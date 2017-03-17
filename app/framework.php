<?php

use Sway\Component\Service;
use Sway\Component\Parameter;

class Framework
{
    /**
     *
     * @var \Sway\Component\Service\Container
     */
    private static $serviceContainer = null;
    
    /**
     *
     * @var \Sway\Component\Parameter\Container
     */
    private static $parameterContainer = null;
    
    /**
     * Sets service container (only once)
     * @param \Sway\Component\Service\Container $serviceContainer
     */
    public static function setServiceContainer(Service\Container $serviceContainer)
    {
        if (empty(self::$serviceContainer)){
            self::$serviceContainer = $serviceContainer;
        }
    }
    
    
    /**
     * Sets parameters container (only once)
     * @param \Sway\Component\Parameter\Container $parameterContainer
     */
    public static function setParameterContainer(Parameter\Container $parameterContainer)
    {
        if (empty(self::$parameterContainer)){
            self::$parameterContainer = $parameterContainer;
        }
    }
    
    /**
     * Gets service
     * @param string $serviceName
     * @return object
     */
    public static function get(string $serviceName)
    {
        return self::$serviceContainer->get($serviceName);
    }
    
    /**
     * 
     * @param string $parameterName
     * @return mixed
     */
    public static function getParameter(string $parameterName)
    {
        return self::$parameterContainer->get($parameterName);
    }
    
    /**
     * Checks if parameter is exists
     * @param string $parameterName
     */
    public static function hasParameter(string $parameterName)
    {
        return self::$parameterContainer->hasParameter($parameterName);
    }
    
    public static function getServiceContainer()
    {
        return self::$serviceContainer;
    }

}


?>