<?php

function actionAccueil($twig, $db) {
    echo $twig->render('index.html.twig', array());
}



?>