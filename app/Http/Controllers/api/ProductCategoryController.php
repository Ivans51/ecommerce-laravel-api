<?php

namespace App\Http\Controllers\api;

use App\Helpers\ApiResponse\DataBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCategoryRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProductCategoryController extends Controller
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
     * @param ProductCategoryRequest $request
     * @return DataBuilder
     * @throws Throwable
     */
    public function store(ProductCategoryRequest $request): DataBuilder
    {
        try {
            $response = ProductCategory::query()
                ->create([
                    'name' => $request->input('name'),
                    'desc' => $request->input('desc'),
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
        $user = ProductCategory::query()
            ->where('id', '=', $id)
            ->first();

        return $this->api->data([$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ProductCategory $productCategory
     * @return Response
     */
    public function edit(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductCategoryRequest $request
     * @param string $id
     * @return DataBuilder
     * @throws Throwable
     */
    public function update(ProductCategoryRequest $request, string $id): DataBuilder
    {
        try {
            $response = ProductCategory::query()
                ->where('id', '=', $id)
                ->update([
                    'name' => $request->input('name'),
                    'desc' => $request->input('desc'),
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

            $productCategory = ProductCategory::whereId($id);

            Product::query()
                ->whereCategoryId($productCategory->first()->id)
                ->update(['category_id' => null]);

            $response = $productCategory->delete();

            return $this->isUpdated($response);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseError($e);
        }
    }
}
