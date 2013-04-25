<?php

interface Session_Interface {
    
    public function __construct(array $session_config = array());
    
    public function authenticate();
    
    public function unauthenticate();
    
    public function isAuthenticated();
    
    public function regenerate();

    public function destroy();
    
}