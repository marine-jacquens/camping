<?php
ob_start();
$page_selected = 'gestion_reservation';
?>

<!DOCTYPE html>
<html>

<head>
    <title>camping - gestion_reservation</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/XzyCCqt/LOGO1.png">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <header>
        <?php 
        include("includes/header.php"); 
        require 'class/reservation_form.php';
        $modif_reservation = new reservation($db);
        $connexion = $db->connectDb();
        $infos = new camping_properties($db);
        ?>
    </header>
    <main class="main_gestion_reservation">
        <?php
        try
        {
            //CONNEXION BDD
            $connexion1=new PDO("mysql:host=localhost;dbname=camping",'root','');
            // DEFINITION MODE D'ERREUR PDO SUR EXCEPTION
            $connexion1->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            
            //VARIABLE STOCKANT L'ID DE RESERVATION
            $id_booking=$_GET['id_reservation'];
            
            
            //RECUPERATION DES INFORMATIONS LIEES A LA RESERVATION
            $info_customer = $connexion1->prepare ("SELECT 
            reservations.id_utilisateur,
            utilisateurs.id_utilisateur, 
            utilisateurs.nom,
            utilisateurs.prenom,
            utilisateurs.num_tel,
            utilisateurs.email
            FROM 
            reservations,
            utilisateurs 
            WHERE reservations.id_reservation = $id_booking
            AND 
            reservations.id_utilisateur = utilisateurs.id_utilisateur
            ");
            //EXECUTION REQUETE
            $info_customer->execute();
            //RECUPERATION RESULTAT
            $resultat_info_customer = $info_customer->fetchAll(PDO::FETCH_ASSOC);
            
            
            //RECUPERATION DES INFORMATIONS LIEES A LA RESERVATION
            $info_reservation = $connexion1->prepare ("SELECT * FROM reservations WHERE id_reservation = $id_booking");
            //EXECUTION REQUETE
            $info_reservation->execute();
            //RECUPERATION RESULTAT
            $resultat_info_reservation = $info_reservation->fetchAll(PDO::FETCH_ASSOC);

            //RECUPERATION DES INFORMATIONS LIEES A LA RESERVATION
            $info_lieu = $connexion1->prepare ("SELECT * FROM detail_lieux WHERE id_reservation = $id_booking");
            //EXECUTION REQUETE
            $info_lieu->execute();
            //RECUPERATION RESULTAT
            $resultat_info_lieu = $info_lieu->fetchAll(PDO::FETCH_ASSOC);
            
            //RECUPERATION DES INFORMATIONS LIEES A LA RESERVATION
            $info_type = $connexion1->prepare ("SELECT * FROM  detail_types_emplacement WHERE id_reservation = $id_booking");
            //EXECUTION REQUETE
            $info_type->execute();
            //RECUPERATION RESULTAT
            $resultat_info_type = $info_type->fetchAll(PDO::FETCH_ASSOC);
            
            //RECUPERATION DES INFORMATIONS LIEES A LA RESERVATION
            $info_option = $connexion1->prepare ("SELECT * FROM  detail_options WHERE id_reservation = $id_booking");
            //EXECUTION REQUETE
            $info_option->execute();
            //RECUPERATION RESULTAT
            $resultat_info_option = $info_option->fetchAll(PDO::FETCH_ASSOC);
            
            //RECUPERATION DES INFORMATIONS LIEES A LA RESERVATION
            $info_prix = $connexion1->prepare ("SELECT * FROM  prix_detail WHERE id_reservation = $id_booking");
            //EXECUTION REQUETE
            $info_prix->execute();
            //RECUPERATION RESULTAT
            $resultat_info_prix = $info_prix->fetchAll(PDO::FETCH_ASSOC);
        
        }
        
        
        
        catch (PDOException $e) 
        {
        echo "Erreur : " . $e->getMessage();
        }

        ?>
        
        <section class="booking_details">
            <div class="reservation_facture">
           
            <h1>Facture réservation n°<?php echo $resultat_info_reservation[0]['id_reservation'] ?></h1><br/>
            
            <h3>Sardine's Camp</h3><br/>
            <address>
                <p>1 avenue de la Madrague 13008 Marseille</p>
                <a class="contact" href="tel:+330491919191">"tel:+330491919191"</a><br/>
                <a class="contact" href="mailto:hello@sardinescamp.com">hello@sardinescamp.com</a>
            </address>

            <br/>
                
            <h3> Informations client </h3><br/>
            <dl>
                <dt>Id utilisateur</dt>
                <dd><?php echo  $resultat_info_customer[0]['id_utilisateur'] ?></dd>
                
                <dt>Nom et Prénom</dt>
                <dd><?php echo  $resultat_info_customer[0]['prenom'].' '.$resultat_info_customer[0]['nom'] ?></dd>
                
                <dt>Numéro de téléphone</dt>
                <dd><?php echo  $resultat_info_customer[0]['num_tel'] ?></dd>
                
                <dt>Mail</dt>
                <dd><?php echo  $resultat_info_customer[0]['email'] ?></dd>
            </dl>
            
            <br/>
            
            <h3> Récapitulatif du séjour</h3><br/>
            <dl>
                <dt>Date de séjour</dt>
                <dd>Arrivée le : <?php echo $resultat_info_reservation[0]['date_debut'] ?> _ Départ le : <?php echo $resultat_info_reservation[0]['date_fin'] ?></dd><br/>
                
                <dt>Site réservé et tarif journalier</dt>
                <dd>
                    <?php foreach($resultat_info_lieu as $info_lieu){echo $info_lieu['nom_lieu'].' _ '. $info_lieu['prix_journalier'].'€/j (1 emplacement)';}?>
                </dd><br/>
                
                <dt>Type(s) d'emplacement réservé(s)</dt>
                <dd>
                    <?php
                    foreach($resultat_info_type as $info_type)
                    {
                        echo $info_type['nom_type_emplacement'].' ('.
                        $info_type['nb_emplacements_reserves'].' emplacement(s) réservé(s) ) <br/>';
                    }
                    ?>
                </dd><br/>
                
                <dt>Option(s) sélectionnée(s) et tarif</dt>
                <dd>
                    <?php 
                         foreach($resultat_info_option as $info_option)
                        {
                            echo $info_option['nom_option'] .' '.
                            $info_option['prix_option'].' €/j <br/>';
                        }
                    ?>
                </dd>
            </dl>
            
            <br/>
            
            <h3>TOTAL</h3><br/>
            <dl>
                <dt>Total emplacement(s) réservé(s)</dt>
                <dd><?php echo $resultat_info_prix[0]['nb_emplacement'] ?></dd>
                <dt>Total tarif journalier emplacement(s)</dt>
                <dd><?php echo $resultat_info_prix[0]['prix_journalier'].' €' ?></dd>
                <dt>Total tarif journalier option(s) </dt>
                <dd><?php echo $resultat_info_prix[0]['prix_options'] .' €'?></dd>
                <dt>Total journée(s) réservée(s)</dt>
                <dd><?php echo $resultat_info_prix[0]['nb_jours'] ?></dd>
                <dt>Total coût séjour</dt>
                <dd id="total"><?php echo $resultat_info_prix[0]['prix_total'].' €' ?></dd>
            </dl>
            
            
            <button onclick="myFunction()"  class="button_print"><i class="fas fa-print"></i></button>
          
    
            <script>
            function myFunction() {
                window.print();
            }
            </script>
            
           
            
             
            
             </div>
        </section>
        
        
    </main>
    <footer>
        <?php include("includes/footer.php") ?>
    </footer>
</body>