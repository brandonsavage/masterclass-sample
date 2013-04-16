<?php

class Model_Comment {
    
    public function __construct($config) {
        $this->config = $config;
        $this->db = new Database_Mysql($config);
    }
    
    public function getCommentCountForStory($story_id) {
        $comment_sql = 'SELECT COUNT(*) as `count` FROM comment WHERE story_id = ?';
        $count = $this->db->fetchOne($comment_sql, array($story_id));
        return $count;
    }
    
    public function getStoryComments($story_id) {
        $comment_sql = 'SELECT * FROM comment WHERE story_id = ?';
        $comments = $this->db->fetchAll($comment_sql, array($story_id));
        return $comments;
    }
    
    public function createComment(array $params = array()) {
        $sql = 'INSERT INTO comment (created_by, created_on, story_id, comment) VALUES (?, NOW(), ?, ?)';
        return $this->db->insert($sql, $params);
    }
}