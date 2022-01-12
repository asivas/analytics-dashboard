<?php

namespace Asivas\Analytics\Widget;

use Asivas\Analytics\Http\Controllers\Dashboard\RadarChartController;

class RadarChartWidget extends Widget
{
    public function __construct($title)
    {
        parent::__construct($title, 'RadarChart', RadarChartController::class);
    }

}