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
                <th colspan="2" align="center"><strong>Customer</strong></th>
                <th colspan="2" align="center"><strong>First visit</strong></th>
                <th colspan="2" align="center"><strong>Last visit</strong></th>
                <th colspan="2" align="center"><strong>Total visits</strong></th>
                <th colspan="2" align="center"><strong>Total spent</strong></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td align="center">{{ $customer->id }}</td>
                    <td colspan="2" align="center">{{ $customer->customer }}</td>
                    <td colspan="2" align="center">{{ $customer->first_visit }}</td>
                    <td colspan="2" align="center">{{ $customer->last_visit }}</td>
                    <td colspan="2" align="center">{{ $customer->total_visits }}</td>
                    <td colspan="2" align="center">{{ $customer->total_spent }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
