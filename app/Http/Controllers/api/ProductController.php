<?php

namespace App\Http\Controllers\api;

use App\Helpers\ApiResponse\DataBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProductController extends Controller
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
     * @param ProductRequest $request
     * @return DataBuilder
     * @throws Throwable
     */
    public function store(ProductRequest $request): DataBuilder
    {
        try {
            $response = Product::query()
                ->create([
                    'name'         => $request->input('name'),
                    'desc'         => $request->input('desc'),
                    'SKU'          => $request->input('SKU'),
                    'price'        => $request->input('price'),
                    'category_id'  => $request->input('category_id'),
                    'inventory_id' => $request->input('inventory_id'),
                    'discount_id'  => $request->input('discount_id'),
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
        $user = Product::query()
            ->where('id', '=', $id)
            ->first();

        return $this->api->data([$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductRequest $request
     * @param string $id
     * @return DataBuilder
     * @throws Throwable
     */
    public function update(ProductRequest $request, string $id): DataBuilder
    {
        try {
            $response = Product::query()
                ->where('id', '=', $id)
                ->update([
                    'name'         => $request->input('name'),
                    'desc'         => $request->input('desc'),
                    'SKU'          => $request->input('SKU'),
                    'price'        => $request->input('price'),
                    'category_id'  => $request->input('category_id'),
                    'inventory_id' => $request->input('inventory_id'),
                    'discount_id'  => $request->input('discount_id'),
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

            $response = Product::whereId($id)->delete();
            return $this->isUpdated($response);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseError($e);
        }
    }
}
