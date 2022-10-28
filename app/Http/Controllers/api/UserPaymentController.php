<?php

namespace App\Http\Controllers\api;

use App\Helpers\ApiResponse\DataBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserPaymentRequest;
use App\Models\UserPayment;
use Exception;
use Throwable;

class UserPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserPaymentRequest $request
     * @return DataBuilder
     * @throws Throwable
     */
    public function store(UserPaymentRequest $request): DataBuilder
    {
        try {
            $response = UserPayment::query()
                ->create([
                    'payment_type' => $request->input('payment_type'),
                    'provider'     => $request->input('provider'),
                    'account_no'   => $request->input('account_no'),
                    'expiry'       => $request->input('expiry'),
                    'user_id'      => $request->input('user_id'),
                ]);

            return $this->isUpdated($response);

        } catch (Exception $e) {
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
        $user = UserPayment::query()
            ->with(['user'])
            ->where('id', '=', $id)
            ->first();

        return $this->api->data([$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param UserPayment $userPayment
     * @return \Illuminate\Http\Response
     */
    public function edit(UserPayment $userPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserPaymentRequest $request
     * @param string $id
     * @return DataBuilder
     * @throws Throwable
     */
    public function update(UserPaymentRequest $request, string $id): DataBuilder
    {
        try {
            $response = UserPayment::query()
                ->where('id', '=', $id)
                ->update([
                    'payment_type' => $request->input('payment_type'),
                    'provider'     => $request->input('provider'),
                    'account_no'   => $request->input('account_no'),
                    'expiry'       => $request->input('expiry'),
                    'user_id'      => $request->input('user_id'),
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
            $response = UserPayment::whereId($id)->delete();
            return $this->isUpdated($response);
        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }
}
