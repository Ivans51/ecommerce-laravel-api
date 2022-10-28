<?php

namespace App\Http\Controllers\api;

use App\Helpers\ApiResponse\DataBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\CartItemRequest;
use App\Models\CartItem;
use App\Models\ShoppingSession;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

class CartItemController extends Controller
{
    /**
     * @param Request $request
     * @return DataBuilder
     */
    public function cartItemList(Request $request): DataBuilder
    {
        $perPage = $request->query('per_page', 5);
        $dateOne = $request->query('date_one');
        $dateTwo = $request->query('date_two');

        $response = CartItem::query()
            ->createdAt($dateOne, $dateTwo)
            ->paginate($perPage);

        return $this->api->data($response);
    }

    /**
     * Display a listing of the resource.
     *
     * @return DataBuilder
     */
    public function index(): DataBuilder
    {
        return $this->api->message('success');
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
     * @param CartItemRequest $request
     * @return DataBuilder
     * @throws Throwable
     */
    public function store(CartItemRequest $request): DataBuilder
    {
        try {
            DB::beginTransaction();

            $shoppingSession = ShoppingSession::query()->whereId($request->input('session_id'));

            $cartItemResponse = CartItem::query()
                ->create([
                    'quantity'   => $request->input('quantity'),
                    'session_id' => $request->input('session_id'),
                    'product_id' => $request->input('product_id'),
                ]);

            $shoppingResponse = $shoppingSession->update([
                'total' => $shoppingSession->first()->total + 1
            ]);

            return $this->isUpdated($cartItemResponse, $shoppingResponse);

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
        $user = CartItem::query()
            ->where('id', '=', $id)
            ->first();

        return $this->api->data([$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CartItem $cartItem
     */
    public function edit(CartItem $cartItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CartItemRequest $request
     * @param string $id
     * @return DataBuilder
     * @throws Throwable
     */
    public function update(CartItemRequest $request, string $id): DataBuilder
    {
        try {
            DB::beginTransaction();

            $response = CartItem::query()
                ->where('id', '=', $id)
                ->update([
                    'quantity'   => $request->input('quantity'),
                    'session_id' => $request->input('session_id'),
                    'product_id' => $request->input('product_id'),
                ]);

            return $this->isUpdated($response);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseError($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return DataBuilder
     * @throws Throwable
     * @throws Throwable
     */
    public function destroy(string $id): DataBuilder
    {
        try {
            $response = CartItem::whereId($id)->delete();
            return $this->isUpdated($response);
        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }
}
