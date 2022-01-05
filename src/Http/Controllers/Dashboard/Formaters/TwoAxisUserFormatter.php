<?php


namespace Asivas\Analytics\Http\Controllers\Dashboard\Formaters;


use Illuminate\Support\Carbon;

class TwoAxisUserFormatter implements WidgetFormatter
{

    static function formatResponse(Carbon $dFrom, Carbon $dTo, array $allSeries, array $compareSeries = null): array
    {
        $computedSeries = [];
        $users = [];
        foreach ($allSeries as $serie) {
            foreach ($serie['data'] as $user => $value){
                $users[$user] = $user;
            }
        }
        $categories = array_keys($users);
        foreach ($allSeries as $serie) {
            $computedSerie = [];
            $computedSerie['name'] = $serie['name'];
            foreach ($categories as $category){
                $computedSerie['data'][] = $serie['data'][$category] ?? 0;
            }
            $computedSeries[] = $computedSerie;
        }
        return
            [
                "categories" => $categories,
                "series" => $computedSeries
            ];
    }
}
