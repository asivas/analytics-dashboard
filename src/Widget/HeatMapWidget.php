<?php

namespace Asivas\Analytics\Widget;


use Asivas\Analytics\Http\Controllers\Dashboard\HeatMapChartController;

class HeatMapWidget extends Widget
{
    public function __construct($title)
    {
        parent::__construct($title, 'LineChart', HeatMapChartController::class);
    }
}