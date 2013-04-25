<?php

abstract class Controller_Base {
    
    protected $config = array();
    protected $session;
    
    public function __construct(Session_Interface $session, Database_Base $db, array $config = array()) {
        $this->config = $config;
        $this->session = $session;
        $this->db = $db;
        $this->_loadModels();
    }
    
    abstract protected function _loadModels();
    
}