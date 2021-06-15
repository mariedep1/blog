<?php 

require_once "logique.php";

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/solar/bootstrap.css">
</head>
<body>

<?php require_once dirname(__FILE__)."/../navbar.php "?>

<?php if($isAdmin){?>
     <h3>Articles</h3>

     <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Titre</th>
      <th scope="col">Auteur</th>
      <th scope="col">Lien Article</th>
      <th scope="col">Publication</th>
      <th scope="col">Supprimer</th>
    </tr>
  </thead>
    <?php foreach($resultatDeMaRequeteArticleAdmin as $post){ ?>
  <tbody>
    <tr>
      <th scope="row"><?php echo $post['id'] ?></th>
      <td><?php echo $post['title']?></td>
      <td><?php echo $post['username']?></td>

      <td>
      <a href ="<?php echo $racineSite ?>/blog/postUnique.php?postId=<?php echo $post['id'] ?>" class="btn btn-success">Voir l'article</a>
      </td>
   
    <?php if( isset($post['published']) ){ 
        if(!$post['published']){ ?>
      <td>
        <form action="" method="post">
            <button type="submit" name="publishAdmin" value="<?php echo $post['id'] ?>"class="btn btn-info">Publier</button>
        </form>  </td>
    <?php }else{ ?>
        <td>
            <form action="" method="post">
                <button type="submit" name="unpublishAdmin" value="<?php echo $post['id'] ?>" class="btn btn-danger">Mettre en privé</button>
            </form>
        </td>
    <?php } } ?>

      <td>
        <form action="" method="post">
            <button type="submit" class="btn btn-warning" name="idSuppressionAdmin" value="<?php echo $post['id'] ?>">Supprimer</button>
        </form>
      </td>
    </tr>

    <?php } ?>

    </tbody>
</table>



<h3>Utilisateurs</h3>

<table class="table w-50">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">username</th>
      <th scope="col">Suppression</th>
      
    </tr>
  </thead>
  <tbody>
  
    </tr>
<?php foreach($resultatRequeteUtilisateur as $user){?>
  <tr>
      <th scope="row"><?php echo $user['id'] ?></th>
      <td><?php echo $user['username'] ?></td>
      <td>
        <form action="" method="post">
            <button type="submit" class="btn btn-warning" name="idSuppressionUserAdmin" value="<?php echo $user['id'] ?>">Supprimer</button>
        </form>
      </td>
      
    </tr>
<?php } ?>

</tbody>
</table>



<?php }else{ ?>
    <p>Passe ton chemin le gueux</p>
<?php } ?>


</body>
</html>

