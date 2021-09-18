<?php
include_once "controller.php";
include_once "model/iller.php";

class Iller extends MainController
{

    private $db;

    public function __construct()
    {
        $this->db = new Dbiller();
    }

    public function index()
    {
        $veriler = json_encode($this->db->gets());
        print_r($veriler);
    }

    public function il($id = 0)
    {
        $id = (int) $id;
        if ($id == 0)
        {
            print_r(json_encode(["hata"=> "Bir id değeri gönderin"]));
        }
        if (is_int($id))
        {
            $veri = json_encode($this->db->get($id));
            print_r($veri);
        }
    }
}
