<?php
    class Categories extends Controller {
        public function index($pageNumber = 1) {
            $products = $this->model('Products');
            $data = [
                'products' => $products->getProducts(),
                'title' => 'Все товары на сайте',
                'pageNumber' => $pageNumber
            ];
            $this->view('categories/index', $data);
        }

        public function shoes($pageNumber = 1) {
            $products = $this->model('Products');
            $data = [
                'products' => $products->getProductsCategory('shoes'),
                'title' => 'Категория обувь',
                'pageNumber' => $pageNumber
            ];
            $this->view('categories/index', $data);
        }

        public function hats($pageNumber = 1) {
            $products = $this->model('Products');
            $data = [
                'products' => $products->getProductsCategory('hats'),
                'title' => 'Категория кепки',
                'pageNumber' => $pageNumber];
            $this->view('categories/index', $data);
        }

        public function shirts($pageNumber = 1) {
            $products = $this->model('Products');
            $data = [
                'products' => $products->getProductsCategory('shirts'),
                'title' => 'Категория футболки',
                'pageNumber' => $pageNumber
            ];
            $this->view('categories/index', $data);
        }

        public function watches($pageNumber = 1) {
            $products = $this->model('Products');
            $data = [
                'products' => $products->getProductsCategory('watches'),
                'title' => 'Категория часы',
                'pageNumber' => $pageNumber
            ];
            $this->view('categories/index', $data);
        }
    }