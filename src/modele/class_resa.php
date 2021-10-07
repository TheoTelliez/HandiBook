<?php

class Resa {

    private $db;    // déclaration de la variable en privé (uniquement pour la classe) // $db c'est la variable de connection
    private $insert;
    private $select;
    private $update;
    private $selectByUniq;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("insert into reservation(emailResa, date, heure, lieu, etat, qrcode, uniqid) values (:emailResa, :date, :heure, :lieu, :etat, :qrcode, :uniqid)");
        $this->select = $db->prepare("select id, date, heure, lieu, emailResa, etat, qrcode, uniqid from reservation order by date");
        $this->update = $db->prepare("update reservation set etat='Arrivé' where uniqid=:uniqid");
        $this->selectByUniq = $db->prepare("select emailResa, date, heure, lieu, etat, qrcode, uniqid from reservation where uniqid=:uniqid order by date");
    }

    public function insert($utilisateur, $date, $heure, $lieu, $etat, $qrcode, $uniqid) {
        $r = true;
        $this->insert->execute(array(':emailResa' => $utilisateur, ':date' => $date, ':heure' => $heure, ':lieu' => $lieu, ':etat' => $etat, ':qrcode' => $qrcode, ':uniqid' => $uniqid));  //on exécute les requètes préparés dans le prepare et on affecte les valeurs SQL aux valeurs du formulaire. ATTENTION à l'ordre et à la position !!
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function select() {
        $liste = $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function selectByUniq($uniqid) {
        $liste = $this->selectByUniq->execute(array(':uniqid' => $uniqid));
        if ($this->selectByUniq->errorCode() != 0) {
            print_r($this->selectByUniq->errorInfo());
        }
        return $this->selectByUniq->fetch();
    }

    public function update($uniqid) {
        $r = true;
        $this->update->execute(array(':uniqid' => $uniqid));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }



}

?>