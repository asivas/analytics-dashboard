<?php


namespace Asivas\Analytics\Http\Controllers\Dashboard;


use Asivas\Analytics\Widget;
use Illuminate\Support\Carbon;

class TopXchartController extends DashboardWidgetController
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
        $formater = $this->getFormatterClass($widget);
        return $formater::formatResponse($from, $to, $serie);
    }
}
