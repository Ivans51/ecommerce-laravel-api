<?php

namespace App\Http\Controllers\api;

use App\Helpers\ApiResponse\DataBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddress;
use Exception;
use Throwable;

class UserAddressController extends Controller
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
     * @param UserAddressRequest $request
     * @return DataBuilder
     * @throws Throwable
     */
    public function store(UserAddressRequest $request): DataBuilder
    {
        try {
            $response = UserAddress::query()
                ->create([
                    'address_line1' => $request->input('address_line1'),
                    'address_line2' => $request->input('address_line2'),
                    'city'          => $request->input('city'),
                    'postal_code'   => $request->input('postal_code'),
                    'country'       => $request->input('country'),
                    'telephone'     => $request->input('telephone'),
                    'mobile'        => $request->input('mobile'),
                    'user_id'       => $request->input('user_id'),
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
        $user = UserAddress::query()
            ->with(['user'])
            ->where('id', '=', $id)
            ->first();

        return $this->api->data([$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param UserAddress $userAddress
     * @return \Illuminate\Http\Response
     */
    public function edit(UserAddress $userAddress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserAddressRequest $request
     * @param string $id
     * @return DataBuilder
     * @throws Throwable
     */
    public function update(UserAddressRequest $request, string $id): DataBuilder
    {
        try {
            $response = UserAddress::query()
                ->where('id', '=', $id)
                ->update([
                    'address_line1' => $request->input('address_line1'),
                    'address_line2' => $request->input('address_line2'),
                    'city'          => $request->input('city'),
                    'postal_code'   => $request->input('postal_code'),
                    'country'       => $request->input('country'),
                    'telephone'     => $request->input('telephone'),
                    'mobile'        => $request->input('mobile'),
                    'user_id'       => $request->input('user_id'),
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
            $userAddress = UserAddress::whereId($id)->delete();
            return $this->isUpdated($userAddress);
        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }
}
