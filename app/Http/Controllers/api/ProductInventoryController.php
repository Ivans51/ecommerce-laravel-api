<?php

namespace App\Http\Controllers\api;

use App\Helpers\ApiResponse\DataBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductInventoryRequest;
use App\Models\Product;
use App\Models\ProductInventory;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProductInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductInventoryRequest $request
     * @return DataBuilder
     * @throws Throwable
     */
    public function store(ProductInventoryRequest $request): DataBuilder
    {
        try {
            $response = ProductInventory::query()
                ->create([
                    'quantity' => $request->input('quantity'),
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
        $user = ProductInventory::query()
            ->where('id', '=', $id)
            ->first();

        return $this->api->data([$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ProductInventory $productInventory
     * @return Response
     */
    public function edit(ProductInventory $productInventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductInventoryRequest $request
     * @param string $id
     * @return DataBuilder
     * @throws Throwable
     */
    public function update(ProductInventoryRequest $request, string $id): DataBuilder
    {
        try {
            $response = ProductInventory::query()
                ->where('id', '=', $id)
                ->update([
                    'quantity' => $request->input('quantity'),
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

            $productInventory = ProductInventory::whereId($id);

            Product::query()
                ->whereInventoryId($productInventory->first()->id)
                ->delete();

            $response = $productInventory->delete();

            return $this->isUpdated($response);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseError($e);
        }
    }
}
