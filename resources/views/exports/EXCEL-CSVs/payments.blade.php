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
                <th colspan="2" align="center"><strong>Cashier</strong></th>
                <th colspan="2" align="center"><strong>Payment Method</strong></th>
                <th colspan="2" align="center"><strong>Total</strong></th>
                <th colspan="2" align="center"><strong>Cash</strong></th>
                <th colspan="2" align="center"><strong>Change</strong></th>
                <th colspan="2" align="center"><strong>Date Ordered</strong></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td align="center">{{ $payment->pos_payment_id }}</td>
                    <td colspan="2" align="center">{{ $payment->customer_name }}</td>
                    <td colspan="2" align="center">{{ $payment->cashier }}</td>
                    <td colspan="2" align="center">{{ $payment->payment_method }}</td>
                    <td colspan="2" align="center">{{ number_format($payment->total, 2) }}</td>
                    <td colspan="2" align="center">{{ number_format($payment->cash, 2) }}</td>
                    <td colspan="2" align="center">{{ number_format($payment->change, 2) }}</td>
                    <td colspan="2" align="center">{{ $payment->date_ordered }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
