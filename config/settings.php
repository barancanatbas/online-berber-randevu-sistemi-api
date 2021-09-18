<?php
session_start();
ob_start();
date_default_timezone_set('Europe/Istanbul');

include_once 'model/kullanicilar.php';

class settings
{

    protected $user;
    public function __construct()
    {
        $this->user = new Dbkullanicilar();
    }

    public function filtre($veri)
    {
        $bir = trim($veri);
        $iki = strip_tags($bir);
        $uc = htmlspecialchars($iki,ENT_QUOTES);
        return $uc;
    }

    public function filtreCoz($veri)
    {
        $bir = htmlspecialchars_decode($veri,ENT_QUOTES);
        return $bir;
    }

}

