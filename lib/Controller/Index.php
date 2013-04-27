<?php

class Controller_Index extends Controller_Base {
    
    protected $story_model;
    protected $comment_model;    
    
    public function _loadModels() {
        $this->story_model = new Model_Story($this->db);
        $this->comment_model = new Model_Comment($this->db);
    }
    
    public function index() {
        
        $stories = $this->story_model->getListOfStories();
                
        foreach($stories as $k => $story) {
            $count = $this->comment_model->getCommentCountForStory($story['id']);
            $stories[$k]['count'] = $count['count'];
        }        
        
        $response = new Response_Http();
        return $response->showView(array('stories' => $stories), 
                                  $this->config['views']['view_path'] . '/index/index.phtml',
                                  $this->config['views']['layout_path'] . '/layout.phtml'
                            );
                                  
    }
}