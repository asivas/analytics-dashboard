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
                'title' => array_key_first($mainSerie),
                'counter' => array_values($mainSerie)[0] ?? 0,
            ];
    }
}
