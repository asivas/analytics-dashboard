<?php


namespace Asivas\Analytics\Http\Controllers\Dashboard;


use Illuminate\Support\Carbon;

class TopXchartController extends DashboardWidgetController
{
    public function buildResponse($data, $from, $to, $groupBy): array
    {
        $serie = [];
        foreach ($data as $elem) {
            $aux = [];
            $aux[$this->dataMap->getLabel()] = $elem[$this->dataMap->getLabel()];
            $aux[$this->dataMap->getSerie()] = $elem[$this->dataMap->getSerie()];
            array_push($serie,$aux);
        }
        $formater = $this->getFormatterClass();
        return $formater::formatResponse($from, $to, $serie);
    }
}
