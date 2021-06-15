<?php
    class Contact extends Controller {
        public function index() {
            $data = func_get_args();

            if(isset($_POST['name'])) {
                $mail = $this->model('ContactForm');
                $mail->setData($_POST['name'], $_POST['email'], $_POST['age'], $_POST['message']);

                $isValid = $mail->validForm();

                if($isValid == "Верно")
                    $data['message'] = $mail->mail();
                else
                    $data['message'] = $isValid;
            }

            $this->view('contact/index', $data);
        }

        public function about() {
            $data = func_get_args();
            $this->view('contact/about', $data);
        }
    }