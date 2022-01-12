<?php

namespace Asivas\Analytics\Widget;

use Asivas\Analytics\Http\Controllers\Dashboard\BarChartController;

class BarChartWidget extends Widget
{
    public function __construct($title)
    {
        parent::__construct($title, 'BarChart', BarChartController::class);
    }

}