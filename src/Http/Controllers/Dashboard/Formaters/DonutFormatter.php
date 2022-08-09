<?php

namespace Asivas\Analytics\Http\Controllers\Dashboard\Formaters;

use Asivas\Analytics\Widget\Widget;
use Illuminate\Support\Carbon;

class DonutFormatter extends PercentualFormatter
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
        foreach ($data as $elem) {
            $mainSerie[$elem[$widget->getLabel()]] = $elem[$widget->getSerie()];
        }
        return $this->formatResponse($from, $to, [$mainSerie]);
    }
}