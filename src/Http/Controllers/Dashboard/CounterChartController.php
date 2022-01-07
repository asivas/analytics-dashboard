<?php
namespace Asivas\Analytics\Http\Controllers\Dashboard;

use Illuminate\Support\Carbon;

class CounterChartController extends DashboardWidgetController
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
        if (count($data)>0){
            foreach ($data as $elem) {
                $mainSerie[$elem[$this->dataMap->getLabel()]] = $elem[$this->dataMap->getSerie()];
            }
        } else {
            $mainSerie[$this->dataMap->getTitle()] = 0;
        }
        $formater = $this->getFormatterClass();
        return $formater::formatResponse($from, $to, [$mainSerie]);
    }
}
