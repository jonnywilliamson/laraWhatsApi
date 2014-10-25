<?php namespace Williamson\Larawhatsapi\Facades;

use Illuminate\Support\Facades\Facade;

class LaraWhatsapiFacade extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'Williamson\Larawhatsapi\Repository\SMSMessageInterface';
    }
}