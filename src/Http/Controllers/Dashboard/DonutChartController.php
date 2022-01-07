<?php


namespace Asivas\Analytics\Http\Controllers\Dashboard;


use Illuminate\Support\Carbon;

class DonutChartController extends DashboardWidgetController
{
    /**
     * @param $data
     * @param $from
     * @param $to
     * @param $groupBy
     * @return array
     */
    public function buildResponse($data,$from,$to,$groupBy):array
    {
        $mainSerie=[];
        foreach ($data as $elem) {
            $mainSerie[$elem[$this->dataMap->getLabel()]] = $elem[$this->dataMap->getSerie()];
        }
        $formater = $this->getFormatterClass();
        return $formater::formatResponse($from, $to, [$mainSerie]);
    }
}
