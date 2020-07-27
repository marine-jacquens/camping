<?php

$page_selected = 'reservation';
require 'class/evenements.php';
$events = new Evenements();
/*if(!isset ($_GET['id'])){
  header('location:.php');
}*/
$event = $events->find($_GET['id'] ?? null);
$option = $events->option($_GET['id'] ?? null);
?>

<!DOCTYPE html>
<html>
<head>
    <title>camping - check reservation</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes"/>
    <link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/XzyCCqt/LOGO1.png">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <header>
      <?php include("includes/header.php");?>
    </header>
    <main>
      <section id="container-reservations">
        <h1> <img src="src/wave.png" alt="wave-icon-white"> VOS RÉSERVATIONS EN COURS <img src="src/wave.png" alt="wave-icon-white"></h1>
        <ul id="current-reservations">
          <li>Votre séjour aura lieu du <i><?= (new DateTime($event['date_debut']))->format('d/m/Y')?></i> jusqu'au <i><?= (new DateTime($event['date_fin']))->format('d/m/Y')?></i> inclus</li>
          <li> </li>
          <li>Lieu : <i><?= ($event['nom_lieu'])?></i></li>
          <li>vos emplacements : <i><?=($event['nb_emplacement'])?></i></li>
          <li>vos options : 
            <i><?php foreach($option as $opt){
                         $val = $opt['nom_option'];
                         echo $val.' &nbsp;';
                         }
           ?></i>
        </li>
          <li>prix total de la reservation : <i><?= ($event['prix_total'])?></i> Euros</li>
        </ul>
        <p>pour modifier votre réservation, veuillez <a  href="mailto:hello@sardinescamp.com">nous contacter</a>.</p>
      </section>
    </main>
<footer>
    <?php
    include("includes/footer.php"); ?>
</footer>
</body>
</html>
