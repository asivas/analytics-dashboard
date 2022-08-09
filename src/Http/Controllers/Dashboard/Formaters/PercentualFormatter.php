<?php


namespace Asivas\Analytics\Http\Controllers\Dashboard\Formaters;


use Illuminate\Support\Carbon;

class PercentualFormatter extends WidgetFormatter
{
    public function formatResponse(Carbon $dFrom, Carbon $dTo, array $series, array $compareSeries = null): array
    {
        $mainSerie = current($series);
        return
            [
                'labels' => array_keys($mainSerie),
                'series' => array_values($mainSerie)
            ];
    }
}
