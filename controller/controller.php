<?php
include_once 'config/settings.php';
include_once 'model/kullanicilar.php';

class MainController extends settings
{
    public $basedir;

    public function __construct()
    {
        parent::__construct();
        $this->basedir = "/berber/";
    }

    public function checkauth($kuladi,$sifre)
    {
        $result = $this->user->login($kuladi,$sifre);
        return $result;
    }

}