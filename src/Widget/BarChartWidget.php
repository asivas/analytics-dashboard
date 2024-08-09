<?php

namespace Asivas\Analytics\Widget;

use Asivas\Analytics\Http\Controllers\Dashboard\BarChartController;

class BarChartWidget extends Widget
{
    protected $horizontal=false;
    protected $stacked=false;

    public function __construct($title)
    {
        parent::__construct($title, 'BarChart', BarChartController::class);
    }

    /**
     * @return bool
     */
    public function isHorizontal(): bool
    {
        return $this->horizontal;
    }

    /**
     * @param bool $horizontal
     */
    public function setHorizontal(bool $horizontal): void
    {
        $this->horizontal = $horizontal;
    }

    public function isStacked(): bool
    {
        return $this->stacked;
    }

    public function setStacked(bool $stacked): BarChartWidget
    {
        $this->stacked = $stacked;
        return $this;
    }



    public function toArray(): array
    {
        $arr = parent::toArray();
        $arr['horizontal'] = $this->horizontal;
        $arr['stacked'] = $this->stacked;
        return $arr;
    }


}