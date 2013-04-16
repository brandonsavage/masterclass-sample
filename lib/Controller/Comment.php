<?php

class Controller_Comment {
    
    protected $model;
    protected $config;
    protected $session;
    
    public function __construct($config) {
        $this->config = $config;
        $this->model = new Model_Comment($config);
        $this->session = new Session_Default();
    }
    
    public function create() {
        if(!$this->session->isAuthenticated()) {
            header("Location: /");
            exit;
        }
        
        $args = array(
            $this->session->username,
            $_POST['story_id'],
            filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        );
        $this->model->createComment($args);
        header("Location: /story/?id=" . $_POST['story_id']);
    }
    
}