<?php

class Model_Comment extends Model_Base {
    
    public function getCommentCountForStory($story_id) {
        $comment_sql = 'SELECT COUNT(*) as `count` FROM comment WHERE story_id = ?';
        $count = $this->fetchOne($comment_sql, array($story_id));
        return $count;
    }
    
    public function getStoryComments($story_id) {
        $comment_sql = 'SELECT * FROM comment WHERE story_id = ?';
        $comments = $this->fetchAll($comment_sql, array($story_id));
        return $comments;
    }
    
    public function createComment(array $params = array()) {
        $sql = 'INSERT INTO comment (created_by, created_on, story_id, comment) VALUES (?, NOW(), ?, ?)';
        return $this->insert($sql, $params);
    }
}