<?php

namespace App\Traits\Pos;

use App\Models\Discount;
use App\Models\Invoice;
use App\Models\Pos;
use App\Models\Stock;
use App\Models\Product;
use App\Traits\Payment\PaymentServices;
use Illuminate\Support\Facades\DB;
use App\Traits\Pos\PosSubModelServices;

trait PosServices
{

    use PosSubModelServices, PaymentServices;


    /**
     * Undocumented function
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function loadOrders()
    {
        return Pos::with('posDetails')->get();
    }

    

    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @return Object|null
     */
    public function findCustomerPos(int $customerId): Object|null
    {
        return Pos::where('customer_id', '=', $customerId)
                    ->where('status', '=', 'Pending')
                    ->first();
    }


    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @param integer $productId
     * @return mixed
     */
    public function findPosProduct(int $customerId, int $productId): mixed
    {
        return Pos::where('customer_id', '=', $customerId)
                    ->where('status', '=', 'Pending')
                    ->first()
                    ->posDetails()
                    ->wherePivot('product_id', $productId)
                    ->first()
                    ?? throw new \Exception("Product not found, please try again.");

    }


    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @return Object|null
     */
    public function findCustomerPosDetails(int $customerId): Object|null
    {
        return Pos::where('customer_id', '=', $customerId)
                    ->where('status', '=', 'Pending')
                    ->first()
                    ->posDetails;
    }


    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @return array
     */
    public function getCustomerCartDetails(int $customerId): array
    {
        $customer = $this->findCustomerPos($customerId);

        if (is_null($customer))
        {
            return [];
        }

        $posDetails = $customer->latestPosDetails->map->pivot->map;

        $tax = \number_format($posDetails->tax->sum(), 2);
        $subTotal = \number_format($posDetails->sub_total->sum(), 2);
        $discount = \number_format($posDetails->discount->sum(), 2);
        $total = \number_format($posDetails->total->sum(), 2);

        $orderDetails = [];

        if ($posDetails)
        {
            foreach ($customer->latestPosDetails as $posDetail)
            {
                $orderDetails[] = [
                    'id' => $posDetail->id,
                    'pos_details_id' => $posDetail->id,
                    'product_id' => $posDetail->pivot->product_id,
                    'discount_id' => $posDetail->pivot->discount_id,
                    'product_description' => $posDetail->name,
                    'quantity' => $posDetail->pivot->quantity,
                    'price' => \number_format($posDetail->pivot->price, 2),
                    'discount' => \number_format($posDetail->pivot->discount, 2)
                ];
            }

            return [
                'orderDetails' => $orderDetails,
                'subTotal' => $subTotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total
            ];
        }
    }



    /**
     * Upsert `pos_details` table
     *
     * @param integer $customerId
     * @param integer $productId
     * @return mixed
     */
    public function addToCart(int $customerId, int $productId = NULL, string $productBarcode = NULL): mixed
    {
        try {
            DB::transaction(function () use ($customerId, $productId, $productBarcode)
            {
                # get product data
                $product = (new Product())->getProduct($productId, $productBarcode);

                if (empty($product->stock->in_stock))
                {
                    throw new \Exception("Product is out of stock.");
                }

                $subTotal = $product->price;
                $tax = $subTotal * .12;
                $total = ($subTotal + $tax);

                # product details
                $posDetails = [
                    'quantity' => 1,
                    'price' => $product->price,
                    'unit_of_measurement' => $product->sold_by,
                    'sub_total' => $subTotal,
                    'tax' => $tax,
                    'total' => $total,
                    'created_at' => now(),
                    'updated_at' => NULL
                ];

                $pos = $this->findCustomerPos($customerId);

                # check if `pos_details` exists
                if ($pos)
                {
                    $data = preparePrepend([
                        'pos_id' => $pos->id,
                        'product_id' => $product->id,
                    ], $posDetails);

                    $uniqueBy = [
                        'pos_id',
                        'product_id'
                    ];

                    $update = [
                        'quantity' => DB::raw('pos_details.quantity + values(quantity)'),
                        'sub_total' => DB::raw('pos_details.sub_total + values(sub_total)'),
                        'tax' => DB::raw('pos_details.tax + values(tax)'),
                        'total' => DB::raw('pos_details.total + values(total)'),
                        'created_at' => DB::raw('pos_details.created_at'),
                        'updated_at' => now()
                    ];

                    # insert or update if `pos_id` && `product_id` exists already in the `pos_details`
                    DB::table('pos_details')->upsert($data, $uniqueBy, $update);
                }
                else
                {
                    # create `pos`
                    $pos = Pos::create([
                        'cashier' => auth()->user()->name,
                        'customer_id' => $customerId
                    ]);

                    $posDetails['updated_at'] = now();

                    # create `pos_details`
                    $pos->posDetails()->attach($product->id,
                    $posDetails);
                }
            });
        } catch (\Throwable $th) {
           return $th->getMessage();
        }

        return true;
    }



    /**
     * Undocumented function
     *
     * @param integer $productId
     * @param integer $discountId
     * @return mixed
     */
    public function assignDiscountToAll(int $customerId, int $discountId): mixed
    {
        try {
            DB::transaction(function () use($customerId, $discountId)
            {
                # select customer
                $customerPos = $this->findCustomerPos($customerId);

                if (!$customerPos)
                {
                    throw new \Exception("Customer has yet to order");
                }

                # select discount
                $discount = ((new Discount())->getDiscount($discountId)->percentage / 100);

                # update customer `pos_details` discount
                $customerPos->posDetails()
                    ->update([
                        'discount_id' => $discountId,
                        'discount' => DB::raw("sub_total * $discount"),
                        'total' => DB::raw("(sub_total + tax) - (sub_total * $discount)"),
                        'updated_at' => now()
                    ])
                    ?? throw new \Exception("Error Processing Discount Request");
            });

        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }



    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @return mixed
     */
    public function removeDiscountToAll(int $customerId): mixed
    {
        try {

            DB::transaction(function () use($customerId)
            {
                # select customer
                $customerPos = $this->findCustomerPos($customerId);

                if (!$customerPos)
                {
                    throw new \Exception("Customer has yet to order");
                }

                # update customer `pos_details` discount
                $result = \boolval($customerPos->posDetails()
                    ->update([
                        'discount_id' => NULL,
                        'total' => DB::raw('total + discount'),
                        'discount' => 0.00,
                    ]));

                if (!$result)
                {
                    throw new \Exception("Product was not within customer's order, discount removal failed.");
                }
            });

        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }


    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @param string $paymentMethod
     * @param float|null $cash
     * @param boolean $shouldMail
     * @param int|null $numberOfDays
     * @param string|null $customerEmail
     * @return void
     */
    public function processPayment(
        int $customerId,
        string $paymentMethod,
        $cash = null,
        bool $shouldMail = false,
        $numberOfDays = null,
        string $customerEmail = null,
        string $customerName = null)
    {
        return $this->processingPayment($customerId,
            $paymentMethod,
            $cash,
            $shouldMail,
            $numberOfDays,
            $customerEmail,
            $customerName);
    }


    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @param integer $productId
     * @param integer $quantity
     * @return mixed
     */
    public function updateOrderQty(int $customerId, int $productId, int $quantity): mixed
    {
        try {
            DB::transaction(function () use($customerId, $productId, $quantity)
            {
                $pos = $this->findCustomerPos($customerId);

                if (!$pos)
                {
                    throw new \Exception("Customer has yet to order");
                }

                $result = \boolval($pos->posDetails()
                        ->updateExistingPivot($productId,[
                            'quantity' => $quantity,
                            'sub_total' => DB::raw('price * quantity'),
                            'tax' => DB::raw('sub_total * 0.12' ),
                            'total' => DB::raw('sub_total + tax'),
                            'updated_at' => now()
                        ])
                );

                if (!$result)
                {
                    throw new \Exception("Product was not found within customer's order");
                }
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }


    public function applyDiscountWithQuantity(int $customerId, int $productId, bool $hasDiscount, int $quantity, $discountId = NULL): mixed
    {
        try {
            DB::transaction(function () use($customerId, $productId, $hasDiscount, $quantity, $discountId,)
            {
                $customerPos = $this->findCustomerPos($customerId);

                if (!$customerPos)
                {
                    throw new \Exception("Customer has yet to order");
                }

                # select discount
                if ($hasDiscount)
                {
                    $discount = ((new Discount())->getDiscount($discountId)->percentage / 100);

                    $result = \boolval($customerPos->posDetails()
                            ->updateExistingPivot($productId,[
                                'quantity' => $quantity,
                                'sub_total' => DB::raw('price * quantity'),
                                'discount_id' => $discountId,
                                'discount' => DB::raw('sub_total * ' . $discount),
                                'tax' => DB::raw('sub_total * 0.12' ),
                                'total' => DB::raw('sub_total + tax - discount'),
                                'updated_at' => now()
                            ])
                    );
                }
                else
                {
                    $result = \boolval($customerPos->posDetails()
                        ->updateExistingPivot($productId,[
                            'quantity' => $quantity,
                            'sub_total' => DB::raw('price * quantity'),
                            'discount_id' => NULL,
                            'discount' => 0.00,
                            'tax' => DB::raw('sub_total * 0.12'),
                            'total' => DB::raw('sub_total + tax - discount'),
                            'updated_at' => now()
                        ])
            );
                }

                if (!$result)
                {
                    throw new \Exception("Product was not found within customer's order");
                }
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }




    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @param array $productId
     * @return mixed
     */
    public function removeItem(int $customerId, array $productIds): mixed
    {
        try {
            DB::transaction(function () use($customerId, $productIds)
            {
                $pos = $this->findCustomerPos($customerId);

                if (!$pos)
                {
                    throw new \Exception("Customer has yet to order");
                }

                return \boolval($pos->posDetails()->detach($productIds));
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }


    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @param integer $productId
     * @return mixed
     */
    public function cancelOrders(int $customerId): mixed
    {
        try {
            DB::transaction(function () use($customerId)
            {
                # select customer `pos`
                $customerPos = $this->findCustomerPos($customerId);

                if (!$customerPos)
                {
                    throw new \Exception("Customer has yet to order");
                }

                $customerPos->status = 'Cancelled';

                # detach customer `pos_details`
                $customerPos->posDetails()->detach();

                $customerPos->save();

            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }




    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @param string $status
     * @return boolean
     */
    public function updateCustomerPosStatus(int $customerId, string $status): bool
    {
        return \boolval(Pos::where('customer_id', '=', $customerId)
                            ->where('status', '=', 'Processing')
                            ->update([
                                'status' => $status
                            ])
        );
    }

}
