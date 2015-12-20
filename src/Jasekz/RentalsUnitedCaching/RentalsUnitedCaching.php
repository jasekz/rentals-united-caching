<?php
namespace Jasekz\RentalsUnitedCaching;

use Exception;

class RentalsUnitedCaching {

    /**
     * Laravel application
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /**
     * Create a new confide instance.
     *
     * @param \Illuminate\Foundation\Application $app            
     *
     * @return void
     */
    public function __construct()
    {
        $this->app = app();
    }

    /**
     * Magic method implementation for all the services
     *
     * @param string $name            
     * @param mixed $arguments            
     * @throws Exception
     * @return RentalsUnitedCaching\Services\Processable
     */
    public function __call($name, $arguments)
    {
        $name = 'Jasekz\RentalsUnitedCaching\Models\\' . ucfirst($name);
        try {
            if (! isset($this->app[$name])) { // bind for if not already done so
                $this->app->singleton($name, function () use ($name, $arguments)
                {
                    return new $name($arguments);
                });
            }
            return $this->app[$name];
        } 

        catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Magic method implementation for all the services
     *
     * @param string $name            
     * @param mixed $arguments            
     * @throws Exception
     * @return RentalsUnitedCaching\Services\Processable
     */
    public function dataLoader($name = null, $arguments = null)
    {
        $name = $name ? $name : 'base';
        $name = 'Jasekz\RentalsUnitedCaching\DataLoaders\\' . ucfirst($name);
        try {
            if (! isset($this->app[$name])) { // bind for if not already done so
                $this->app->singleton($name, function () use ($name, $arguments)
                {
                    return new $name($arguments);
                });
            }
            return $this->app[$name];
        } 

        catch (Exception $e) {
            throw $e;
        }
    }
}
