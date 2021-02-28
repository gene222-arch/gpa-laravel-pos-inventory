<?php

namespace App\Traits\Payment;

use App\Jobs\QueuePaymentNotification;
use App\Models\Pos;
use App\Models\Stock;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Traits\PDF\PDFGeneratorServices;
use App\Notifications\PaymentNotification;
use App\Traits\Notifications\ShouldMailServices;

trait PaymentServices
{

    use PDFGeneratorServices, ShouldMailServices;

    /**
     * Undocumented function
     *
     * @return array
     */
    public function loadPayments(): array
    {
        DB::statement("SET sql_mode='' ");

        return DB::table('pos_payments')
            ->join('pos', 'pos.id', '=', 'pos_payments.pos_id')
            ->join('customers', 'customers.id', '=', 'pos.customer_id')
            ->selectRaw('
                pos_payments.id as pos_payment_id,
                customers.name as customer_name,
                pos_payments.cashier,
                pos_payments.payment_method,
                pos_payments.total,
                pos_payments.cash,
                pos_payments.change,
                DATE_FORMAT(pos_payments.created_at, "%M %d, %Y") as date_ordered
            ')
            ->groupBy('pos_payment_id'
            )
            ->get()
            ->toArray();
    }



    /**
     * Undocumented function
     *
     * @param integer $paymentId
     * @return array
     */
    public function paymentPosDetailstoPDF(int $paymentId): array
    {
        $paymentPosDetails = DB::table('pos_payments')
                    ->where('pos_payments.id', '=', $paymentId)
                    ->join('pos_details', 'pos_details.pos_id', '=', 'pos_payments.pos_id')
                    ->join('products', 'products.id', '=', 'pos_details.product_id')
                    ->selectRaw('
                        products.name as product_description,
                        pos_details.quantity as quantity,
                        pos_details.price as unit_price,
                        pos_details.sub_total as sub_total
                    ')
                    ->get()
                    ->toArray();


        foreach ($paymentPosDetails as $paymentPosDetail)
        {
            $paymentPosDetail->unit_price = number_format($paymentPosDetail->unit_price, 2);
            $paymentPosDetail->sub_total = number_format($paymentPosDetail->sub_total, 2);
        }

        return $paymentPosDetails;
    }



    /**
     * Undocumented function
     *
     * @param integer $paymentId
     */
    public function paymentCustomerInfo(int $paymentId)
    {
        return DB::table('pos_payments')
            ->where('pos_payments.id', '=', $paymentId)
            ->join('pos', 'pos.id', '=', 'pos_payments.pos_id')
            ->join('customers', 'customers.id', '=', 'pos.customer_id')
            ->selectRaw("
                customers.`name` as name,
                customers.email as email,
                customers.phone as phone,
                customers.address as address,
                CONCAT(customers.city, ', ',customers.province, ', ', customers.country) as complete_address,
                customers.postal_code
            ")
            ->first();
    }



    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @param string $paymentMethod
     * @param float|null $cash
     * @param int|null $numberOfDays
     * @param string|null $customerEmail,
     * @param string|null $customerName
     * @return void
     */
    public function processingPayment(
        int $customerId,
        string $paymentMethod,
        float $cash = null,
        bool $shouldMail = false,
        int $numberOfDays = null,
        string $customerEmail = null,
        $customerName = null)
    {
        try {
            DB::transaction(function () use(
                $customerId,
                $paymentMethod,
                $cash,
                $shouldMail,
                $numberOfDays,
                $customerEmail,
                $customerName)
            {

                $cash = $cash ?? 0.00;
                $customerEmail = $customerEmail ?? '';
                $customerName = $customerName ?? '';

                # select customer `pos`
                $customerPos = (new Pos())->findCustomerPos($customerId);

                if (!$customerPos)
                {
                    throw new \Exception("Customer has yet to order.");
                }

                # select customer order list in `pos_details`
                $customerOrderDetails = $customerPos->posDetails->map->pivot;

                switch ($paymentMethod)
                {
                    case 'cash':
                        $this->payWithCash(
                            $customerId,
                            $customerPos,
                            $cash,
                            $shouldMail,
                            $customerEmail,
                            $customerName
                        );

                        $customerPos->status = 'Cash';
                        break;


                    case 'credit':
                        $this->payWithCredit(
                            $customerId,
                            $customerPos,
                            $shouldMail,
                            $customerEmail,
                            $customerName);

                        $customerPos->status = 'Credit';
                        break;


                    case 'invoice':
                        $this->payWithInvoice(
                            $customerId,
                            $customerOrderDetails,
                            $numberOfDays,
                            $customerEmail
                        );

                        $customerPos->status = 'Invoice';
                        break;
                }

                $customerPos->save();

                # update stocks
                (new Stock())->stockOutMany($customerOrderDetails);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }


   /**
    * Undocumented function
    *
    * @param int $customerId
    * @param \App\Models\Pos $customerPos
    * @param float $cash
    * @param string|null $customerEmail
    * @param string|null $customerName
    * @return void
    */
    private function payWithCash(
        int $customerId,
        Pos $customerPos,
        float $cash,
        bool $shouldMail,
        string $customerEmail = null,
        string $customerName = null): void
    {
        $subTotal = $customerPos->posDetails
                                ->map
                                ->pivot
                                ->map
                                ->sub_total
                                ->sum();

        $discount = $customerPos->posDetails
                                ->map
                                ->pivot
                                ->map
                                ->discount
                                ->sum();

        $noOfItemsBought = $customerPos->posDetails
                                        ->map
                                        ->pivot
                                        ->map
                                        ->quantity
                                        ->sum();

        $tax = ($subTotal * 0.12);
        $total = (($subTotal - $discount) + $tax);
        $change = ($cash - $total);

        if ($total > $cash)
        {
            throw new \Exception("Cash is not enough, remaining fees: P" . abs($change));
        }

        $paymentDetails = [
            'cashier' => auth()->user()->name,
            'no_of_items_bought' => $noOfItemsBought,
            'sub_total' => $subTotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
            'cash' => $cash,
            'change' => $change
        ];

        # create new `payments`
        $result = $customerPos->posPayment()->create($paymentDetails);

        if (!$result)
        {
            throw new \Exception("Error Processing Payment in Cash");
        }

        if ($shouldMail)
        {
            $this->paymentMail(
                $customerId,
                $result->id,
                'cash',
                $customerId !== 1,
                $customerEmail,
                $customerName
            );
        }

    }



  /**
   * Undocumented function
   *
   * @param integer $customerId
   * @param Pos $customerPos
   * @param boolean $shouldMail
   * @param string|null $customerEmail
   * @param string|null $customerName
   * @return void
   */
    private function payWithCredit(int $customerId, Pos $customerPos, bool $shouldMail, string $customerEmail = null, string $customerName = null): void
    {
        $subTotal = $customerPos->posDetails
                                ->map
                                ->pivot
                                ->map
                                ->sub_total
                                ->sum();

        $discount = $customerPos->posDetails
                                ->map
                                ->pivot
                                ->map
                                ->discount
                                ->sum();

        $noOfItemsBought = $customerPos->posDetails
                                ->map
                                ->pivot
                                ->map
                                ->quantity
                                ->sum();

        $tax = ($subTotal * 0.12);
        $total = (($subTotal - $discount) + $tax);

        $paymentDetails = [
            'payment_method' => 'credit',
            'cashier' => auth()->user()->name,
            'no_of_items_bought' => $noOfItemsBought,
            'sub_total' => $subTotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
            'cash' => $total,
        ];

        $result = $customerPos->posPayment()->create($paymentDetails);

        if (!$result)
        {
            throw new \Exception("Error Processing Credit Payment");
        }

        if ($shouldMail)
        {
            $this->paymentMail($customerId,
                $result->id,
                'credit',
                $customerId !== 1,
                $customerEmail,
                $customerName
            );
        }
    }



    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @param [type] $customerOrderDetails
     * @param [type] $numberOfDays
     * @param string $customerEmail
     * @return void
     */
    private function payWithInvoice(int $customerId, $customerOrderDetails, $numberOfDays = null, string $customerEmail = null): void
    {
        $numberOfDays = $numberOfDays ?? 30;
        $invoiceDetails = [];

        # select customer
        $customer = Customer::find($customerId);

        foreach ($customerOrderDetails as $customerOrderDetail)
        {
            $subTotal = ($customerOrderDetail->price * $customerOrderDetail->quantity);
            $tax = ($subTotal * 0.12);
            $total = ($subTotal + $tax) - $customerOrderDetail->discount;

            $invoiceDetails[] =
            [
                'product_id' => $customerOrderDetail->product_id,
                'quantity' => $customerOrderDetail->quantity,
                'price' => $customerOrderDetail->price,
                'unit_of_measurement' => $customerOrderDetail->unit_of_measurement,
                'sub_total' => $subTotal,
                'discount' => $customerOrderDetail->discount,
                'tax' => $tax,
                'total' => $total,
                'created_at' => now()
            ];
        }

        # create invoice
        (new Invoice())->generateSalesInvoice(
            $customer,
            $invoiceDetails,
            $numberOfDays,
            $customerEmail
        );
    }


}
