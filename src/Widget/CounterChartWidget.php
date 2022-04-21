<?php

namespace Asivas\Analytics\Widget;

use Asivas\Analytics\Http\Controllers\Dashboard\BarChartController;
use Asivas\Analytics\Http\Controllers\Dashboard\CounterChartController;

class CounterChartWidget extends Widget
{
    protected $icon;

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

    public function __construct($title)
    {
        parent::__construct($title, 'CounterChart', CounterChartController::class);
    }

    public function toArray(): array
    {
        $arr = parent::toArray();
        $arr['icon'] = $this->icon;
        return $arr;
    }

}