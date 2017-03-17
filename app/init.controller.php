<?php

namespace Sway\Init;

use Sway\Component\Init\InitFramework;
use Sway\Component\Console\Input\ArgvInput;

require_once ('framework.php');

/**
 * 
 */
class Controller
{
    /**
     * Root directory of application
     * @var string
     */
    private $applicationWorkingDirectory = null;
    
    /**
     * Dependecies injector
     * @var \Sway\Component\Dependency\DependencyInjector
     */
    private $dependencyInjector = null;
    
    /**
     *
     * @var \Sway\Component\Init\InitFramework
     */
    private $initFramework = null;
    
    public function __construct(string $fileCaller)
    {
        if (empty($this->applicationWorkingDirectory)){
            $this->getApplicationDirectory($fileCaller);
        }
        
        /**
         * Its very important, creates a dependency injector
         */
        $this->createDependencyInjector();
        
        /** Stores initController as dependency */
        $this->dependencyInjector->createDependency('init.controller', $this);
        
        /** Creates an init framework */
        $this->initFramework = new InitFramework($this->applicationWorkingDirectory, dirname(dirname(__FILE__)));
        $this->dependencyInjector->inject($this->initFramework);
           
    }
    
    /**
     * Gets absolute path of application
     * @param string $fileCaller
     */
    protected function getApplicationDirectory(string $fileCaller)
    {
        $this->applicationWorkingDirectory = (string) realpath(dirname($fileCaller));
    }
    
    /**
     * Gets real path of file
     * @param string $filePath
     * @return string
     */
    protected function getPathOf(string $filePath)
    {
        return realpath($this->applicationWorkingDirectory . DIRECTORY_SEPARATOR . $filePath);
    }
     
    /**
     * Inits application using application's configuration file
     * @param string $configurationFile
     */
    public function initFrom(string $configurationFile, string $mode = 'dev')
    {
        $this->initFramework->setRunningMode($mode);
        $this->initFramework->initConfig();
        
        $this->initFramework->initExtendedConfig($configurationFile);
        $this->initFramework->initStandardFeatures();
        
        $this->initFramework->initComponents();

        
        if ($this->initFramework->hasCfg('framework/global')){
            
            $directAccess = $this->initFramework->getCfg('framework/global');
            
            if (in_array('service', $directAccess)){
                \Framework::setServiceContainer($this->initFramework->getDependency('serviceContainer'));
            }
            
            if (in_array('parameter', $directAccess)){
                \Framework::setParameterContainer($this->initFramework->getDependency('parameter'));
            }
        }    
        
        $this->initFramework->initDistribution();
    }
    
    /**
     * Runs console session
     * @param ArgvInput $input
     */
    public function run(ArgvInput $input)
    {
        $this->initFramework->runConsoleSession($input);
    }
    
    /**
     * Imports file
     * @param string $partlyFilePath
     * @param \Sway\Init\callable $callback
     * @throws \Exception
     */
    public function import(string $partlyFilePath, callable $callback = null)
    {
        $filePath = $partlyFilePath;
        
        if (!$this->endsWith($filePath, '.php')){
            $filePath .= '.php';
        }
        
        $filePath = $this->getPathOf($filePath);
        
        if (@require_once ($filePath)){
            if (!empty($callback)){
                $callback();
            }
        }
        else{
            throw new \Exception(sprintf("Failed while import part of application"));
        }
    }
    
    /**
     * Triggers frameworkInited event
     */
    public function start()
    {
        $this->initFramework->flush();
    }
    
    /**
     * Gets application's working directory
     * @return string
     */
    protected function getWorkingDirectory()
    {
        return (string) $this->applicationWorkingDirectory;
    }
    
    protected function endsWith($haystack, $needle) 
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }
    

    /**
     * Creates a dependency injector
     */
    public function createDependencyInjector()
    {
        if (empty($this->dependencyInjector)){
            $this->dependencyInjector = new \Sway\Component\Dependency\DependencyInjector();
            $this->dependencyInjector->createDependency('injector', $this->dependencyInjector);
        }
    }
    

}



?>