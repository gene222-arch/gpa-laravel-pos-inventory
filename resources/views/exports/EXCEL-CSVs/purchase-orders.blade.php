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
                <th colspan="2" align="center"><strong>Date</strong></th>
                <th colspan="2" align="center"><strong>Status</strong></th>
                <th colspan="2" align="center"><strong>Supplier</strong></th>
                <th colspan="2" align="center"><strong>Received</strong></th>
                <th colspan="2" align="center"><strong>Expected on</strong></th>
                <th colspan="2" align="center"><strong>Total</strong></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchaseOrders as $purchaseOrder)
                <tr>
                    <td align="center">{{ $purchaseOrder->id }}</td>

                    <td colspan="2" align="center">{{ $purchaseOrder->purchase_order_date }}</td>
                    <td colspan="2" align="center">{{ $purchaseOrder->status }}</td>
                    <td colspan="2" align="center">{{ $purchaseOrder->supplier }}</td>
                    <td colspan="2" align="center">{{ $purchaseOrder->received }}</td>
                    <td colspan="2" align="center">{{ $purchaseOrder->expected_on }}</td>
                    <td colspan="2" align="center">{{ $purchaseOrder->total_ordered_quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
