<?php
include_once "model.php";


class Dbiller extends model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function gets()
    {
        $sorgu = $this->con->prepare("select * from iller");
        $sorgu->execute();
        $kayit = $sorgu->fetchAll(PDO::FETCH_ASSOC);
        if ($kayit > 0)
        {
            return $kayit;
        }
    }

    public function get($id)
    {
        $sorgu = $this->con->prepare("select * from iller where il_no = ?");
        $sorgu->execute([$id]);
        $veri = $sorgu->fetch(PDO::FETCH_ASSOC);
        if ($veri > 0)
        {
            return $veri;
        }
    }
}