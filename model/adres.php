<?php
include_once 'model.php';

class Dbadres extends model
{
    public function __construct()
    {
        parent::__construct();
    }


    public function insert($ilceFk,$detay)
    {
        $sorgu = $this->con->prepare("insert into adres (ilceFk,detay) values (?,?)");
        $sorgu->execute([$ilceFk,$detay]);
        if ($sorgu)
        {
            return $this->con->lastInsertId();
        }
        else{
            return false;
        }
    }

    public function delete($id)
    {
        $sorgu = $this->con->prepare("delete from adres where id = ?");
        $sorgu->execute([$id]);
    }

    public function get($adresFk)
    {
        $sorgu = $this->con->prepare("select * from adres a  left join ilceler i on a.ilceFK = i.ilce_no inner join iller il on i.il_no = il.il_no where id = ?");
        $sorgu->execute([$adresFk]);
        return $sorgu->fetch(PDO::FETCH_ASSOC);
    }


    public function __destruct()
    {
        parent::__destruct();
    }
}