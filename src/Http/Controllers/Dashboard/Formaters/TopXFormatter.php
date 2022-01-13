<?php


namespace Asivas\Analytics\Http\Controllers\Dashboard\Formaters;


use Illuminate\Support\Carbon;

class TopXFormatter implements WidgetFormatter
{

    static function formatResponse(Carbon $dFrom, Carbon $dTo, array $allSeries, array $compareSeries = null): array
    {
        return ['items'=>$allSeries];
    }
}
