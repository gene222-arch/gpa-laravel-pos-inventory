<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        * {
            font-family: Helvetica;
            text-align: center;
        }
    </style>
</head>
<body>

    <table>
        <thead>
            <tr align="center">
                <th align="center"><strong>Purchase order #</strong></th>
                <th colspan="2" align="center"><strong>Product</strong></th>
                <th colspan="2" align="center"><strong>Received</strong></th>
                <th colspan="2" align="center"><strong>Ordered</strong></th>
                <th colspan="2" align="center"><strong>Remaining order</strong></th>
                <th colspan="2" align="center"><strong>Purchase cost</strong></th>
                <th colspan="2" align="center"><strong>Amount</strong></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchaseOrders as $purchaseOrder)
                <tr>
                    <td align="center">{{ $purchaseOrder->id }}</td>
                    <td colspan="2" align="center">{{ $purchaseOrder->product_description }}</td>
                    <td colspan="2" align="center">{{ $purchaseOrder->received_quantity }}</td>
                    <td colspan="2" align="center">{{ $purchaseOrder->ordered_quantity }}</td>
                    <td colspan="2" align="center">{{ $purchaseOrder->remaining_ordered_quantity }}</td>
                    <td colspan="2" align="center">{{ $purchaseOrder->purchase_cost }}</td>
                    <td colspan="2" align="center">{{ $purchaseOrder->amount }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
