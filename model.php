<?php
// Connection à la BDD
    try
    {
        $bdd = new PDO('mysql:host=localhost;dbname=blog_avec_commentaires;charset=utf8', 'root', '');
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

// Création des variables pour la pagination
    $req = $bdd->query('SELECT COUNT(*) AS nb_billets FROM billets');
    $donnees = $req->fetch();
    $nombre_billet = $donnees['nb_billets'];
    $billets_par_page = 5;
    if(isset($_GET['page']) AND 0 < $_GET['page'] AND $_GET['page'] <=  $nombre_billet/$billets_par_page)
    { // accepte que si 0<page<nb_total_page
        $page_courante = $_GET['page'];
    }
    else
    {
        $page_courante = 1;
    } 
    
    $debut = ($page_courante-1)*$billets_par_page;

    // Libération du curseur pour la prochaine requête
    $req->closeCursor();  

    // Récupération des billets en fonction de la page sélectionnée
    $req = $bdd->prepare('SELECT id, titre, auteur, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin\') AS date_creation_fr FROM billets ORDER BY date_creation DESC LIMIT :debut, :billets');
    $req->bindValue(':debut', $debut, \PDO::PARAM_INT);
    $req->bindValue(':billets', $billets_par_page, \PDO::PARAM_INT);
    $req->execute();

