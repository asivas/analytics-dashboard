<?php


namespace Asivas\Analytics\Http\Controllers\Dashboard\Formaters;


use Asivas\Analytics\AnalyticsFacade;
use Asivas\Analytics\Widget\Widget;
use Asivas\Analytics\WidgetControllerFacade;
use Illuminate\Support\Carbon;

abstract class WidgetFormatter
{

    abstract function formatResponse(Carbon $dFrom, Carbon $dTo, array $series, array $compareSeries= null): array;

    public static function getData($analyticsName, $from, $to)
    {
        return AnalyticsFacade::$analyticsName($from, $to);
    }

    /**
     * @param Widget $widget
     * @param $from
     * @param $to
     * @param $data
     * @return array
     */
    public function prepareWidgetData(Widget $widget, $from, $to, $data=null): array
    {
        $shoulDisplayClosure = $widget->getShouldDisplayClosure();
        $response['display'] = $shoulDisplayClosure(Carbon::create($from), Carbon::create($to), $data);
        return $response;
    }

    public function getWidgetData($analyticName, $from, $to,$otherParams=[]) {

        $data = self::getData($analyticName, $from, $to);

        /** @var Widget $widget */
        $widget = WidgetControllerFacade::getWidget($analyticName);

        $response = $this->prepareWidgetData($widget, $from, $to, $data);
        if($response['display'])
            $response = $response + self::buildResponse($widget, $data, Carbon::create($from), Carbon::create($to), $otherParams);

        return $response;
    }

    /**
     * @param $serie
     * @param $data
     * @param Widget $widget
     * @param null $dataMapSeries
     * @return array
     */
    protected static function getSerieData($serie, $data, Widget $widget, $dataMapSeries = null): array
    {
        $auxSerie['name'] = $serie;
        $dataSerie = [];
        foreach ($data as $elem) {
            if (!isset($dataMapSeries) || ($serie == $elem[$dataMapSeries])) {
                $dataSerie[$elem[$widget->getLabel()]] = $elem[$widget->getSerie()];
            }
        }
        $auxSerie['data'] = $dataSerie;
        return $auxSerie;
    }

    /**
     * @param Widget $widget
     * @param $data
     * @param $from
     * @param $to
     * @param $groupBy
     * @return array
     */
    public function buildResponse(Widget $widget, $data, $from, $to, $groupBy): array
    {
        $allSeries = [];
        $series = [];
        $dataMapSeries = $widget->getSeries();
        if (isset($dataMapSeries)) {
            foreach ($data as $elem) {
                $series[] = $elem[$dataMapSeries];
            }
            $series = array_unique($series);
            foreach ($series as $serie) {
                $allSeries[] = $this->getSerieData($serie, $data, $widget, $dataMapSeries);
            }
        } else {
            $allSeries[] = $this->getSerieData($widget->getSerie(), $data, $widget);             ;
        }
        return $this->formatResponse($from, $to, $allSeries);
    }
}
