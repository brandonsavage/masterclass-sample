<?php

abstract class Controller_Base {
    
    protected $config = array();
    protected $session;
    protected $db;
    protected $request;
    
    public function __construct(array $config = array()) {
        $this->config = $config;
    }
    
    public function init() {
        $this->_loadModels();
    }
    
    public function setSession(Session_Interface $session) {
        $this->session = $session;
    }
    
    public function setRequest(Request_Interface $request) {
        $this->request = $request;
    }
    
    public function setDatabase(Database_Base $db) {
        $this->db = $db;
    }
    
    abstract protected function _loadModels();
    
}