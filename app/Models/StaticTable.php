<?php
namespace App\Models;

trait StaticTable
{
    public static function table()
    {
        return (new static())->getTable();
    }
}
