<?php

class Controller_Index {
    
    protected $story_model;
    protected $comment_model;
    protected $config;
    protected $session;
    
    
    public function __construct($config) {
        $this->config = $config;
        $this->story_model = new Model_Story($config);
        $this->comment_model = new Model_Comment($config);
        $this->session = new Session_Default();
    }
    
    public function index() {
        
        $stories = $this->story_model->getListOfStories();
        
        $content = '<ol>';
        
        foreach($stories as $story) {
            $count = $this->comment_model->getCommentCountForStory($story['id']);
            $content .= '
                <li>
                <a class="headline" href="' . $story['url'] . '">' . $story['headline'] . '</a><br />
                <span class="details">' . $story['created_by'] . ' | <a href="/story/?id=' . $story['id'] . '">' . $count['count'] . ' Comments</a> | 
                ' . date('n/j/Y g:i a', strtotime($story['created_on'])) . '</span>
                </li>
            ';
        }
        
        $content .= '</ol>';
        
        require $this->config['views']['layout_path'] . '/layout.phtml';
    }
}