<?php


namespace Asivas\Analytics\Http\Controllers\Dashboard\Formaters;


use Asivas\Analytics\Widget\Widget;
use Illuminate\Support\Carbon;

class TopXFormatter extends WidgetFormatter
{

    public function buildResponse(Widget $widget, $data, $from, $to, $groupBy): array
    {
        $serie = [];
        foreach ($data as $elem) {
            $aux = [];
            $aux[$widget->getLabel()] = $elem[$widget->getLabel()];
            $aux[$widget->getSerie()] = $elem[$widget->getSerie()];
            array_push($serie,$aux);
        }
        return $this->formatResponse($from, $to, $serie);
    }

    function formatResponse(Carbon $dFrom, Carbon $dTo, array $allSeries, array $compareSeries = null): array
    {
        return ['items'=>$allSeries];
    }
}
