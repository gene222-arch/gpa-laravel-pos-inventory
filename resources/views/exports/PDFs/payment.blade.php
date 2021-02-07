<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        * {
            font-family: monospace !important;
            line-height: 1.6;
            font-size: 12px;
        }

        .payment-details {
            width: 100%;
            margin-top: 30px;
            text-align: center;
        }
        .payment-details td, .payment-id td {
            padding: 1px 0;
        }

        .payment-id, .billing-details {
            width: 50%;
        }
        .payment-id th, .billing-details th {
            padding: 1px 5px 1px 0;
            text-align: left;
        }
        .payment-id td, .billing-details td
        {
            padding-left: 5px;
            background-color: #dddddd;
            text-align: left;
            width: 30%;
        }

        .payment-details th {
            padding: 4px 0 4px 0;
        }
        td {
            padding: 5px;
        }

        .payment-id {
            margin: 5px 0 50px;
        }

        .billing-details {
            margin: 5px 0 60px;
        }

        .payment-pre-total
        {
            width: 20%;
            margin-left: auto;
            font-size: 12px;
        }

        header {
            font-size: 30px;
        }


        .terms {
            width: 70%;
        }
        .terms .terms-message {
            padding: 3px 4px;
            color: #575656;
            background-color: rgb(145, 209, 218);
        }

        .receipt-details-table {
            width: 20%;
            margin-left: auto;
        }

        .receipt-header
        {
            text-align: center;
            width: 100%;
            font-size: 12px;
        }

        .shop-name
        {
            font-size: 16px !important;
            text-align: center;
        }

        .dotted {
            border: 1px dotted #2c2c2c;
            border-style: none none dotted;
            color: #fff; background-color: #fff;
        }
        .payment-details {
            border-collapse: collapse;
        }

        .payment-title
        {
            border-bottom: 1px dotted #2c2c2c;
            padding-bottom: 20px;
        }

        .payment {
            font-weight: 700;
        }
    </style>
</head>
<body>
    <table class="receipt-header">
        <tbody>
            <tr>
                <td class="shop-name">
                    Shop Name
                </td>
            </tr>
            <tr>
                <td>
                    {{ Config::get('app.company_address', 'default') }}
                </td>
            </tr>
            <tr>
                <td>
                    {{ Config::get('app.company_contact', 'default') }}
                </td>
            </tr>
        </tbody>
    </table>


    <table class="payment-details">
        <thead>
            <tr>
                <th class="payment-title">Description</th>
                <th class="payment-title">Qty</th>
                <th class="payment-title">Price</th>
                <th class="payment-title">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($paymentDetails as $paymentDetail)
                <tr>
                    <td>{{ $paymentDetail->product_description }}</td>
                    <td>{{ $paymentDetail->quantity }}</td>
                    <td>{{ 'P'. $paymentDetail->unit_price }}</td>
                    <td>{{ 'P'. $paymentDetail->sub_total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr class='dotted' style="margin-top: 20px">

    <table class="payment-pre-total">
        <tbody>
            <tr>
                <td>Subtotal</td>
                <td align="right">{{ 'P' . $payment->sub_total }}</td>
            </tr>
            <tr>
                <td>Discount</td>
                <td align="right">{{ 'P' . $payment->discount }}</td>
            </tr>
            <tr>
                <td>(Tax Rate)</td>
                <td align="right">{{ $taxRate }}</td>
            </tr>
            <tr>
                <td>Tax</td>
                <td align="right">{{ 'P' . $payment->tax }}</td>
            </tr>
        </tbody>
    </table>
    <hr class='dotted'>
    <table class="receipt-details-table">
        <tbody>
            <tr>
                <td>
                    Total
                </td>
                <td align="right" class="payment">{{ 'P' . $payment->total }}</td>
            </tr>
            <tr>
                <td>
                    Cash
                </td>
                <td align="right" class="payment">{{ 'P'. $payment->cash }}</td>
            </tr>
            <tr>
                <td>
                    Balance
                </td>
                <td align="right" class="payment">{{ $payment->change }}</td>
            </tr>
        </tbody>
    </table>
    <hr class="dotted" style="margin-top: 25px;">
        <p style="text-align: center">Thank you, come again.</p>
    <hr class="dotted">
</body>
</html>
