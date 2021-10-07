<?php

class Utilisateur {

    private $db;    // déclaration de la variable en privé (uniquement pour la classe) // $db c'est la variable de connection
    private $insert;
    private $connect;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("insert into utilisateur(email, mdp, nom, prenom, idRole, photo, telephone) values (:email, :mdp, :nom, :prenom, :role, :photo, :telephone)");
        $this->connect = $db->prepare("select email, idRole, mdp from utilisateur where email=:email");
    }

    public function insert($email, $mdp, $role, $nom, $prenom, $photo, $telephone) {
        $r = true;
        $this->insert->execute(array(':email' => $email, ':mdp' => $mdp, ':role' => $role, ':nom' => $nom, ':prenom' => $prenom, ':photo' => $photo, ':telephone' => $telephone));  //on exécute les requètes préparés dans le prepare et on affecte les valeurs SQL aux valeurs du formulaire. ATTENTION à l'ordre et à la position !!
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function connect($email) {
        $unUtilisateur = $this->connect->execute(array(':email' => $email));
        if ($this->connect->errorCode() != 0) {
            print_r($this->connect->errorInfo());
        }
        return $this->connect->fetch();
    }



}

?>