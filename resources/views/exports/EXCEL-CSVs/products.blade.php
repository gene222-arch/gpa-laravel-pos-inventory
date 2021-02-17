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
                <th align="center"><strong>id</strong></th>
                <th align="center"><strong>Sku</strong></th>
                <th align="center"><strong>Barcode</strong></th>
                <th align="center"><strong>Product description</strong></th>
                <th align="center"><strong>Category</strong></th>
                <th align="center"><strong>Sold by</strong></th>
                <th align="center"><strong>Price</strong></th>
                <th align="center"><strong>Cost</strong></th>
                <th align="center"><strong>Supplier id</strong></th>
                <th align="center"><strong>In stock</strong></th>
                <th align="center"><strong>Bad order stock</strong></th>
                <th align="center"><strong>Stock in</strong></th>
                <th align="center"><strong>Stock out</strong></th>
                <th align="center"><strong>Minimum reorder level</strong></th>
                <th align="center"><strong>Incoming</strong></th>
                <th align="center"><strong>Default purchase costs</strong></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td align="center">{{ $product->id }}</td>
                    <td align="center">{{ $product->sku }}</td>
                    <td align="center">{{ $product->barcode }}</td>
                    <td align="center">{{ $product->name }}</td>
                    <td align="center">{{ $product->category }}</td>
                    <td align="center">{{ $product->sold_by }}</td>
                    <td align="center">{{ number_format($product->price, 2) }}</td>
                    <td align="center">{{ number_format($product->cost, 2) }}</td>
                    <td align="center">{{ $product->supplier_id }}</td>
                    <td align="center">{{ $product->in_stock }}</td>
                    <td align="center">{{ $product->bad_order_stock }}</td>
                    <td align="center">{{ $product->stock_in }}</td>
                    <td align="center">{{ $product->stock_out }}</td>
                    <td align="center">{{ $product->minimum_reorder_level }}</td>
                    <td align="center">{{ $product->incoming }}</td>
                    <td align="center">{{ $product->default_purchase_costs }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
