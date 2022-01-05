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
            $aux[$this->dataMap['label']] = $elem[$this->dataMap['label']];
            $aux[$this->dataMap['serie']] = $elem[$this->dataMap['serie']];
            array_push($serie,$aux);
        }
        $formater = $this->getFormatterClass();
        return $formater::formatResponse($from, $to, $serie);
    }
}
