<?php

namespace App\Http\Controllers\api;

use App\Helpers\ApiResponse\DataBuilder;
use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\UserRole;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UserController extends Controller
{
    /**
     * @return DataBuilder
     */
    public function profile(): DataBuilder
    {
        $user = User::query()
            ->with(['userRole'])
            ->where('id', '=', Auth::user()->id)
            ->first();

        return $this->api->data([$user]);
    }

    /**
     * @param Request $request
     * @return DataBuilder
     */
    public function userList(Request $request): DataBuilder
    {
        $perPage = $request->query('per_page', 5);
        $typeId  = $request->query('role_id');
        $email   = $request->query('email');

        $users = User::query()
            ->with(['userRole'])
            ->where('id', '!=', Auth::user()->id)
            ->typeID($typeId)
            ->email($email)
            ->paginate($perPage);

        return $this->api->data([$users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return DataBuilder
     */
    public function store(UserRequest $request): DataBuilder
    {
        try {
            $password = random_bytes(8);

            $roleId = UserRole::query()
                ->where('type', '=', $request->input('type'))
                ->first()
                ->id;

            $user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name'  => $request->input('last_name'),
                'telephone'  => $request->input('telephone'),
                'email'      => $request->input('email'),
                'role_id'    => $roleId,
                'password'   => bcrypt($password)
            ]);

            $templateVerify = $this->messageManage->templateEmailVerify((object)[
                'name'    => $user->first_name,
                'email'   => $user->email,
                'request' => $request,
            ]);

            $templatePassword = $this->messageManage->templateWelcomePassword((object)[
                'name'     => $user->first_name,
                'password' => $password,
            ]);

            $this->messageManage->sendEmail('Verificar email', $templateVerify, $user->email);
            $this->messageManage->sendEmail('Bienvenido', $templatePassword, $user->email);

            return $this->api->status(201)->data([
                'message' => 'Successfully created user!'
            ]);
        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }

    /**
     *
     * @param UserRequest $request
     * @return DataBuilder
     * @throws Throwable
     */
    public function updateImageProfile(UserRequest $request): DataBuilder
    {
        try {
            $user         = User::query()->where('id', '=', $request->input('id'));
            $imageProfile = $user->first()->image_profile;

            if (isset($imageProfile) && Storage::disk('public')->exists($imageProfile)) {
                Storage::disk('public')->delete($imageProfile);
            }

            $file     = request()->file('image_profile');
            $name     = $file->store(Constants::PATH_IMAGE_PROFILE, ['disk' => 'public']);
            $response = $user->update([
                'image_profile' => $name
            ]);

            return $this->isUpdated($response);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseError($e);
        }
    }

    /**
     *
     * @param UserRequest $request
     * @return DataBuilder
     * @throws Throwable
     */
    public function updatePassword(UserRequest $request): DataBuilder
    {
        try {
            $id       = $request->input('id');
            $password = $request->input('password');

            $response = User::query()->where('id', '=', $id)->update([
                'password' => app('hash')->make($password),
            ]);

            return $this->isUpdated($response);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseError($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return DataBuilder
     */
    public function show(string $id): DataBuilder
    {
        $user = User::query()
            ->with(['userRole'])
            ->where('id', '=', $id)
            ->first();

        return $this->api->data([$user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string $id
     * @return DataBuilder
     * @throws Throwable
     */
    public function update(Request $request, string $id): DataBuilder
    {
        try {
            $response = User::where('id', '=', $id)->update([
                'first_name' => $request->input('first_name'),
                'last_name'  => $request->input('last_name'),
                'telephone'  => $request->input('telephone'),
                'email'      => $request->input('email'),
            ]);

            return $this->isUpdated($response);

        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return DataBuilder
     * @throws Throwable
     */
    public function destroy(string $id): DataBuilder
    {
        try {
            if (Auth::user()->id == $id) {
                return $this->api->status(400)->message('No puedes borrar tu usuario en sesión');
            } else {
                DB::beginTransaction();
                $response = User::whereId($id)->delete();
                return $this->isUpdated($response);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseError($e);
        }
    }
}
