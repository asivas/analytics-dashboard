<?php


namespace Asivas\Analytics\Http\Controllers\Dashboard\Formaters;


use Illuminate\Support\Carbon;

interface WidgetFormatter
{
    static function formatResponse(Carbon $dFrom, Carbon $dTo, array $series, array $compareSeries= null): array;
}
