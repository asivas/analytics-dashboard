<?php
namespace Asivas\Analytics\Http\Controllers\Dashboard;

use Asivas\Analytics\AnalyticsFacade;
use Asivas\Analytics\Http\Controllers\Dashboard\Formaters\PercentualFormatter;
use Asivas\Analytics\PanelWidget;
use Asivas\Analytics\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

        $widget = AnalyticsFacade::getWidget($analyticName);
        $response = $this->buildResponse($widget, $data, $dFrom, $dTo, $params);

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
                $auxSerie['name'] = $serie;
                $dataSerie = [];
                foreach ($data as $elem) {
                    if (($serie == $elem[$dataMapSeries])) {
                        $dataSerie[$elem[$widget->getLabel()]] = $elem[$widget->getSerie()];
                    }
                }
                $auxSerie['data'] = $dataSerie;
                $allSeries[] = $auxSerie;
            }
        } else {
            $uniqueDataSerie = [];
            foreach ($data as $elem) {
                $uniqueDataSerie[$elem[$widget->getLabel()]] = $elem[$widget->getSerie()];
                $auxSerie['name'] = $widget->getSerie();
                $auxSerie['data'] = $uniqueDataSerie;
                $allSeries = [$auxSerie];
            }
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
                    $response = $this->getWidgetData(\request(),$widget->getUrl());
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
