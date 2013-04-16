<?php

class Model_User {
    
    protected $db;
    
    public function __construct($config) {
        $this->config = $config;
        $this->db = new Database_Mysql($config);
    }
    
    public function createUser(array $params = array()) {
        $sql = 'INSERT INTO user (username, email, password) VALUES (?, ?, ?)';
        return $stmt = $this->db->insert($sql, $params);
    }
    
    public function checkUsername($username) {
        $check_sql = 'SELECT * FROM user WHERE username = ?';
        $check_stmt = $this->db->fetchOne($check_sql, array($username));
        return $this->db->rowCount();
    }
    
    public function authenticateUser($username, $password) {
        $password = md5($username . $password); // THIS IS NOT SECURE. DO NOT USE IN PRODUCTION.
        $sql = 'SELECT * FROM user WHERE username = ? AND password = ? LIMIT 1';
        $user = $this->db->fetchOne($sql, array($username, $password));
        return array('authenticated' => $this->db->rowCount(), 'user' => $user);
    }
    
    public function getUserData($username) {
        $dsql = 'SELECT * FROM user WHERE username = ?';
        return $this->db->fetchOne($dsql, array($username));    
    }
    
    public function changeUserPassword($username, $password) {
        $sql = 'UPDATE user SET password = ? WHERE username = ?';
        $params = array(
           md5($username . $password), // THIS IS NOT SECURE. 
           $username,
        );
        return $this->db->insert($sql, $params);     
    }
}