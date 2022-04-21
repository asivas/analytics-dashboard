<?php

namespace Asivas\Analytics\Widget;

use Asivas\Analytics\PanelWidget;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class MultiWidget extends Widget
{
    /** @var PanelWidget */
    protected $panel;
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
        return $this->panel->getWidgets();
    }

    /**
     * @param mixed $widgets
     * @return MultiWidget
     */
    public function setWidgets($widgets)
    {
        $this->panel->setWidgets($widgets);
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
     * @return PanelWidget
     */
    public function getPanel(): PanelWidget
    {
        return $this->panel;
    }

    /**
     * @param PanelWidget $panel
     */
    public function setPanel(PanelWidget $panel): void
    {
        $this->panel = $panel;
    }


    /**
     * @param \Asivas\Analytics\Widget\Widget $widget
     * @return self
     */
    public function addWidget($widget,$widgetName=null) {
        $this->panel->addWidget($widget,$widgetName);
        return $this;
    }

    public function toArray(): array
    {
        $toArray = parent::toArray();
        $toArray['widgets']=$this->getWidgets();
        $toArray['panel']=$this->getPanel();
        $toArray['options']=$this->options;
        return $toArray;
    }


}