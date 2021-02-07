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
        <h1>Low Stock Items</h1>
    </header>

    <table class="invoice-details">
        <thead>
            <tr>
                <th><strong>Product Description</strong></th>
                <th><strong>Minimum Quantity</strong></th>
                <th><strong>Remaining Quantity</strong></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->stock->minimum_reorder_level }}</td>
                    <td>{{ $product->stock->in_stock }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
</body>
</html>
