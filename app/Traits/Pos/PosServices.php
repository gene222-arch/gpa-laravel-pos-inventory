<?php

namespace App\Traits\Pos;

use App\Models\Invoice;
use App\Models\Pos;
use App\Models\PosPayment;
use App\Models\Stock;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Traits\Pos\PosHelperServices;

trait PosServices
{

    use PosHelperServices;

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
                    ->where('status', '=', 'Processing')
                    ->first();
    }


    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @param integer $productId
     * @return Product
     */
    public function findPosProduct(int $customerId, int $productId): Product
    {
        return Pos::where('customer_id', '=', $customerId)
                    ->where('status', '=', 'Processing')
                    ->first()
                    ->posDetails()
                    ->wherePivot('product_id', $productId)
                    ->first();
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
                    ->where('status', '=', 'Processing')
                    ->first()
                    ->posDetails;
    }


    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @return integer
     */
    public function getCustomerAmountToPay(int $customerId): int
    {
        return $this->findCustomerPosDetails($customerId)
                    ->map
                    ->pivot
                    ->map
                    ->amount
                    ->sum();
    }


    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @param integer $productId
     * @return boolean
     */
    public function addToCart(int $customerId, int $productId = NULL, string $productBarcode = NULL): bool
    {
        try {
            DB::transaction(function () use ($customerId, $productId, $productBarcode)
            {
                # get product data
                $product = (new Product())->getProduct($productId, $productBarcode);

                # product details
                $posDetails = [
                    'quantity' => 1,
                    'price' => $product->price,
                    'unit_of_measurement' => $product->sold_by,
                    'amount' => ($product->price * 1),
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
                        'amount' => DB::raw('pos_details.amount + values(amount)'),
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
                        'customer_id' => $customerId
                    ]);

                    $posDetails['updated_at'] = now();

                    # create `pos_details`
                    $pos->posDetails()->attach($product->id,
                    $posDetails);
                }
            });
        } catch (\Throwable $th) {
           return false;
        }

        return true;
    }


    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @param integer $productId
     * @return boolean
     */
    public function incrementItemQuantity(int $customerId, int $productId): bool
    {
        try {
            DB::transaction(function () use($customerId, $productId)
            {
                # select product `stocks`
                $productStock = (new Stock())->getRemainingStockOf($productId);

                # select order qty
                $currentOrderQty = $this->findPosProduct($customerId, $productId)->pivot->quantity;

                if ($currentOrderQty === $productStock)
                {
                    throw new \ErrorException("Error Processing Request", 1);
                }

                $totalOrderQty = $currentOrderQty + 1;

                # update `pos_details`
                $this->findCustomerPos($customerId)->posDetails()
                        ->wherePivot('product_id', $productId)
                        ->updateExistingPivot($productId,[
                            'quantity' => $totalOrderQty,
                            'amount' => DB::raw('price * ' . ($currentOrderQty + 1)),
                        ]);

            });
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }


    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @param integer $productId
     * @return boolean
     */
    public function decrementItemQuantity(int $customerId, int $productId): bool
    {
        try {
            $pos = $this->findCustomerPos($customerId);

            # update `pos_details`
            $isDecreased = \boolval($pos->posDetails()
                                        ->wherePivot('product_id', $productId)
                                        ->wherePivot('quantity', '>', 1)
                                        ->updateExistingPivot($productId, [
                                            'quantity' => DB::raw('quantity - 1'),
                                            'amount' => DB::raw('amount - price')
                                        ])
            );

            # update `stocks`
            if (!$isDecreased)
            {
                throw new \ErrorException("Error");
            }
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }



    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @param string $paymentMethod
     * @return void
     */
    public function processPayment(
        int $customerId,
        string $paymentMethod,
        $cash = 0.00,
        $shippingFee = 0.00,
        $numberOfDays = NULL)
    {
        try {
            DB::transaction(function () use(
                $customerId,
                $paymentMethod,
                $cash,
                $shippingFee,
                $numberOfDays)
            {
                # select customer `pos`
                $customerPos = $this->findCustomerPos($customerId);

                if (!$customerPos)
                {
                    throw new \ErrorException("Customer Pos data does not exists");
                }

                # select customer order list in `pos_details`
                $customerPosDetails = $customerPos->posDetails->map->pivot;

                switch ($paymentMethod)
                {
                    case 'cash':
                        $this->payWithCash($customerPos, $cash, $shippingFee);
                        break;

                    case 'credit':
                        $this->payWithCredit($customerPos, $shippingFee);
                        break;

                    case 'invoice':
                        $this->payWithInvoice($customerId, $customerPosDetails, $numberOfDays);
                        break;
                }

                # customer `pos` is processed
                $customerPos->update([
                    'status' => 'Processed'
                ]);

                # update stocks
                (new Stock())->stockOutMany($customerPosDetails);
            });
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }


    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @param integer $productId
     * @param integer $quantity
     * @return boolean
     */
    public function updateOrderQty(int $customerId, int $productId, int $quantity): bool
    {
        $pos = $this->findCustomerPos($customerId);

        return \boolval($pos->posDetails()
                            ->updateExistingPivot($productId,[
                                'quantity' => $quantity,
                                'amount' => DB::raw('amount * ' . $quantity)
                            ])
        );
    }



    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @param integer $productId
     * @return boolean
     */
    public function removeItem(int $customerId, int $productId): bool
    {
        $pos = $this->findCustomerPos($customerId);

        return \boolval($pos->posDetails()->detach($productId));
    }


    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @param integer $productId
     * @return boolean
     */
    public function cancelOrders(int $customerId): bool
    {
        try {
            DB::transaction(function () use($customerId)
            {
                # select customer `pos`
                $customerPos = $this->findCustomerPos($customerId);

                if (!$customerPos)
                {
                    throw new \ErrorException("Error Processing Cancellation of Order Request");
                }

                # detach customer `pos_details`
                $customerPos->posDetails()->detach();

                # update customer `pos` ['status'] field
                $customerPos->updateTs([
                    'status' => 'Cancelled'
                ]);
            });
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }



    /**
     * Undocumented function
     *
     * @param [type] $customerPos
     * @param float $shippingFee
     * @return void
     */
    private function payWithCash($customerPos, float $cash, float $shippingFee): void
    {
        $subTotal = $customerPos->posDetails
                                ->map
                                ->pivot
                                ->map
                                ->amount
                                ->sum();

        $discount = 0.00;
        $tax = $subTotal * 0.12;

        $total = (($subTotal - $discount) + ($tax + $shippingFee));

        $change = $cash - $total;

        $paymentDetails = [
            'cashier' => auth()->user()->name,
            'sub_total' => $subTotal,
            'discount' => $discount,
            'tax' => $tax,
            'shipping_fee' => $shippingFee,
            'total' => $total,
            'cash' => $cash,
            'change' => $change
        ];

        $customerPos->posPayment()->create($paymentDetails);
    }



    /**
     * Undocumented function
     *
     * @param [type] $customerPos
     * @param float $shippingFee
     * @return void
     */
    private function payWithCredit($customerPos, float $shippingFee = 0.00): void
    {
        $subTotal = $customerPos->posDetails
                                ->map
                                ->pivot
                                ->map
                                ->amount
                                ->sum();
        $discount = 0.00;
        $tax = $subTotal * 0.12;

        $total = (($subTotal - $discount) + ($tax + $shippingFee));

        $paymentDetails = [
            'cashier' => auth()->user()->name,
            'sub_total' => $subTotal,
            'discount' => $discount,
            'tax' => $tax,
            'shipping_fee' => $shippingFee,
            'total' => $total,
            'cash' => $subTotal,
        ];

        $customerPos->posPayment()->create($paymentDetails);
    }



    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @param [type] $customerPosDetails
     * @return void
     */
    private function payWithInvoice(int $customerId, $customerPosDetails, int $numberOfDays): void
    {
        foreach ($customerPosDetails as $customerPosDetail)
        {
            $invoiceDetails[] =
            [
                'product_id' => $customerPosDetail->product_id,
                'quantity' => $customerPosDetail->quantity,
                'price' => $customerPosDetail->price,
                'unit_of_measurement' => $customerPosDetail->unit_of_measurement,
                'amount' => $customerPosDetail->amount,
                'created_at' => now()
            ];
        }

        # create invoice
        (new Invoice())->generateSalesInvoice($customerId, $invoiceDetails, $numberOfDays);
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
