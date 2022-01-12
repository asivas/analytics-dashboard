<?php

namespace Asivas\Analytics\Widget;

use Asivas\Analytics\Http\Controllers\Dashboard\BarChartController;
use Asivas\Analytics\Http\Controllers\Dashboard\CounterChartController;

class CounterChartWidget extends Widget
{

    public function __construct($title)
    {
        parent::__construct($title, 'CounterChart', CounterChartController::class);
    }

}