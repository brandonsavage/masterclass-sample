<?php

class Controller_User extends Controller_Base {
    
    protected $model;
        
    public function _loadModels() {
        $this->model = new Model_User($this->db);
    }
    
    public function create() {
        $error = null;
        
        // Do the create
        if($this->request->get('create')) {
            if(!$this->request->get('username') || !$this->request->get('email') ||
               !$this->request->get('password') || !$this->request->get('password_check')) {
                $error = 'You did not fill in all required fields.';
            }
            
            if(is_null($error)) {
                if(!filter_var($this->request->get('email'), FILTER_VALIDATE_EMAIL)) {
                    $error = 'Your email address is invalid';
                }
            }
            
            if(is_null($error)) {
                if($this->request->get('password') != $this->request->get('password_check')) {
                    $error = "Your passwords didn't match.";
                }
            }
            
            if(is_null($error)) {

                if($this->model->checkUsername($this->request->get('username')) > 0) {
                    $error = 'Your chosen username already exists. Please choose another.';
                }
            }
            
            if(is_null($error)) {
                $params = array(
                    $this->request->get('username'),
                    $this->request->get('email'),
                    md5($this->request->get('username') . $this->request->get('password')),
                );
                $this->model->createUser($params);
                $response = new Response_HttpRedirect();
                $response->setUrl('/user/login');
                return $response->renderResponse();
            }
        }
        // Show the create form
        
        $response = new Response_Http();
        return $response->showView(array('error' => $error),
                            $this->config['views']['view_path'] . '/user/create.phtml',
                            $this->config['views']['layout_path'] . '/layout.phtml');        
    }
    
    public function account() {
        $error = null;
        if(!$this->session->isAuthenticated()) {
            header("Location: /user/login");
            exit;
        }
        
        if(isset($_POST['updatepw'])) {
            if(!isset($_POST['password']) || !isset($_POST['password_check']) ||
               $_POST['password'] != $_POST['password_check']) {
                $error = 'The password fields were blank or they did not match. Please try again.';       
            }
            else {
                $this->model->changeUserPassword($this->session->username, $_POST['password']);
                $error = 'Your password was changed.';
            }
        }
        
        $details = $this->model->getUserData($this->session->username);
        
        $response = new Response_Http();
        return $response->showView(array('error' => $error, 'username' => $details['username'], 'email' => $details['email']),
                            $this->config['views']['view_path'] . '/user/account.phtml',
                            $this->config['views']['layout_path'] . '/layout.phtml');    }
    
    public function login() {
        $error = null;
        // Do the login
        if($this->request->get('login')) {
            $username = $this->request->get('user');
            $password = $this->request->get('pass');
            $result = $this->model->authenticateUser($username, $password);

            if($result['authenticated']) {
               $data = $result['user'];
               session_regenerate_id();
               $this->session->username = $data['username'];
               $this->session->authenticate();
               $response = new Response_HttpRedirect();
               $response->setUrl('/');
               return $response->renderResponse();
            }
            else {
                $error = 'Your username/password did not match.';
            }
        }
        
        $response = new Response_Http();
        return $response->showView(array('error' => $error),
                            $this->config['views']['view_path'] . '/user/login.phtml',
                            $this->config['views']['layout_path'] . '/layout.phtml');        
    }
    
    public function logout() {
        // Log out, redirect
        session_destroy();
        header("Location: /");
    }
}