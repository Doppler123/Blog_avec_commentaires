<?php 
// Connection à la BDD
    try
    {
	    $bdd = new PDO('mysql:host=localhost;dbname=blog_avec_commentaires;charset=utf8', 'root', '');
    }
    catch(Exception $e)
    {
        die('Erreur : '.$e->getMessage());
    }
// Affichage d'un message d'erreur si pas de contenu renseigné
    if (!strlen(trim($_POST['contenu']))) 
    {
        echo 'Tu n\'a rien écrit! Si tu veux faire un hommage, il faut écrire dans la zone de texte puis valider.<br /> <a href="index.php"> Faire un nouvel essai </a>';
    }

// Sinon, ajout de la nouvelle entrée dans la BDD puis retour à l'affichage des billets
    else 
    {
        $req = $bdd->prepare('INSERT INTO billets (titre, auteur, contenu) VALUES(?, ?, ?)');
        $req->execute(array($_POST['titre'], $_POST['auteur'], $_POST['contenu']));
        header('Location: index.php');
    }


