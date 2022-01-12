<?php

namespace Asivas\Analytics\Widget;

use Asivas\Analytics\Http\Controllers\Dashboard\LineChartController;

class LineChartWidget extends Widget
{
    public function __construct($title)
    {
        parent::__construct($title, 'LineChart', LineChartController::class);
    }

}