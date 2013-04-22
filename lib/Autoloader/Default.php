<?php

class Autoloader_Default {
    public function loader($class) {
        $className = str_replace('_', '/', $class);
        $className = $className . '.php';
        require_once $className;  
    }
}