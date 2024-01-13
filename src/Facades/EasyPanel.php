<?php

namespace EasyPanel\Facades;
use Illuminate\Support\Facades\Facade;

class EasyPanel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ezPanel';
    }

}