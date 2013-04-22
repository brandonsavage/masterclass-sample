<?php

class MasterController {
    
    private $config;
    protected $_session;
    protected $_db;
    protected $router;
    
    public function __construct($config) {
        $this->_setupConfig($config);
        
        $autoloader_driver = $config['autoloader'];
        $autoloader_path = require('Autoloader/' . $autoloader_driver . '.php');
        $autoloader = $this->loadDependency('Autoloader_', $autoloader_driver);
        spl_autoload_register(array($autoloader, 'loader'));
        $this->router = $this->_loadRouter();
        $this->_db = $this->_loadDb();
        $this->_configureSession();
    }
    
    protected function _loadRouter() {
        $router = $this->config['router'];
        return $this->loadDependency('Router_', $router);
    }
    
    protected function _loadDb() {
        $driver = $this->config['database']['driver'];
        return $this->loadDependency('Database_', $driver, $this->config['database']);
    }
    
    public function execute() {
        $call = $this->router->determineRouting();
        $call_class = $call['call'];
        $class = ucfirst(array_shift($call_class));
        $class = 'Controller_' . $class;
        $method = array_shift($call_class);
        $o = new $class($this->_session, $this->_db, $this->config);
        return $o->$method();
    }
    
    protected function _configureSession() {
        $config = $this->config;
        $session_config = $config['session_config'];
        $driver = $session_config['driver'];
        $this->_session = $this->loadDependency('Session_', $driver, $session_config);
    }
    
    private function _setupConfig($config) {
        $this->config = $config;
    }
    
    protected function loadDependency($prefix, $driver, array $config = array()) {
        if(empty($config)) {
            $config = $this->config;
        }
        
        $classname = $prefix . $driver;
        return new $classname($config);
    }    
}