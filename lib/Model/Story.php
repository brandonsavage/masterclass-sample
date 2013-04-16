<?php

class Model_Story {
    
    protected $db; 
    
    public function __construct($config) {
        $this->config = $config;
        $this->db = new Database_Mysql($config);
    }
    
    public function getListOfStories() {
        $sql = 'SELECT * FROM story ORDER BY created_on DESC';
        $stories = $this->db->fetchAll($sql);
        return $stories;
    }
    
    public function getStory($story_id) {
        $story_sql = 'SELECT * FROM story WHERE id = ?';
        $story = $this->db->fetchOne($story_sql, array($story_id));
        return $story;
    }
    
    public function createStory(array $params = array()) {
        $sql = 'INSERT INTO story (headline, url, created_by, created_on) VALUES (?, ?, ?, NOW())';
        $this->db->insert($sql, $params);
        $id = $this->db->lastInsertId();
        return $id;
    }
}

