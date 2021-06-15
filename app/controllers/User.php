<?php
    class User extends Controller {
        public function reg() {

            $data = [];
            if (isset($_COOKIE['login'])) {
                header("Location: dashboard");
            } elseif(isset($_POST['name'])) {
                $user = $this->model('UserModel');
                $user->setData($_POST['name'], $_POST['email'], $_POST['pass'], $_POST['re_pass']);
                $isValid = $user->validForm();

                if($isValid == "Верно") {
                    $user->addUser();
                    header("Refresh: 0");
                } else {
                    $data['message'] = $isValid;
                    $this->view('user/reg', $data);
                }

            } else
                $this->view('user/reg');      
        }

        public function dashboard() {

            $user = $this->model('UserModel');

            $data = ['user' => $user->getUser()];

            if(isset($_POST['exit_btn'])) {
                $user->logOut();
                exit();
            } elseif (isset($_FILES['user_img'])) {
                $img = $_FILES['user_img'];

                $isValidImg = $user->validateImg($img);

                if($isValidImg == 'ok') {
                    $user->saveUserImg($_FILES['user_img']['tmp_name'], $_FILES['user_img']['name']);
                } else {
                    $data['ImgError'] = $isValidImg;
                }
            }

            $this->view('user/dashboard', $data);
        }

        public function auth() {
            $data = [];
            if (isset($_COOKIE['login'])) {
                header("Location: dashboard");
            } elseif (isset($_POST['email'])) {
                $user = $this->model('UserModel');
                $data['message'] = $user->auth($_POST['email'], $_POST['pass']);

                if ($data['message'] == 'ok') {
                    header("Refresh: 0");
                } else {
                    $this->view('user/auth', $data);
                }
                    
            } else {
                $this->view('user/auth');
            }      
        }
    }