<?php


namespace Asivas\Analytics\Http\Controllers\Dashboard\Formaters;


use Illuminate\Support\Carbon;

class DateAxisFormatter implements WidgetFormatter
{
    /**
     * @param Carbon $dFrom
     * @param Carbon $dTo
     * @param array $mainSerie
     * @param array $compareSerie
     * @return array[]
     */
    static function formatResponse(Carbon $dFrom, Carbon $dTo, array $allSeries, array $compareSeries= null): array
    {
        $categories= [];
        $computedSeries = [];

        for ($d = $dFrom; $d <= $dTo; $d->addDay()) {
            $categories[] = $d->format('Y-m-d');
        }

        foreach ($allSeries as $serie) {
            $computedSerie = [];
            $computedSerie['name'] = $serie['name'];
            $data = [];
            foreach($categories as $category) {
                $data[] = $serie['data'][$category] ?? null;
            }
            $computedSerie['data'] = $data;
            $computedSeries[] = $computedSerie;
        }
        return
            [
                "categories" => $categories,
                "series" => $computedSeries
            ];
    }
}
