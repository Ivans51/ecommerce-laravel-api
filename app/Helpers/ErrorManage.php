<?php


namespace App\Helpers;


use App\Helpers\ApiResponse\DataBuilder;
use App\Models\UserRole;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Throwable;

class ErrorManage extends BaseController
{
    public MessageManage $messageManage;
    public DataBuilder $api;

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->messageManage = new MessageManage();
        $this->api           = new DataBuilder();
    }

    /**
     * @param ...$checkList
     * @return DataBuilder
     * @throws Throwable
     */
    public function isCreated(...$checkList): DataBuilder
    {
        return $this->returnStatus($checkList, 201);
    }

    /**
     * @param ...$checkList
     * @return DataBuilder
     * @throws Throwable
     */
    public function isUpdated(...$checkList): DataBuilder
    {
        return $this->returnStatus($checkList, 204);
    }

    /**
     * @param array $checkList
     * @param int $status
     * @return DataBuilder
     * @throws Throwable
     */
    private function returnStatus(array $checkList, int $status): DataBuilder
    {
        $isPass = true;
        foreach ($checkList as $check) {
            if (!$check) {
                $isPass = false;
                break;
            }
        }

        if ($isPass) {
            DB::commit();
            return $this->api->status($status);
        } else {
            DB::rollBack();
            return $this->api->status(400)->data([
                'message' => 'Something is wrong!'
            ]);
        }
    }

    /**
     * @param string $table
     * @param $value
     * @param $column
     * @param $exceptValue
     * @return bool
     */
    public function checkExist(string $table, $value, $column, $exceptValue): bool
    {
        return DB::table($table)
            ->where('id', '!=', $exceptValue)
            ->where($column, $value)
            ->exists();
    }

    /**
     * @param Exception $exception
     * @param string $message
     * @return DataBuilder
     */
    public function responseError(Exception $exception, string $message = 'Something is wrong!'): DataBuilder
    {
        return $this->api->status(400)->message($this->messageError($exception, $message));
    }

    /**
     * @param Exception $exception
     * @param string $message
     * @return string
     */
    private function messageError(Exception $exception, string $message): string
    {
        $isProd = config('app.env') == Constants::PROD;
        return $isProd ? $message : $exception->getMessage();
    }

    /**
     * @param string $message
     * @return DataBuilder
     */
    public function responseMessageError(string $message = 'Something is wrong!'): DataBuilder
    {
        return $this->api->status(400)->message($message);
    }

    /**
     * @return Builder|UserRole|null
     */
    public function getUserRoleGuest(): Builder|UserRole|null
    {
        $userRole = UserRole::query()
            ->where('type', Constants::ROLE_GUEST);

        if ($userRole->exists()) {
            return $userRole->first();
        } else {
            return UserRole::query()->create([
                'type'        => Constants::ROLE_GUEST,
                'permissions' => '',
            ]);
        }
    }

}
