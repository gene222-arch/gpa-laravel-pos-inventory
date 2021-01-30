<?php

namespace App\Providers;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;


class QueryBuilderMacroProvider extends ServiceProvider
{
    /**
     * Method to override properties
     *
     * @var array
     */
    protected static $methods = [
        "insertTs",
        "insertGetIdTs",
        "updateTs",
        "deleteTs"
    ];


    /**
     * Undocumented function
     *
     * @param [type] $funcName
     * @param array $colNames
     * @return Illuminate\Database\Query\Builder
     */
    protected static function timestampValues($funcName, array $colNames)
    {
        Builder::macro($funcName, function (array $values, $withBy = false) use ($colNames)
        {
            // Add created_by or updated_by
            $user_id = $withBy ? auth()->user()->id : null;

            // insert created_at or updated_at using Carbone
            $now = \Carbon\Carbon::now();

            if (array_key_exists(0, $values) && is_array($values[0]))
            {
                foreach ($values as &$value)
                {
                    $value[$colNames[1]] = $now;

                    if ($withBy)
                    {
                        $value[$colNames[2]] = $user_id;
                    }
                }
            } else {
                $values[$colNames[1]] = $now;

                if ($withBy) {
                    $values[$colNames[2]] = $user_id;
                }
            }

            return Builder::{$colNames[0]}($values);
        });
    }

    /**
     * Insert with timestamp
     */
    protected static function insertTs()
    {
        return self::timestampValues(__FUNCTION__, ["insert", "created_at", "created_by"]);
    }

    /**
     * Insert with timestamp and return id
     */
    protected static function insertGetIdTs()
    {
        return self::timestampValues(__FUNCTION__, ["insertGetId", "created_at", "created_by"]);
    }

    /**
     * Update with timestamp
     */
    protected static function updateTs()
    {
        return self::timestampValues(__FUNCTION__, ["update", "updated_at", "updated_by"]);
    }


    /**
     * Soft delete (with timestamp)
     */
    protected static function deleteTs()
    {
        Builder::macro(__FUNCTION__, function ($withBy = false) {
            $values = [
                "deleted_at" => \Carbon\Carbon::now()
            ];
            // deleted_by
            if ($withBy) {
                $values["deleted_by"] = auth()->user()->id;
            }

            return Builder::update($values);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach (self::$methods as $method)
        {
            self::{$method}();
        }
    }
}
