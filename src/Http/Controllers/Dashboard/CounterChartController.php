<?php
namespace Asivas\Analytics\Http\Controllers\Dashboard;

use Asivas\Analytics\Widget;
use Illuminate\Support\Carbon;

class CounterChartController extends DashboardWidgetController
{
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
        $formater = $this->getFormatterClass($widget);
        return $formater::formatResponse($from, $to, [$mainSerie]);
    }
}
