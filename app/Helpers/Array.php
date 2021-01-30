<?php


    if (! function_exists('determineIsArrayMulti'))
    {

        /**
         * Determine if the array is multidimensional
         *
         * @param array $array
         * @return boolean
         */
        function determineIsArrayMulti(array $array): bool
        {
            foreach ($array as $value) if (is_array($value)) return true;
            return false;
        }

    }


    if (! function_exists('preparePrepend'))
    {
        /**
         * Prepend new array on either 1D or Multidimensional Array
         *
         * @param array $keyValue
         * @param array $paramArray
         * @return array
         */
        function preparePrepend(array $array, array $paramArray): array
        {

            if (!determineIsArrayMulti($paramArray))
            {
                return array_merge($array, $paramArray);
            }

            return array_map(fn($curArr) => array_merge($curArr, $array), $paramArray);
        }
    }


    if (! function_exists('preparePrependIgnore'))
    {
        /**
         * Prepend new array on either 1D or Multidimensional Array
         * Ignores appending if same key exists
         *
         * @param array $keyValue
         * @param array $paramArray
         * @param  string $unique
         * @return array
         */
        function preparePrependIgnore(array $keyValue, array $paramArray): array
        {
            // 1D
            if (!determineIsArrayMulti($paramArray))
            {
                if (array_key_exists(array_key_first($keyValue), $paramArray))
                {
                    return $paramArray;
                }
                    return array_merge($keyValue, $paramArray);
            }

            // 2D
            return array_map(function($curArr) use ($keyValue)
            {
                if (array_key_exists(array_key_first($keyValue), $curArr))
                {
                    return $curArr;
                }

                return array_merge($curArr, $keyValue);

            }, $paramArray);
        }
    }



    if ( !function_exists('prepareMultiArraySum'))
    {
        function prepareMultiArraySum(string $key, array $arrays)
        {
            if (! determineIsArrayMulti($arrays))
            {
                return null;
            }

            $storage = [];

            foreach ($arrays as $array)
            {
                if (array_key_exists($key, $array))
                {
                    $storage[] = $array[$key];
                }
            }

            return array_sum($storage);
        }
    }



