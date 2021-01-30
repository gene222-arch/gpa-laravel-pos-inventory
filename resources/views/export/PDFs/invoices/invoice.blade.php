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
        }

        .contact-address, .billing-address {
            margin-bottom: 15px;
        }
        .contact-address td
        {
            padding: 2px 30px 2px 0;
        }
        .billing-address > div{
            padding: 2px 30px 2px 0;
        }
        .invoice-details {
            width: 100%;
            margin-top: 15px;
        }

        .invoice-order-details  {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        .invoice-order-details tbody td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        .invoice-order-details tfoot {
            border: none;
        }

        .invoice-order-details tbody tr {
            background-color: #f3f3f3;
        }

        .invoice-order-details tfoot {
            background-color: #FFF;
        }
    </style>
</head>
<body>
    <div>
        <h3>Company name</h3>
    </div>
    <div class="app">
        <div class="address-container">
            <table class="contact-address" colspan='20'>
                <tbody>
                    <tr>
                        <td>
                            123 Your Street
                        </td>
                        <td>
                            564-555-1234
                        </td>
                    </tr>
                    <tr>
                        <td>
                            City, State, Country
                        </td>
                        <td>
                            your@email.com
                        </td>
                    </tr>
                    <tr>
                        <td>
                            ZIP Code
                        </td>
                        <td>
                            yourwebsite.com
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <h3>Billed to:</h3>
        </div>
        <div class="billing-address">
            <div>Client Name</div>
            <div>Street address</div>
            <div>City, State Country</div>
            <div>ZIP Code</div>
        </div>
        <hr>
        <div>
            <table class="invoice-details">
                <tbody>
                    <tr>
                        <td class="invoice-div">
                            <div>
                                <h1>Invoice</h1>
                                <div class="invoice-number">
                                    <p>Invoice Number</p>
                                    <p>00001</p>
                                </div>

                                <div class="date-of-issue">
                                    <p>Date of Issue</p>
                                    <p>mm/dd/yy</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <table class="invoice-order-details">
                                    <thead>
                                        <tr>
                                            <th class="item-description">Description</th>
                                            <th>Unit Cost</th>
                                            <th>Quantity</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="item-description">Your item name</td>
                                            <td>$0</td>
                                            <td>1</td>
                                            <td>$0</td>
                                        </tr>
                                        <tr>
                                            <td class="item-description">Your item name</td>
                                            <td>$0</td>
                                            <td>1</td>
                                            <td>$0</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td>SUBTOTAL</td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td>DISCOUNT</td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td>TAX</td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td>SHIPPING FEE</td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>

        </div>
    </div>
</body>
</html>
