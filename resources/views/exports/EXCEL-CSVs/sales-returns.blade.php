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
                <th colspan="2" align="center"><strong>Customer </strong></th>
                <th colspan="2" align="center"><strong>Purchased at</strong></th>
                <th colspan="2" align="center"><strong>Sales Return</strong></th>
                <th colspan="2" align="center"><strong>No# of items</strong></th>
                <th colspan="2" align="center"><strong>Returned at</strong></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salesReturns as $salesReturn)
                <tr>
                    <td align="center">{{ $salesReturn->id }}</td>
                    <td colspan="2" align="center">{{ $salesReturn->customer }}</td>
                    <td colspan="2" align="center">{{ $salesReturn->purchased_at }}</td>
                    <td colspan="2" align="center">{{ number_format($salesReturn->sales_return, 2) }}</td>
                    <td colspan="2" align="center">{{ $salesReturn->no_of_items }}</td>
                    <td colspan="2" align="center">{{ $salesReturn->returned_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
