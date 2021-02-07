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
        .invoice-details td, .invoice-id td {
            padding: 1px 0;
        }

        .invoice-id, .billing-details {
            width: 50%;
        }
        .invoice-id th, .billing-details th {
            padding: 1px 5px 1px 0;
            text-align: left;
        }
        .invoice-id td, .billing-details td
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

        .invoice-id {
            margin: 5px 0 50px;
        }



        .billing-details {
            margin: 5px 0 60px;
        }

        .invoice-pre-total
        {
            width: 20%;
            margin-left: auto;
        }

        header {
            font-size: 30px;
        }

        .invoice-total {
            text-align: right;
        }
        .invoice-total .total-price {
            font-size: 30px;
            padding: 4px;
            color: #FFF;
            background-color: #2c2c2c;
        }

        .terms {
            width: 70%;
        }
        .terms .terms-message {
            padding: 3px 4px;
            color: #575656;
            background-color: rgb(145, 209, 218);

        }

    </style>
</head>
<body>
    <header>
        <h1>Invoice</h1>
    </header>
    <table class="invoice-id">
        <thead>
            <tr>
                <th><strong>Invoice Number</strong></th>
                <th><strong>Date of Issue</strong></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ sprintf("%05d", $invoice->id) }}</td>
                <td>{{ $invoice->created_at->toDateString() }}</td>
            </tr>
        </tbody>
    </table>

    <table class="billing-details">
        <thead>
            <tr>
                <th><strong>Billed to</strong></th>
                <th colspan="2"><strong>{{ Config::get('app.name', 'default') }}</strong></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $customer->name }}</td>
                <td>{{ Config::get('app.company_address', 'default') }}</td>
            </tr>
            <tr>
                <td>{{ $customer->address }}</td>
                <td>{{ Config::get('app.company_contact', 'default') }}</td>
            </tr>
            <tr>
                <td>{{ $customer->city . ', ' . $customer->province . ', ' . $customer->country }}</td>
                <td>{{ Config::get('app.company_email', 'default') }}</td>
            </tr>
            <tr>
                <td>{{ $customer->postal_code }}</td>
                <td>{{ Config::get('app.company_website', 'default') }}</td>
            </tr>
        </tbody>
    </table>


    <table class="invoice-details">
        <thead>
            <tr>
                <th><strong>Product Description</strong></th>
                <th><strong>Quantity</strong></th>
                <th><strong>Tax</strong></th>
                <th><strong>Sub total</strong></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoiceDetails as $invoiceDetail)
                <tr>
                    <td>{{ $invoiceDetail->product_description }}</td>
                    <td>{{ $invoiceDetail->quantity }}</td>
                    <td>{{ 'P'. $invoiceDetail->tax }}</td>
                    <td>{{ 'P'. $invoiceDetail->sub_total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <table class="invoice-pre-total">
        <tbody>
            <tr>
                <td>
                    <strong>Subtotal</strong>
                </td>
                <td align="right">{{ 'P' . $invoiceSalesTax['subTotal'] }}</td>
            </tr>
            <tr>
                <td>
                    <strong>Discount</strong>
                </td>
                <td align="right">{{ 'P' . $invoiceSalesTax['discount'] }}</td>
            </tr>
            <tr>
                <td>
                    <strong>(Tax Rate)</strong>
                </td>
                <td align="right">{{ $invoiceSalesTax['taxRate'] }}</td>
            </tr>
            <tr>
                <td>
                    <strong>Tax</strong>
                </td>
                <td align="right">{{ 'P' . $invoiceSalesTax['tax'] }}</td>
            </tr>
        </tbody>
    </table>
    <hr>
    <div class="invoice-total">
        <p><strong>Invoice total</strong></p>
        <h3 class="total-price">{{ '$' . $invoiceSalesTax['total'] }}</h3>
    </div>

    <div class="terms">
        <small><strong>Terms</strong></small>
        <p class="terms-message">Please pay invoice by {{ $invoice->payment_date }}</p>
    </div>
</body>
</html>
