<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        * {
            font-family: Helvetica;
            line-height: 1.6;
        }
        .invoice-details {
            width: 100%;
            margin-top: 30px;
            text-align: center;
        }
        .invoice-details td, .purchase-order-id td {
            padding: 1px 0;
        }

        .purchase-order-id, .billing-details {
            width: 50%;
        }
        .purchase-order-id th, .billing-details th {
            padding: 1px 5px 1px 0;
            text-align: left;
        }
        .purchase-order-id td, .billing-details td
        {
            padding-left: 5px;
            background-color: #dddddd;
            text-align: left;
            width: 30%;
        }

        .invoice-details th {
            padding: 12px 0 12px 0;
        }
        td {
            padding: 5px;
        }

        .purchase-order-id {
            margin: 5px 0 50px;
        }

        .billing-details {
            margin: 5px 0 60px;
        }

        header {
            font-size: 25px;
        }

        .purchase-order-total {
            text-align: right;
        }
        .purchase-order-total .total-price {
            font-size: 30px;
            padding: 4px;
            color: #FFF;
            background-color: #2c2c2c;
        }

        .invoice-details {
            border-collapse: collapse;
        }
        .invoice-details th {
            border-bottom: 2px solid #2c2c2c;
        }

    </style>
</head>
<body>
    <header>
        <h1>Purchase Order {{ sprintf("%05d", $purchaseOrder->id) }}</h1>
    </header>
    <table class="purchase-order-id">
        <tbody>
            <tr>
                <td style="background-color: #FFF"><strong>Purchase Order Date</strong></td>
                <td>{{ date('M d Y ', strtotime($purchaseOrder->purchase_order_date)) }}</td>
            </tr>
            <tr>
                <td style="background-color: #FFF"><strong>Expected On</strong></td>
                <td>{{ date('M d Y ', strtotime($purchaseOrder->expected_delivery_date)) }}</td>
            </tr>
            <tr>
                <td style="background-color: #FFF"><strong>Ordered By</strong></td>
                <td>{{ $purchaseOrder->ordered_by }}</td>
            </tr>
        </tbody>
    </table>


    <table class="invoice-details">
        <thead>
            <tr>
                <th><strong>Product Description</strong></th>
                <th><strong>Quantity</strong></th>
                <th><strong>Price</strong></th>
                <th><strong>Amount</strong></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchaseOrderDetails as $purchaseOrderDetail)
                <tr>
                    <td>{{ $purchaseOrderDetail->name }}</td>
                    <td>{{ $purchaseOrderDetail->remaining_ordered_quantity }}</td>
                    <td>{{ 'P'. $purchaseOrderDetail->purchase_cost }}</td>
                    <td>{{ 'P'. $purchaseOrderDetail->amount }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>
    <div class="purchase-order-total">
        <p><strong>Total</strong></p>
        <h3 class="total-price">{{ '$' . $total }}</h3>
    </div>

</body>
</html>
