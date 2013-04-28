<?php

class Controller_Comment extends Controller_Base {
    
    protected $model;
    
    public function _loadModels() {        
        $data_object = new Model_Story_Data($this->db);
        $this->model = new Model_Story_Gateway($data_object);
    }
    
    public function create() {
        if(!$this->session->isAuthenticated()) {
            header("Location: /");
            exit;
        }
        
        $this->model->addComment($this->request->get('story_id'),
                                 $this->session->username,
                                 $this->request->get('comment'));
        $response = new Response_HttpRedirect();
        $response->setUrl("Location: /story/?id=" . $this->request->get('story_id'));
        return $response->renderResponse();
    }
    
}