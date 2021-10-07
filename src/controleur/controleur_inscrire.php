<?php

    function actionInscrire($twig, $db)
    {
        $form = array();
        if (isset($_POST['btInscrire'])) {
            $inputEmail = $_POST['inputEmail'];
            $inputPassword = $_POST['inputPassword'];
            $inputPassword2 = $_POST['inputPassword2'];
            $nom = $_POST['inputSurname'];
            $prenom = $_POST['inputName'];
            $telephone = $_POST['inputPhone'];
            $role = 2;
            $photo = NULL;
            $form['valide'] = true;

            if (isset($_FILES['photo'])) {
                if (!empty($_FILES['photo']['name'])) {
                    $extensions_ok = array('png', 'gif', 'jpg', 'jpeg');
                    $taille_max = 500000;
                    $dest_dossier = '/var/www/html/symfony4-4059/public/HandiBook/web/img/imgext/';
                    if (!in_array(substr(strrchr($_FILES['photo']['name'], '.'), 1), $extensions_ok)) {
                        echo 'Veuillez sélectionner un fichier de type png, gif ou jpg !';
                    } else {
                        if (file_exists($_FILES['photo']['tmp_name']) && (filesize($_FILES['photo']
                            ['tmp_name'])) > $taille_max) {
                            echo 'Votre fichier doit faire moins de 500Ko !';
                        } else {
                            $photo = basename($_FILES['photo']['name']);
                            $photo = strtr($photo, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                            $photo = preg_replace('/([^.a-z0-9]+)/i', '_', $photo);
                            move_uploaded_file($_FILES['photo']['tmp_name'], $dest_dossier . $photo);
                        }
                    }
                }
            }
            if ($inputPassword != $inputPassword2) {
                $form['valide'] = false;
                $form['message'] = 'Les mots de passe sont différents';
            } else {
                $utilisateur = new Utilisateur($db);
                $exec = $utilisateur->insert($inputEmail, password_hash($inputPassword,
                    PASSWORD_DEFAULT), $role, $nom, $prenom, $photo, $telephone);
                if (!$exec) {
                    $form['valide'] = false;
                    $form['message'] = 'Problème d\'insertion dans la table utilisateur ';
                }
            }
            $form['email'] = $inputEmail;
            $form['role'] = $role;
        }
        echo $twig->render('inscrire.html.twig', array('form' => $form));
    }