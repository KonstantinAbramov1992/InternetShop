<?php
    require 'DB.php';

    class UserModel {
        private $name;
        private $email;
        private $pass;
        private $re_pass;
        private $img = 'no_image.jpg';

        private $_db = null;

        public function __construct() {
            $this->_db = DB::getInstence();
        }

        public function setData($name, $email, $pass, $re_pass) {
            $this->name = trim(filter_var($name, FILTER_SANITIZE_STRING));
            $this->email = trim(filter_var($email, FILTER_SANITIZE_EMAIL));
            $this->pass = trim(filter_var($pass, FILTER_SANITIZE_STRING));
            $this->re_pass = trim(filter_var($re_pass, FILTER_SANITIZE_STRING));
        }

        public function validForm() {
            if(strlen($this->name) < 3)
                return "Имя слишком короткое";
            else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL))
                return "Email не верный";
            else if(strlen($this->pass) < 3)
                return "Пароль не менее 3 символов";
            else if($this->pass != $this->re_pass)
                return "Пароли не совпадают";
            else
                return "Верно";
        }

        public function addUser() {
            $sql = 'INSERT INTO users(name, email, pass) VALUES(:name, :email, :pass)';
            $query = $this->_db->prepare($sql);

            $pass = password_hash($this->pass, PASSWORD_DEFAULT);
            $query->execute(['name' => $this->name, 'email' => $this->email, 'pass' => $pass]);

            $this->setAuth($this->email);
        }

        public function getUser() {
            $email = $_COOKIE['login'];
            $result = $this->_db->query("SELECT * FROM `users` WHERE `email` = '$email'");
            return $result->fetch(PDO::FETCH_ASSOC);
        }

        public function logOut() {
            setcookie('login', $this->email, time() - 3600 * 24, '/');
            unset($_COOKIE['login']);
            header('Location: /user/auth');
        }

        public function auth($email, $pass) {
            $result = $this->_db->query("SELECT * FROM `users` WHERE `email` = '$email'");
            $user = $result->fetch(PDO::FETCH_ASSOC);

            if($user['email'] == '')
                return 'Пользователя с таким email не существует';
            else if(password_verify($pass, $user['pass'])) {
                $this->setAuth($email);
                return 'ok';
            } else
                return 'Пароли не совпадают';
        }

        public function ValidateImg ($img) {
            $imgSize = $img['size'];

            if ($imgSize == 0) {
                return 'Файл не выбран';
            } elseif ($imgSize > 500000) {
                return 'Файл слишком большой';
            } else {
                return 'ok';
            }
        }

        public function saveUserImg ($imgTmpPath, $imgName) {
            if ($this->img != 'no_image.jpg')
                unlink('public/userImg/' . $this->img);

            $this->img = $this->createNewImgName($imgName);

            if(move_uploaded_file($imgTmpPath, 'public/userImg/' . $this->img)) {
                echo'File is successfully uploaded.';
            }
            else {
                echo 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
            }

            $email = $_COOKIE['login'];

            $sql = "UPDATE `users` SET `img` = :img WHERE `users`.`email` = :email";
            $query = $this->_db->prepare($sql);
            $query->execute(['img' => $this->img, 'email' => $email]);
        }

        public function createNewImgName ($imgName) {
            $imgName = str_replace(" ", '', $imgName);
            $imgNameCmps = explode(".", $imgName);
            $imgExtension = strtolower(end($imgNameCmps));

            return $imgNameCmps[0] . time() . '.' . $imgExtension;
        }

        public function setAuth($email) {
            setcookie('login', $email, time() + 3600 * 24, '/');
        }

    }