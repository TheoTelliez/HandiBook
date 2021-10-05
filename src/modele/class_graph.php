<?php

class Graphique {

    private $db;
    private $select;

    public function __construct($db) {
        $this->db = $db;
        $this->select = $db->prepare("SELECT emailco, count(*) as nombredeco FROM connexions GROUP BY emailco");

    }

    public function select() {
        $liste = $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }
    

}

?>