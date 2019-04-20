<?php

namespace Hetsabit\Payments\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for using the Payment service
 */
class Payment extends Facade{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(){
        return 'Payment';
    }
}
