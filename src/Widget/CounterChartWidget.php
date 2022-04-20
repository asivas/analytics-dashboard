<?php

namespace Asivas\Analytics\Widget;

use Asivas\Analytics\Http\Controllers\Dashboard\BarChartController;
use Asivas\Analytics\Http\Controllers\Dashboard\CounterChartController;

class CounterChartWidget extends Widget
{

    protected $bgcolor;
    protected $icon;

    public function __construct($title)
    {
        parent::__construct($title, 'CounterChart', CounterChartController::class);
    }

    public function toArray(): array
    {
        $arr = parent::toArray();
        $arr['bgcolor'] = $this->bgcolor;
        $arr['icon'] = $this->icon;
        return $arr;
    }

}