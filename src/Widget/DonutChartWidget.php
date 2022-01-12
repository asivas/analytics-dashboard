<?php

namespace Asivas\Analytics\Widget;

use Asivas\Analytics\Http\Controllers\Dashboard\DonutChartController;

class DonutChartWidget extends Widget
{
    public function __construct($title)
    {
        parent::__construct($title, 'DonutChart', DonutChartController::class);
    }
}