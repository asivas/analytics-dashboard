<?php

namespace Asivas\Analytics\Http\Controllers\Dashboard\Formaters;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

/**
 * Formats the response for categorized series (ej: stacked bar chart)
 */
class TwoAxisSeriesFormatter extends TwoAxisSimpleFormatter
{
    function formatResponse(Carbon $dFrom, Carbon $dTo, array $allSeries, array $compareSeries = null): array
    {
        $computedSeries = [];
        $allSeriesByName=Arr::mapWithKeys($allSeries,function($i){ return [$i['name']=>$i['data']]; });
        $categories = array_keys(Arr::collapse($allSeriesByName));
        sort($categories);

        foreach ($allSeriesByName as $name=>$serie) {
            $serieData = [];
            foreach ($categories as $category) {
                $serieData[]=$serie[$category]??0;
            }
            $computedSeries[] = ['name'=>$name,'data'=>$serieData];
        }
        return
            [
                "categories" => $categories,
                "series" => $computedSeries
            ];
    }

}
