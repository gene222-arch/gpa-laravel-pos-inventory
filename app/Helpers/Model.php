<?php

    if (! function_exists('purchase_order'))
    {
        function purchase_order()
        {
            return new \App\Models\PurchaseOrder;
        }
    }


    if (! function_exists('stock'))
    {
        function stock()
        {
            return new \App\Models\Stock;
        }
    }
