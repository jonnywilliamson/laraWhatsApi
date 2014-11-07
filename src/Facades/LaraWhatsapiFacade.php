<?php namespace Williamson\Larawhatsapi\Facades;

use Illuminate\Support\Facades\Facade;

class LaraWhatsapiFacade extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'Williamson\Larawhatsapi\Repository\SMSMessageInterface';
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param  string  $method
     * @param  array   $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::getFacadeRoot()->getWhatsApi();

        switch (count($args))
        {
            case 0:
                return $instance->$method();

            case 1:
                return $instance->$method($args[0]);

            case 2:
                return $instance->$method($args[0], $args[1]);

            case 3:
                return $instance->$method($args[0], $args[1], $args[2]);

            case 4:
                return $instance->$method($args[0], $args[1], $args[2], $args[3]);

            case 5:
                return $instance->$method($args[0], $args[1], $args[2], $args[3], $args[4]);

            case 6:
                return $instance->$method($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);

            case 7:
                return $instance->$method($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6]);

            default:
                return call_user_func_array(array($instance, $method), $args);
        }
    }

}