<?php


namespace Asivas\Analytics\Http\Controllers\Dashboard\Formaters;


use Illuminate\Support\Carbon;

class TwoAxisRangesFormatter extends WidgetFormatter
{

    function formatResponse(Carbon $dFrom, Carbon $dTo, array $allSeries, array $compareSeries = null): array
    {
        $ranges= [];
        $computedSeries = [];
        foreach ($allSeries as $serie) {
            $computedSerie = [];
            $computedSerie['name'] = $serie['name'];
            $ranges = array_keys($serie['data']);
            $computedSerie['data'] = array_values($serie['data']);
            $computedSeries[] = $computedSerie;
        }
        return
            [
                "ranges" => $ranges,
                "series" => $computedSeries
            ];
    }
}
