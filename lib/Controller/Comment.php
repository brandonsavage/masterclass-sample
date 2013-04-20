<?php

class Controller_Comment extends Controller_Base {
    
    protected $model;
    
    public function _loadModels() {
        $this->model = new Model_Comment($this->db);
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