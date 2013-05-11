<?php

class Controller_Index extends Controller_Base {
    
    protected $story_model;
    protected $comment_model;    
    
    public function _loadModels() {        
        $data_object = new Model_Story_Data($this->db);
        $this->story_model = new Model_Story_Gateway($data_object);
    }
    
    public function index() {
        
        $stories = $this->story_model->storyList();     
        
        $response = new Response_Http();
        return $response->showView(array('stories' => $stories), 
                                  $this->config['views']['view_path'] . '/index/index.phtml',
                                  $this->config['views']['layout_path'] . '/layout.phtml'
                            );
                                  
    }
}