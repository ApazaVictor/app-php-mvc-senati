<?php
class HomeController
{
    public function index()
    {
        include 'views/layouts/header.php';
        include 'views/home/index.php';
        include 'views/layouts/footer.php';
    }
}