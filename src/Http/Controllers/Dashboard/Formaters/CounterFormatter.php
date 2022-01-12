<?php


namespace Asivas\Analytics\Http\Controllers\Dashboard\Formaters;


use Illuminate\Support\Carbon;

class CounterFormatter implements WidgetFormatter
{

    static function formatResponse(Carbon $dFrom, Carbon $dTo, array $series, array $compareSeries = null): array
    {
        $mainSerie = current($series);
        return
            [
                'title' => key($mainSerie),
                'counter' => current($mainSerie) ?? 0,
            ];
    }
}
