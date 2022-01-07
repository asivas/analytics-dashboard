<?php


namespace Asivas\Analytics\Http\Controllers\Dashboard;

use Asivas\ABM\Form\FormField;
use Asivas\Analytics\Analytics;
use Asivas\Analytics\AnalyticsFacade;
use Asivas\Analytics\Http\Controllers\Dashboard\Formaters\PercentualFormatter;
use Asivas\Analytics\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardWidgetController
{
    /** @var Widget */
    protected $dataMap;
    protected static $labelsSeriesMaps;


    public function getWidgetData(Request $request, $analyticName)
    {
        $params = $request->all();
        $from = $params['startDate'];
        $to = $params['endDate'];
        $data = $this->getData($analyticName, $from, $to);

        $dFrom = Carbon::create($from);
        $dTo = Carbon::create($to);

        $response = $this->buildResponse($data, $dFrom, $dTo, $params);

        return $response;
    }

    /**
     * @return string It should return the formatter class needed for the type of graphs and the analitic
     */
    public function getFormatterClass()
    {
        $formatterClass = $this->dataMap->getFormatter();
        if (!isset($formatterClass))
            $formatterClass = PercentualFormatter::class;
        return $formatterClass;
    }

    public function getData($analyticsName, $from, $to)
    {
        /** @var Widget $analytic */
        foreach ($this->getWidgets() as $analytic)
        {
            if($analytic->getUrl() === $analyticsName)
                $this->dataMap = $analytic;
        }

        return AnalyticsFacade::$analyticsName($from, $to);
    }

    /**
     * @param $data
     * @param $from
     * @param $to
     * @param $groupBy
     * @return array
     */
    public function buildResponse($data, $from, $to, $groupBy): array
    {
        $allSeries = [];
        $series = [];
        $dataMapSeries = $this->dataMap->getSeries();
        if (isset($dataMapSeries)) {
            foreach ($data as $elem) {
                $series[] = $elem[$dataMapSeries];
            }
            $series = array_unique($series);
            foreach ($series as $serie) {
                $auxSerie['name'] = $serie;
                $dataSerie = [];
                foreach ($data as $elem) {
                    if (($serie == $elem[$dataMapSeries])) {
                        $dataSerie[$elem[$this->dataMap->getLabel()]] = $elem[$this->dataMap->getSerie()];
                    }
                }
                $auxSerie['data'] = $dataSerie;
                $allSeries[] = $auxSerie;
            }
        } else {
            $uniqueDataSerie = [];
            foreach ($data as $elem) {
                $uniqueDataSerie[$elem[$this->dataMap->getLabel()]] = $elem[$this->dataMap->getSerie()];
                $auxSerie['name'] = $this->dataMap->getSerie();
                $auxSerie['data'] = $uniqueDataSerie;
                $allSeries = [$auxSerie];
            }
        }
        $formater = $this->getFormatterClass();
        return $formater::formatResponse($from, $to, $allSeries);
    }

    /**
     * The implmementing class should resolve the userTypeName according to the project's
     * user type definition
     * @return mixed
     */
    protected function getUserTypeName() {
        return 'User';
    }

    /**
     * This method should be overridden by the implementing/final class
     * @return array
     */
    public function getUserWidgets() {
        return [];
    }

    public function getAnalytics()
    {
        $widgets = $this->getWidgets();
        $analytics = [];
        /** @var Widget $widget */
        foreach ($widgets as $widget) {
            $response = $this->getWidgetData(\request(),$widget->getUrl());
            $widget->setData($response);
            $analytics[$widget->getUrl()] = $widget->toArray();
        }
        return $analytics;
    }

    /**
     * @return mixed
     */
    public static function getWidgets()
    {
        $instance = new static();
        if(empty(self::$labelsSeriesMaps)) {
            $userType = $instance->getUserTypeName();
            $getWidgetsFn = 'getUserWidgets';
            $getUserTypeWidgetsFn = "get{$userType}Widgets";
            if (method_exists($instance, $getUserTypeWidgetsFn)) {
                $getWidgetsFn = $getUserTypeWidgetsFn;
            }
            self::$labelsSeriesMaps = $instance->$getWidgetsFn();
        }

        return self::$labelsSeriesMaps;
    }
}
