<?php

function getPage($db) {
// Inscrire vos contrôleurs ici
    $lesPages['accueil'] = "actionAccueil;0";

    if ($db != null) {
        if (isset($_GET['page'])) {
// Nous mettons dans la variable $page, la valeur qui a été passée dans le lien
            $page = $_GET['page'];
        } else {
// S'il n'y a rien en mémoire, nous lui donnons la valeur « accueil » afin de lui afficher une page
//par défaut
            $page = 'accueil';
        }
        if (!isset($lesPages[$page])) {
// Nous rentrons ici si cela n'existe pas, ainsi nous redirigeons l'utilisateur sur la page d'accueil
            $page = 'accueil';
        }

        $explose = explode(";", $lesPages[$page]);
        $role = $explose[1];
        if ($role != 0) {
            if (isset($_SESSION['login'])) {
                if (isset($_SESSION['role'])) {
                    if ($role != $_SESSION['role']) {
                        $contenu = 'actionAccueil';
                    } else {
                        $contenu = $explose[0];
                    }
                } else {
                    $contenu = 'actionAccueil';
                }
            } else {
                $contenu = 'actionAccueil';
            }
        } else {
            $contenu = $explose[0];
        }
    } else {
// Si $db est null
        $contenu = 'actionAccueil';
    }
// La fonction envoie le contenu
    return $contenu;
}

?>