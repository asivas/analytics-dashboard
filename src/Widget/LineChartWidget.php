<?php

namespace Asivas\Analytics\Widget;

use Asivas\Analytics\Enums\LineChartWidgetStrokes;
use Asivas\Analytics\Http\Controllers\Dashboard\LineChartController;

class LineChartWidget extends Widget
{
    protected $stroke = LineChartWidgetStrokes::straight;

    public function __construct($title)
    {
        parent::__construct($title, 'LineChart', LineChartController::class);
    }

    public function getStroke(): string
    {
        return $this->stroke;
    }

    /**
     * @param LineChartWidgetStrokes $stroke
     * @return void
     */
    public function setStroke(LineChartWidgetStrokes $stroke): self
    {
        $this->stroke = $stroke;
        return $this;
    }


    public function toArray(): array
    {
        return parent::toArray() +
            [
                'stroke' => $this->stroke
            ];
    }

}