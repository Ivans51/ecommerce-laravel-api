<?php

namespace App\Http\Controllers\api;

use App\Helpers\ApiResponse\DataBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderDetailsRequest;
use App\Models\CartItem;
use App\Models\OrderDetail;
use App\Models\OrderItem;
use App\Models\PaymentDetail;
use App\Models\ShoppingSession;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderDetailsController extends Controller
{
    /**
     * @param Request $request
     * @return DataBuilder
     */
    public function orderDetailsList(Request $request): DataBuilder
    {
        $dateOne = $request->query('date_one');
        $dateTwo = $request->query('date_two');

        $response = OrderDetail::query()
            ->with(['user', 'paymentDetails', 'orderItems', 'orderItems.products'])
            ->createdAt($dateOne, $dateTwo)
            ->paginate(2);

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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OrderDetailsRequest $request
     * @return DataBuilder
     * @throws Throwable
     */
    public function store(OrderDetailsRequest $request): DataBuilder
    {
        try {
            $amount          = 0;
            $user            = $request->user();
            $shoppingSession = ShoppingSession::query()->whereUserId($user->id)->first();

            if ($shoppingSession->exists()) {

                DB::beginTransaction();

                $cartItems = CartItem::query()->whereSessionId($shoppingSession->id);

                $orderDetailsResponse = OrderDetail::query()
                    ->create([
                        'total'   => $shoppingSession->total,
                        'user_id' => $user->id,
                    ]);

                $isOrderItemsResponse = true;
                $cartItemList         = $cartItems->get();

                if ($cartItems->exists() && sizeof($cartItemList) > 0) {
                    foreach ($cartItemList as $item) {
                        $orderItemsSuccess = OrderItem::query()->create([
                            'quantity'   => $item->quantity,
                            'order_id'   => $orderDetailsResponse->id,
                            'product_id' => $item->product_id,
                        ]);

                        if (!$orderItemsSuccess) {
                            $isOrderItemsResponse = false;
                            break;
                        }
                    }

                    /* TODO: stripe */
                    /*$paymentResponse = $this->paymentResponse($orderDetailsResponse, $amount);*/

                    $deleteCartItemsResponse = CartItem::query()->whereSessionId($shoppingSession->id)->delete();
                    $deleteSessionResponse   = ShoppingSession::query()->whereId($shoppingSession->id)->delete();

                    return $this->isUpdated(
                        $orderDetailsResponse->exists(),
                        $isOrderItemsResponse,
                        /*$paymentResponse->exists(),*/
                        $deleteCartItemsResponse,
                        $deleteSessionResponse,
                    );
                } else {
                    return $this->api->status(400)->data([
                        'message' => 'Cart item not exist'
                    ]);
                }

            } else {
                return $this->api->status(400)->data([
                    'message' => 'Cart item not exist'
                ]);
            }

        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseError($e, 'error');
        }
    }

    /**
     * @param OrderDetail $orderDetailsResponse
     * @param int $amount
     * @return PaymentDetail|Model
     */
    public function paymentResponse(OrderDetail $orderDetailsResponse, int $amount): PaymentDetail|Model
    {
        /*$userPayment    = new UserPayment();
        $paymentMethods = $userPayment->paymentMethods();
        $stripeCharge   = $userPayment->charge(100, $paymentMethods);
        $stripeCharge->validate();*/

        return PaymentDetail::query()
            ->create([
                'order_id' => $orderDetailsResponse->id,
                'amount'   => $amount,
                'provider' => '',
                'status'   => '',
                /*'provider' => $stripeCharge->customer()->provider,
                'status'   => $stripeCharge->isSucceeded(),*/
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return DataBuilder
     */
    public function show(string $id): DataBuilder
    {
        $user = OrderDetail::query()
            ->with(['user', 'paymentDetails', 'orderItems', 'orderItems.products'])
            ->where('id', '=', $id)
            ->first();

        return $this->api->data([$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param OrderDetail $orderDetails
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderDetail $orderDetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OrderDetailsRequest $request
     * @param string $id
     * @throws Throwable
     */
    public function update(OrderDetailsRequest $request, string $id)
    {
        //
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

            $response = OrderDetail::whereId($id)->delete();

            return $this->isUpdated($response);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseError($e);
        }
    }
}
