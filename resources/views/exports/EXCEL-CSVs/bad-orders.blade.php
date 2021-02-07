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
                <th align="center"><strong>#</strong></th>
                <th colspan="2" align="center"><strong>Supplier</strong></th>
                <th colspan="2" align="center"><strong>Purchase Return</strong></th>
                <th colspan="2" align="center"><strong>No# of items</strong></th>
                <th colspan="2" align="center"><strong>Purchase Order Date</strong></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($badOrders as $badOrder)
                <tr>
                    <td align="center">{{ $badOrder->id }}</td>
                    <td colspan="2" align="center">{{ $badOrder->supplier_name }}</td>
                    <td colspan="2" align="center">{{ number_format($badOrder->purchase_return, 2) }}</td>
                    <td colspan="2" align="center">{{ $badOrder->no_of_items }}</td>
                    <td colspan="2" align="center">{{ $badOrder->purchase_order_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
