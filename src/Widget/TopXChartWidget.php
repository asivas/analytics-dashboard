<?php

namespace Asivas\Analytics\Widget;

use Asivas\Analytics\Http\Controllers\Dashboard\TopXchartController;

class TopXChartWidget extends Widget
{
    public function __construct($title)
    {
        parent::__construct($title, 'TopXchart', TopXchartController::class);
    }

}