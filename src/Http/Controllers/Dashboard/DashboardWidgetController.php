<?php
namespace Asivas\Analytics\Http\Controllers\Dashboard;

use Asivas\Analytics\AnalyticsFacade;
use Asivas\Analytics\Http\Controllers\Dashboard\Formaters\PercentualFormatter;
use Asivas\Analytics\PanelWidget;
use Asivas\Analytics\Widget\Widget;
use Asivas\Analytics\WidgetControllerFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardWidgetController
{
    protected static $labelsSeriesMaps;


    public function getWidgetData(Request $request, $analyticName)
    {
        $params = $request->all();
        $from = $params['startDate'];
        $to = $params['endDate'];
        $data = $this->getData($analyticName, $from, $to);

        /** @var Widget $widget */
        $widget = WidgetControllerFacade::getWidget($analyticName);

        $shoulDisplayClosure = $widget->getShouldDisplayClosure();
        $response['display']=$shoulDisplayClosure(Carbon::create($from),Carbon::create($to),$data);
        if($response['display'])
            $response = $response + $this->buildResponse($widget, $data, Carbon::create($from), Carbon::create($to), $params);

        return $response;
    }

    /**
     * @param Widget $widget
     * @return string It should return the formatter class needed for the type of graphs and the analitic
     */
    public function getFormatterClass(Widget $widget)
    {
        $formatterClass = $widget->getFormatter();
        if (!isset($formatterClass))
            $formatterClass = PercentualFormatter::class;
        return $formatterClass;
    }

    /**
     * @param $analyticsName
     * @return Widget
     */
    public function getWidget($analyticsName)
    {
        $this->setupWidgetsPanels();
        $panels = $this->getWidgetsPanels();
        /** @var PanelWidget $panel */
        foreach ($panels as $panel) {
            $widgets = $panel->getWidgets();
            /** @var Widget $analytic */
            foreach ($widgets as $analytic) {
                if ($analytic->getUrl() === $analyticsName)
                    return $analytic;
            }
        }
        return null;
    }

    public function getData($analyticsName, $from, $to)
    {
        return AnalyticsFacade::$analyticsName($from, $to);
    }

    /**
     * @param $serie
     * @param $data
     * @param Widget $widget
     * @param null $dataMapSeries
     * @return array
     */
    protected function getSerieData($serie, $data, Widget $widget, $dataMapSeries = null): array
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
        $formater = $this->getFormatterClass($widget);
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
    public function setupUserWidgets() {
        return [];
    }

    public function getAnalytics()
    {
        $this->setupWidgetsPanels();
        $panels = $this->getWidgetsPanels();
        $analytics = [];
        /** @var PanelWidget $panel */
        foreach ($panels as $panel) {
            $widgets = $panel->getWidgets();
            $panelAnalytics = [];
            /** @var Widget $widget */
            foreach ($widgets as $widgetName => $widget) {
                if($widget->getUrl()!=null) {
                    $widgetController = $this;
                    $widgetControllerClass = $widget->getControllerClass();
                    if($widgetControllerClass!=null && class_exists($widgetControllerClass))
                    {
                        $widgetController= new $widgetControllerClass();
                    }
                    $response = $widgetController->getWidgetData(\request(),$widget->getUrl());
                    $widget->setData($response);
                }
                $panelAnalytics[$widgetName] = $widget->toArray();
            }
            $analytics[$panel->getType()] = $panelAnalytics;
        }
        return $analytics;
    }

    /**
     * @return PanelWidget[]
     */
    public static function getWidgetsPanels()
    {
        return self::$labelsSeriesMaps;
    }

    /**
     * @return void
     */
    public function setupWidgetsPanels() {
        if(empty(self::$labelsSeriesMaps)) {
            $userType = $this->getUserTypeName();
            $getWidgetsFn = 'setupUserWidgets';
            $getUserTypeWidgetsFn = "setup{$userType}Widgets";
            if (method_exists($this, $getUserTypeWidgetsFn)) {
                $getWidgetsFn = $getUserTypeWidgetsFn;
            }
            $this->$getWidgetsFn();
        }
    }


    protected static function addWidgetsPanel(PanelWidget $panel) {
        self::$labelsSeriesMaps[$panel->getType()] = $panel;
    }

    /**
     * @param $panelName
     * @param $panelTitle
     * @return PanelWidget
     */
    public function getWidgetsPanel($panelName,$panelTitle=null) {
        if(!isset($panelTitle)) $panelTitle = $panelName;
        if(!isset(self::$labelsSeriesMaps[$panelName]))
            self::addWidgetsPanel(PanelWidget::create($panelName,$panelTitle));
        return self::$labelsSeriesMaps[$panelName];
    }



}
