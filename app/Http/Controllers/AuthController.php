<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse\DataBuilder;
use App\Helpers\Constants;
use App\Http\Requests\AuthRequest;
use App\Models\ShoppingSession;
use App\Models\User;
use App\Models\UserRole;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthController extends Controller
{
    /**
     * @param AuthRequest $request
     * @return DataBuilder
     */
    public function loginCustomer(AuthRequest $request): DataBuilder
    {
        return $this->login($request, Constants::ROLE_CUSTOMER);
    }

    /**
     * @param AuthRequest $request
     * @return DataBuilder
     */
    public function loginAdmin(AuthRequest $request): DataBuilder
    {
        return $this->login($request, Constants::ROLE_ADMIN);
    }

    /**
     * @param AuthRequest $request
     * @param string ...$roleType
     * @return DataBuilder
     */
    public function login(AuthRequest $request, string ...$roleType): DataBuilder
    {
        try {
            if ($this->getScoreRecaptcha($request) > 0.7) {
                $credentials = request(['email', 'password']);

                if (!Auth::attempt($credentials)) {
                    return $this->api->status(401)->message('User or password incorrect');
                }

                $user = Auth::user();
                $role = UserRole::query()->whereType($roleType[0])->first();

                if ($user->role_id != $role->id) {
                    return $this->api->status(401)->message('Without permission');
                }

                $tokenResult = $user->createToken(config('app.custom_token'), $roleType);

                return $this->api->data([
                    'token'            => $tokenResult->plainTextToken,
                    'token_type'       => 'Bearer',
                    'abilities'        => $tokenResult->accessToken->abilities,
                    'shopping_session' => ShoppingSession::query()->whereUserId($user->id)->first()
                ]);
            } else {
                return $this->api->status(400)->message('Validar captcha');
            }
        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }

    /**
     * @param AuthRequest $request
     * @return DataBuilder
     * @throws Throwable
     */
    public function signUp(AuthRequest $request): DataBuilder
    {
        try {
            DB::beginTransaction();

            $typeId = UserRole::query()
                ->where('type', '=', $request->input('type_role'))
                ->first()
                ->id;

            $user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name'  => $request->input('last_name'),
                'telephone'  => $request->input('telephone'),
                'email'      => $request->input('email'),
                'role_id'    => $typeId,
                'password'   => bcrypt($request->input('password'))
            ]);

            ShoppingSession::query()->create([
                'user_id' => $user->id
            ]);

            $template = $this->messageManage->templateEmailVerify((object)[
                'name'    => $user->first_name,
                'email'   => $user->email,
                'request' => $request,
            ]);

            $this->messageManage->sendEmail('Verificar email', $template, $user->email);

            DB::commit();

            return $this->api->data([
                'message' => 'Successfully created user!'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseError($e);
        }
    }

    /**
     * @param AuthRequest $request
     * @return int
     */
    private function getScoreRecaptcha(AuthRequest $request): int
    {
        try {
            $isProd = config('app.env') == Constants::PROD;
            /*return $isProd ? (new RecaptchaHTTP())->verify($request, 'captcha') : 1;*/
            return 1;
        } catch (GuzzleException $e) {
            Log::error($e->getMessage());
            return 0;
        }
    }

    /**
     * @param AuthRequest $request
     * @return DataBuilder
     */
    public function forgotPassword(AuthRequest $request): DataBuilder
    {
        try {
            $email = $request->input('email');

            $user = User::query()
                ->whereEmail($email)
                ->first();

            $template = $this->messageManage->templateEmailForgot((object)[
                'name'    => $user->first_name,
                'email'   => $user->email,
                'request' => $request,
            ]);

            $this->messageManage->sendEmail('Cambio de Contraseña', $template, $user->email);

            return $this->api->status(204);

        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }

    /**
     * @param AuthRequest $request
     * @return DataBuilder
     */
    public function updateForgotPassword(AuthRequest $request): DataBuilder
    {
        try {
            $email    = $request->input('email');
            $token    = $request->input('token');
            $password = $request->input('password');

            $user = User::query()
                ->where('email', '=', $email)
                ->where('token_password_forgot', '=', $token)
                ->first();

            if (isset($user) && !empty($user)) {
                User::whereEmail($email)->update(
                    [
                        'password'              => app('hash')->make($password),
                        'token_password_forgot' => null
                    ]
                );

                return $this->api->status(204);
            } else {
                return $this->api
                    ->status(400)
                    ->message('Información de seguridad expiró o es incorrecta');
            }
        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }

    /**
     * @param AuthRequest $request
     * @return DataBuilder
     */
    public function verifyEmail(AuthRequest $request): DataBuilder
    {
        try {
            $email = $request->input('email');

            $user = User::query()
                ->whereEmail($email)
                ->first();

            $template = $this->messageManage->templateEmailVerify((object)[
                'name'    => $user->first_name,
                'email'   => $user->email,
                'request' => $request,
            ]);

            $this->messageManage->sendEmail('Verificar email', $template, $user->email);

            return $this->api->status(204);

        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }

    /**
     * @param AuthRequest $request
     * @return DataBuilder
     */
    public function updateVerifyEmail(AuthRequest $request): DataBuilder
    {
        try {
            $email = $request->input('email');
            $token = $request->input('token');

            $user = User::query()
                ->where('email', '=', $email)
                ->where('token_email_verify', '=', $token)
                ->first();

            if (isset($user) && !empty($user)) {
                User::whereEmail($email)->update(
                    [
                        'token_email_verify' => null,
                        'email_verified_at'  => now(),
                    ]
                );

                return $this->api->status(204);
            } else {
                return $this->api
                    ->status(400)
                    ->message('Información de seguridad expiró o es incorrecta');
            }
        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }

    /**
     * @param Request $request
     * @return DataBuilder
     */
    public function logout(Request $request): DataBuilder
    {
        $request->user()->currentAccessToken()->delete();

        return $this->api->data([
            'message' => 'Successfully logged out'
        ]);
    }
}
