<?php

namespace Asivas\Analytics;

class WidgetControllerFacade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return "WidgetController";
    }
}