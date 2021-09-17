<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css">
        <title>Mon super mini-blog</title>
    </head>

 <body>

 <em><a href="index.php">Retour à la liste des billets</a></em>

 <h1>Mon super-blog!</h1>


<?php
// Connection à la BDD
try
{
$bdd = new PDO('mysql:host=localhost;dbname=bloc_avec_commentaires;charset=utf8', 'root', '');
}
catch (Exception $e)
{
die('Erreur : ' . $e->getMessage());
}

// Récupération du billet sélectionné par l'utilisateur
$req = $bdd->prepare('SELECT id, titre, auteur, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin\') AS date_creation_fr FROM billets WHERE id = ?');
$req -> execute(array(htmlspecialchars($_GET['billet'])));

// Affichage du billet sélectionné par l'utilisateur
$donnees = $req->fetch();

if (empty($donnees)) // Si aucun id billet ne correspond au paramètre envoyé, on affiche un message d'erreur 
{
echo 'Aucun billet ne correspond au paramètre saisi!';
die();
}
else  // Un billet correspond bien au paramètre envoyé
{

?>
<div class="news">
<h3> <?php echo htmlspecialchars($donnees['titre']); ?> </h3>
<p> "<?php  echo nl2br(htmlspecialchars($donnees['contenu'])); ?>" <br />
Par "<?php echo htmlspecialchars($donnees['auteur']); ?>" le <?php echo ($donnees['date_creation_fr']); ?> <br /></p>
</div>

<?php
// Libération du curseur pour la prochaine requête 
$req->closeCursor(); 

// Récupération des commentaires déja rédigés
$req = $bdd->prepare('SELECT auteur, commentaire, DATE_FORMAT(date_commentaire, \'%d/%m/%Y à %Hh%imin\') AS date_commentaire_fr FROM commentaires WHERE id_billet = ? ORDER BY date_commentaire');
$req->execute(array($_GET['billet']));

// Affichage des commentaires déja rédigés
while ($donnees = $req->fetch())
{
?>
<h2>Commentaires sur cet hommage :</h2>
<p><strong><?php echo htmlspecialchars($donnees['auteur']); ?></strong> le <?php echo $donnees['date_commentaire_fr']; ?> :</p>
<p><?php echo nl2br(htmlspecialchars($donnees['commentaire'])); ?></p>
<?php
} 
}

// Fin de la boucle des commentaires
$req->closeCursor();
?>

<!-- Formulaire d'ajout d'un commentaire -->
<form method="post" action="commentaires_post.php?billet=<?php echo $_GET['billet']; ?>" class="form">
<label for="auteur">Votre pseudo:</label>
<textarea id="auteur" name="auteur" rows="1" cols="20" maxlength="255"> </textarea>
<label for="commentaire">Votre commentaire:</label>
<textarea id="commentaire" name="commentaire" rows="20" cols="80" maxlength="1500"> </textarea>
<input type="submit" value="Valider" />
 
</body>
</html>