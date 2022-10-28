<?php


namespace App\Helpers;


use App\Helpers\ApiResponse\DataBuilder;
use Exception;
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
    public function isUpdated(...$checkList): DataBuilder
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
            return $this->api->status(204);
        } else {
            DB::rollBack();
            return $this->api->status(400)->data([
                'message' => 'Something is wrong!'
            ]);
        }
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

}
