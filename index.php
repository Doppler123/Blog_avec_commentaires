<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css">
        <title>Mon super mini-blog</title>
    </head>

 <body>

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

// Affichage des billets avec une boucle
while ($donnees = $req->fetch())
{
?>
<div class="news">
<h3> <?php echo htmlspecialchars($donnees['titre']); ?> </h3>
<p> "<?php  echo nl2br(htmlspecialchars($donnees['contenu'])); ?>" <br />
Par "<?php echo htmlspecialchars($donnees['auteur']); ?>" le <?php echo ($donnees['date_creation_fr']); ?> <br />
<em><a href="commentaires.php?billet=<?php echo $donnees['id']; ?>">Commentaires</a></em> </p>
</div>
<?php
}

// Fin de la boucle d'affichage des billets
$req->closeCursor(); 
?>

<!-- Pagination -->
<p>Page :
<?php
for($i = 1 ; $i <= $nombre_billet/$billets_par_page ; $i++){
if($i == $page_courante){
echo $i;
}
else
{
echo '<a href=index.php?page='.$i.'>'.$i.'</a>';
}
}   
?>
</p>

<!-- Formulaire d'ajout d'un billet -->
<form action="index_post.php" method="post">
<label for="auteur">Votre pseudo:</label>
<textarea id="auteur" name="auteur" rows="1" cols="20" maxlength="255"> </textarea>
<label for="titre">Votre titre:</label> 
<textarea id="titre" name="titre" rows="1" cols="20" maxlength="255"> </textarea> <br />
<label for="contenu">Votre commentaire:</label>
<textarea id="contenu" name="contenu" rows="20" cols="80" maxlength="1500"> </textarea>
<input type="submit" value="Valider" />

</body>
</html>

