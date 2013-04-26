<?php

class Controller_Story extends Controller_Base {
    
    protected $story_model;
    protected $comment_model;
    
    public function _loadModels() {
        $this->story_model = new Model_Story($this->db);
        $this->comment_model = new Model_Comment($this->db);
        
    }

    public function index() {
        if(!$this->request->get('id')) {
            $response = new Response_HttpRedirect();
            $response->setUrl('/');
            return $response->renderResponse();
        }
        
        $story = $this->story_model->getStory($this->request->get('id'));
        if(count($story) < 1) {
            $response = new Response_HttpRedirect();
            $response->setUrl('/');
            return $response->renderResponse();
        }
                
        $comments = $this->comment_model->getStoryComments($this->request->get('id'));
        $comment_count = count($comments);
        
        $response = new Response_Http();
        return $response->showView(array('story_id' => $this->request->get('id'),
                                         'story' => $story, 
                                         'comments' => $comments, 
                                         'comment_count' => $comment_count, 
                                         'authenticated' => $this->session->isAuthenticated()
                                        ),
                                   $this->config['views']['view_path'] . '/story/index.phtml',
                                   $this->config['views']['layout_path'] . '/layout.phtml');        
    }
    
    public function create() {
        if(!$this->session->isAuthenticated()) {
            $response = new Response_HttpRedirect();
            $response->setUrl('/user/login');
            return $response->renderResponse();
        }
        
        $error = '';
        if($this->request->get('create')) {
            if(!$this->request->get('headline') || !$this->request->get('url') ||
               !filter_var($this->request->get('url'), FILTER_VALIDATE_URL)) {
                $error = 'You did not fill in all the fields or the URL did not validate.';       
            } else {
                $args = array(
                   $this->request->get('headline'),
                   $this->request->get('url'),
                   $this->session->username,
                );
                $id = $this->story_model->createStory($args);
                $response = new Response_HttpRedirect();
                $response->setUrl("/story/?id=$id");
                return $response->renderResponse();
            }
        }
        

        $response = new Response_Http();
        return $response->showView(array('error' => $error,),
                                   $this->config['views']['view_path'] . '/story/create.phtml',
                                   $this->config['views']['layout_path'] . '/layout.phtml');    }
    
}