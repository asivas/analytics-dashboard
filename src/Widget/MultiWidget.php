<?php

namespace Asivas\Analytics\Widget;

use Asivas\Analytics\PanelWidget;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class MultiWidget extends Widget
{
    protected $widgets;
    protected $options;

    public function __construct($title, $type = null, $controllerClass = null)
    {
        parent::__construct($title, 'MultiWidget', $controllerClass);
    }

    /**
     * @return mixed
     */
    public function getWidgets()
    {
        return $this->widgets;
    }

    /**
     * @param mixed $widgets
     * @return MultiWidget
     */
    public function setWidgets($widgets)
    {
        $this->widgets = $widgets;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $options
     * @return MultiWidget
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return MultiWidget
     */
    public function fetchOptions()
    {
        return $this;
    }


    /**
     * @param \Asivas\Analytics\Widget\Widget $widget
     * @return self
     */
    public function addWidget($widget,$widgetName=null) {
        if(!isset($widgetName)) {
            if($widget->getUrl()!=null)
                $widgetName = $widget->getUrl();
            else
                $widgetName = $widget->getTitle();
        }
        $this->widgets[$widgetName] = $widget;
        return $this;
    }

    public function toArray(): array
    {
        $toArray = parent::toArray();
        $toArray['widgets']=$this->widgets;
        $toArray['options']=$this->options;
        return $toArray;
    }


}