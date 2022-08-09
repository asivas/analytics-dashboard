<?php
namespace Asivas\Analytics\Http\Controllers\Dashboard;

use Asivas\Analytics\AnalyticsFacade;
use Asivas\Analytics\Http\Controllers\Dashboard\Formaters\PercentualFormatter;
use Asivas\Analytics\Http\Controllers\Dashboard\Formaters\WidgetFormatter;
use Asivas\Analytics\PanelWidget;
use Asivas\Analytics\Widget\MultiWidget;
use Asivas\Analytics\Widget\Widget;
use Asivas\Analytics\WidgetControllerFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardWidgetController
{
    protected static $labelsSeriesMaps;
    protected $formatters = [];

    public function getAnalytics()
    {
        $this->setupWidgetsPanels();
        $panels = $this->getWidgetsPanels();
        $analytics = [];
        $request = \request();
        $from = $request->query('startDate',Carbon::today());
        $to = $request->query('endDate',Carbon::today());

        foreach ($panels as $panel) {
            $widgets = $panel->getWidgets();
            $panelAnalytics = $panel->toArray();
            $panelWidgetsWithData=[];
            /** @var Widget $widget */
            foreach ($widgets as $widgetName => $widget) {
                $data = null;

                if($widget->getUrl()!=null) {
                    $data = $this->fetchWidgetData($widget, $request);
                }else{
                    /** @var WidgetFormatter $formatter */
                    $formatter = $this->getFormatter($widget);
                    $data = $formatter->prepareWidgetData($widget, $from, $to);
                    if(is_a($widget,MultiWidget::class))
                    {
                        foreach ($widget->getWidgets() as $subWidgetName => $subWidget) {
                            $subWidget->setData($this->fetchWidgetData($subWidget,$request));
                        }
                    }
                }
                $widget->setData($data);
                $panelWidgetsWithData[$widgetName] = $widget->toArray();
            }
            $panelAnalytics['widgets'] = $panelWidgetsWithData;
            $analytics[$panel->getType()] = $panelAnalytics;
        }
        return $analytics;
    }

    /**
     * @param Widget $widget
     * @return WidgetFormatter It should return the formatter class needed for the type of graphs and the analitic
     */
    public function getFormatter(Widget $widget)
    {
        $formatterClass = $widget->getFormatter();
        if (!isset($formatterClass))
            $formatterClass = PercentualFormatter::class;

        if(!isset($this->formatters[$formatterClass]))
            $this->formatters[$formatterClass] = new $formatterClass();


        return $this->formatters[$formatterClass];
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
            /** @var Widget $widget */
            foreach ($widgets as $widget) {
                if ($widget->getUrl() === $analyticsName)
                    return $widget;
                if(is_a($widget,MultiWidget::class)) {
                    foreach ($widget->getWidgets() as $subWidget)
                    {
                        if ($subWidget->getUrl() === $analyticsName)
                            return $subWidget;
                    }
                }
            }
        }
        return null;
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
        //if(!isset($panelTitle)) $panelTitle = $panelName;
        if(!isset(self::$labelsSeriesMaps[$panelName]))
            self::addWidgetsPanel(PanelWidget::create($panelName,$panelTitle));
        return self::$labelsSeriesMaps[$panelName];
    }


    /**
     * @param Widget $widget
     * @param $request
     * @return array
     */
    protected function fetchWidgetData(Widget $widget, $request): array
    {
        $params = $request->all();
        $from = $request->query('startDate',Carbon::today());
        $to = $request->query('endDate',Carbon::today());
        $params['startDate'] = $from;
        $params['endDate'] = $to;
        return $this->getFormatter($widget)->getWidgetData($widget->getUrl(),$from,$to,$params);
    }


}

