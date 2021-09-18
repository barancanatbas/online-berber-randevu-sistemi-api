<?php
include_once 'controller.php';
include_once 'model/ilce.php';

class Ilce extends MainController
{
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = new Dbilce();
    }

    public function get($ilno)
    {

        $ilno = (int)$ilno;
        if (is_int($ilno))
        {
            $veri = json_encode($this->db->GetByilno($ilno));
            print_r($veri);
        }
        else {
            print_r(json_encode(["hata" => "bir id degeri giriniz"]));
        }
    }

}