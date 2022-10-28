<?php

namespace App\Http\Controllers\api;

use App\Helpers\ApiResponse\DataBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountRequest;
use App\Models\Discount;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class DiscountController extends Controller
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
     * @param DiscountRequest $request
     * @return DataBuilder
     * @throws Throwable
     */
    public function store(DiscountRequest $request): DataBuilder
    {
        try {
            $response = Discount::query()
                ->create([
                    'name'                => $request->input('name'),
                    'desc'                => $request->input('desc'),
                    'discount_percentage' => $request->input('discount_percentage'),
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
        $user = Discount::query()
            ->where('id', '=', $id)
            ->first();

        return $this->api->data([$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Discount $discount
     * @return \Illuminate\Http\Response
     */
    public function edit(Discount $discount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DiscountRequest $request
     * @param string $id
     * @return DataBuilder
     * @throws Throwable
     */
    public function update(DiscountRequest $request, string $id): DataBuilder
    {
        try {
            $response = Discount::query()
                ->where('id', '=', $id)
                ->update([
                    'name'                => $request->input('name'),
                    'desc'                => $request->input('desc'),
                    'discount_percentage' => $request->input('discount_percentage'),
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
            DB::beginTransaction();

            $productInventory = Discount::whereId($id);

            Product::query()
                ->whereDiscountId($productInventory->first()->id)
                ->update(['discount_id' => null]);

            $response = $productInventory->delete();

            return $this->isUpdated($response);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseError($e);
        }
    }
}
