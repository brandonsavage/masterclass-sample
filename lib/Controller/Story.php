<?php

class Controller_Story extends Controller_Base {
    
    protected $story_model;
    
    public function _loadModels() {        
        $data_object = new Model_Story_Data($this->db);
        $this->story_model = new Model_Story_Gateway($data_object);
    }

    public function index() {
        if(!$this->request->get('id')) {
            $response = new Response_HttpRedirect();
            $response->setUrl('/');
            return $response->renderResponse();
        }
        
        $story = $this->story_model->getStory($this->request->get('id'));
        if(!($story instanceof Model_Story_Object)) {
            $response = new Response_HttpRedirect();
            $response->setUrl('/');
            return $response->renderResponse();
        }
        
        $comments = $story->getComments();
        $comment_count = $story->getCommentCount();
        $story = $story->getStory();
        
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
            try {
                $args = array(
                   'headline' => $this->request->get('headline'),
                   'url' => $this->request->get('url'),
                   'username' => $this->session->username,
                );
                $id = $this->story_model->saveStory($args);
                $response = new Response_HttpRedirect();
                $response->setUrl("/story/?id=$id");
            return $response->renderResponse();
            } catch (Model_Story_Exception $e) {
                $error = $e->getMessage();
            } catch (Model_Exception $e) {
                
            } catch (Exception $e) {
                
            }
        }
        

        $response = new Response_Http();
        return $response->showView(array('error' => $error,),
                                   $this->config['views']['view_path'] . '/story/create.phtml',
                                   $this->config['views']['layout_path'] . '/layout.phtml');    }
    
}