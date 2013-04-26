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
            $this->request->get('story_id'),
            filter_var($this->request->get('comment'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        );
        $this->model->createComment($args);
        $response = new Response_HttpRedirect();
        $response->setUrl("Location: /story/?id=" . $this->request->get('story_id'));
        return $response->renderResponse();
    }
    
}