<?php


namespace Asivas\Analytics\Http\Controllers\Dashboard\Formaters;


use Illuminate\Support\Carbon;

class TwoAxisSimpleFormatter extends WidgetFormatter
{

    function formatResponse(Carbon $dFrom, Carbon $dTo, array $allSeries, array $compareSeries = null): array
    {
        $categories= [];
        $computedSeries = [];
        foreach ($allSeries as $serie) {
            $computedSerie = [];
            $computedSerie['name'] = $serie['name'];
            $categories = array_keys($serie['data']);
            $computedSerie['data'] = array_values($serie['data']);
            $computedSeries[] = $computedSerie;
        }
        return
            [
                "categories" => $categories,
                "series" => $computedSeries
            ];
    }
}
