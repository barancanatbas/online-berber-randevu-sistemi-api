<?php
include_once 'controller.php';
include_once 'model/adres.php';

class adres extends MainController
{

    protected $db;
    public function __construct()
    {
        $this->db = new Dbadres();
    }
    public function index($adresFk)
    {
        $result = $this->db->get($adresFk);
        echo json_encode($result);
    }
}