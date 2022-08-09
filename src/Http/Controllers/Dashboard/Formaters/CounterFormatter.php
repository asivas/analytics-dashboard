<?php


namespace Asivas\Analytics\Http\Controllers\Dashboard\Formaters;


use Asivas\Analytics\Widget\Widget;
use Illuminate\Support\Carbon;

class CounterFormatter extends WidgetFormatter
{

    function formatResponse(Carbon $dFrom, Carbon $dTo, array $series, array $compareSeries = null): array
    {
        $mainSerie = current($series);
        return
            [
                'title' => key($mainSerie),
                'counter' => current($mainSerie) ?? 0,
            ];
    }
    /**
     * @param Widget $widget
     * @param $data
     * @param $from
     * @param $to
     * @param $groupBy
     * @return array
     */
    public function buildResponse(Widget $widget, $data, $from, $to, $groupBy):array
    {
        $mainSerie=[];
        if (count($data)>0){
            foreach ($data as $elem) {
                $mainSerie[$elem[$widget->getLabel()]] = $elem[$widget->getSerie()];
            }
        } else {
            $mainSerie[$widget->getTitle()] = 0;
        }
        return self::formatResponse($from, $to, [$mainSerie]);
    }
}
