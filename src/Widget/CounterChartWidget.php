<?php

namespace Asivas\Analytics\Widget;

use Asivas\Analytics\Http\Controllers\Dashboard\BarChartController;
use Asivas\Analytics\Http\Controllers\Dashboard\CounterChartController;

class CounterChartWidget extends Widget
{
    protected $icon;
    protected $themeName;
    protected $counterFormat;
    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     * @return Widget
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getThemeName()
    {
        return $this->themeName;
    }

    /**
     * @param mixed $themeName
     * @return Widget
     */
    public function setThemeName($themeName): CounterChartWidget
    {
        $this->themeName = $themeName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCounterFormat()
    {
        return $this->counterFormat;
    }

    /**
     * @param mixed $counterFormat
     * @return CounterChartWidget
     */
    public function setCounterFormat($counterFormat)
    {
        $this->counterFormat = $counterFormat;
        return $this;
    }




    public function __construct($title)
    {
        parent::__construct($title, 'CounterChart', CounterChartController::class);
    }

    public function toArray(): array
    {
        $arr = parent::toArray();
        $arr['icon'] = $this->icon;
        $arr['themeName'] = $this->themeName;
        $arr['counterFormat'] = $this->counterFormat;
        return $arr;
    }

}