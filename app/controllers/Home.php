<?php
    class Home extends Controller {
        public function index() {
            $products = $this->model('Products');
            $data = $products->getProductsLimited(5);
            print_r($data);

            $this->view('home/index', $data);
        }
    }