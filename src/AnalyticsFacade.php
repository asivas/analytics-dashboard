<?php

namespace Asivas\Analytics;

class AnalyticsFacade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return "Analytics";
    }
}