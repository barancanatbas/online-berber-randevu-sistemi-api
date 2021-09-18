<?php
include_once 'controller.php';
include_once 'model/tarifeler.php';
include_once 'berber.php';

class Tarifeler extends MainController
{
    protected $db;
    protected $berberDb;
    public function __construct()
    {
        parent::__construct();
        $this->berberDb = new Dbberber();
        $this->db = new Dbtarifeler();
    }

    public function insert(int $id =0)
    {
        if ($id != 0 and $id > 0)
        {
            if (isset($_POST) and !empty($_POST))
            {
                $kuladi = $this->filtre($_POST["kuladi"]);
                $sifre = $_POST["sifre"];

                $user = $this->checkauth($kuladi,$sifre);
                if (@$user["id"] == $id and @$user["berbermi"] == 1)
                {
                    $berber = $this->berberDb->get($user["id"]);
                    $tarifeAd = $this->filtre($_POST["tarifeAd"]);
                    $tarifeUcret = $this->filtre($_POST["tarifeUcret"]);

                    $result = $this->db->insert($berber["id"],$tarifeAd,$tarifeUcret);
                    if ($result)
                    {
                        echo json_encode(["mesaj" => "Tarife Eklendi"]);
                    }
                }
                else echo json_encode(["mesaj" => "kullanici adı veya şifreyi tekrar giriniz"]);
            }
        }
    }

    public function delete(int $id =0)
    {
        if ($id != 0 and $id > 0)
        {
            if (isset($_POST) and !empty($_POST))
            {
                $kuladi = $this->filtre($_POST["kuladi"]);
                $sifre = $_POST["sifre"];

                $user = $this->checkauth($kuladi,$sifre);
                if (@$user["id"] == $id and @$user["berbermi"] == 1)
                {
                    $silinecekid = (int)$this->filtre($_POST["silinecekid"]);
                    $result = $this->db->delete($silinecekid);
                    if ($result)
                        echo json_encode(["mesaj" => "Silindi"]);
                    else echo json_encode(["mesaj" => "Silinemedi "]);
                }
                else echo json_encode(["mesaj" => "kullanici adı veya şifreyi tekrar giriniz"]);
            }
        }
    }

    public function gets(int $id =0)
    {
        if ($id != 0 and $id > 0)
        {
            $berber = $this->berberDb->getbykulid($id);
            $result = $this->db->gets((int)$berber["id"]);
            if ($result)
            {
                echo json_encode($result);
            }
            else echo json_encode(["mesaj" => "Bir tarifeniz yok "]);
            
        }
    }
}