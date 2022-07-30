<?php

    function actionReservation($twig, $db)
    {
        $form = array();
        if (isset($_POST['btSend'])) {
            $utilisateur = $_SESSION['login'];
            $lieu = $_POST['default-select'];
            $heure = $_POST['heure'];
            $date = $_POST['date'];
            $etat = "Réservation effectuée";
            $now = date("m/d/Y");

            if ($utilisateur == "") {
                $form['valide'] = false;
                $form['message'] = "L'utilisateur n'est pas défini";
            } else {
                if (date_create($date) < date_create($now)) {
                    $form['valide'] = false;
                    $form['message'] = "La date est inférieure à la date du jour";

                } else {
                    $form['valide'] = true;

                    include('/var/www/html/symfony4-4059/public/HandiBook/web/phpqrcode/qrlib.php');

                    $uniqid = uniqid();
                    $lien = 'http://serveur1.arras-sio.com/symfony4-4059/HandiBook/web/index.php?page=validcode&uniqid=' . $uniqid;
                    $fileName = $uniqid . '.jpg';
                    $form['qrcode'] = $fileName;
                    $pngAbsoluteFilePath = '/var/www/html/symfony4-4059/public/HandiBook/web/img/qrcodes/' . $fileName;
                    QRcode::png($lien, $pngAbsoluteFilePath);

                    $resa = new Resa($db);
                    $exec = $resa->insert($utilisateur, $date, $heure, $lieu, $etat, $fileName, $uniqid);

                    if (!$exec) {
                        $form['valide'] = false;
                        $form['message'] = 'Problème d\'insertion dans la table ';
                    }
                }
            }
        }
        echo $twig->render('reservation.html.twig', array('form' => $form));

    }

    function actionListeArrivees($twig, $db)
    {
        $form = array();
        $resa = new Resa($db);
        $liste = $resa->select();
        echo $twig->render('listearrivees.html.twig', array('form' => $form, 'liste' => $liste));
    }

    function actionValidCode($twig, $db)
    {
        if (isset($_GET['uniqid'])) {
            $resa = new Resa($db);
            $res = $resa->selectByUniq($_GET['uniqid']);
            if($res["date"] != date("m/d/Y")){
                echo("Erreur de validation. Veuillez vérifier la date de votre réservation ou en effectuer une autre");
            }else{
                $resa->update($_GET['uniqid']);
                header("Location: http://serveur1.arras-sio.com/symfony4-4059/HandiBook/web/index.php");
                die();
            }
        }
    }