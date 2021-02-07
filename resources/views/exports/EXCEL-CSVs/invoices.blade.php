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
                <th align="center"><strong>Invoice Id</strong></th>
                <th colspan="2" align="center"><strong>Invoice Date</strong></th>
                <th colspan="2" align="center"><strong>Customer</strong></th>
                <th colspan="2" align="center"><strong>No# of items</strong></th>
                <th colspan="2" align="center"><strong>Subtotal</strong></th>
                <th colspan="2" align="center"><strong>Tax</strong></th>
                <th colspan="2" align="center"><strong>Total</strong></th>
                <th colspan="2" align="center"><strong>Payment Date</strong></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoices as $invoice)
                <tr>
                    <td align="center">{{ $invoice->invoice_id }}</td>

                    <td colspan="2" align="center">{{ $invoice->invoice_date }}</td>
                    <td colspan="2" align="center">{{ $invoice->customer_name }}</td>
                    <td colspan="2" align="center">{{ $invoice->number_of_items }}</td>
                    <td colspan="2" align="center">{{ number_format($invoice->sub_total, 2) }}</td>
                    <td colspan="2" align="center">{{ number_format($invoice->tax, 2) }}</td>
                    <td colspan="2" align="center">{{ number_format($invoice->total, 2) }}</td>
                    <td colspan="2" align="center">{{ $invoice->payment_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
