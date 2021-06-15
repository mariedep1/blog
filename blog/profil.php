<?php require_once "logique.php" ?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://bootswatch.com/5/solar/bootstrap.css">
    <title>Profil</title>
</head>
<body>
    <?php require_once dirname(__FILE__)."/../navbar.php "; ?>

    <?php if( isset($_GET['info']) && $_GET['info'] == 'modified' ){?>

    <div class="alert alert-dismissible alert-success">
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      Votre profil a été édité.
    </div>
    <?php } ?>

    <?php if( isset($_GET['info']) && $_GET['info'] == 'denied' ){?>
        <div class="alert alert-dismissible alert-danger">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        Vous ne pouvez pas édité ce profil</a>.
        </div>
    <?php } ?>
    <?php if( isset($_GET['info']) && $_GET['info'] == 'uploaded' ){?>
        <div class="alert alert-dismissible alert-success">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        Photo de profil a été changé.
        </div>
    <?php } ?>

    <?php if( isset($_GET['info']) && $_GET['info'] == 'uploadFailed' ){?>
        <div class="alert alert-dismissible alert-danger">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        Le changement a échoué.
        </div>
    <?php } ?>

    <?php if( isset($_GET['info']) && $_GET['info'] == 'resolution' ){?>
        <div class="alert alert-dismissible alert-warning">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        Veuillez choisir une image avec une largeur de 900px et une hauteur de 720px maximum.  </a>.
        </div>
    <?php } ?>

    <?php if( isset($_GET['info']) && $_GET['info'] == 'oversized' ){?>
        <div class="alert alert-dismissible alert-warning">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        Votre image dépasse 3Mo. Veuillez choisir une image plus légère  </a>.
        </div>
    <?php } ?>
    <?php if( isset($_GET['info']) && $_GET['info'] == 'extension' ){?>
        <div class="alert alert-dismissible alert-warning">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        Veuillez choisir une image au format jpg, jpeg ou png.   </a>.
        </div>
    <?php } ?>

    <?php foreach($resultatRequeteUtilisateur as $user){?>

    <h2>Profil de <?php echo $user['displayname'];  ?></h2>

  
        <ul class="list-unstyled">
        <li class='mt-3'><img src="images/profiles/<?php echo $user['image']?>" alt=""></li>
        <li class='mt-3'>Nom de l'utilisateur : <?php if($user['displayname']==""){
            echo "Cet utilisateur n'a pas de displayname"; }else{
                echo $user['displayname'];
            } ?></li>
        <li>Email: <?php if($user['email']==""){
            echo "Cet utilisateur n'a pas d'email"; 
            }else{
                echo $user['email'];
            } ?></li>
        </ul>

    <?php } ?>

    <div class="row justify-content-center">
      <?php if($isLoggedIn && $isUser){?>
        <form action="modificationProfil.php" method="POST" class="col-2 text-center">
          <button type="submit" name="userId" value="<?php echo $_SESSION['userId']?>" class="btn btn-primary">Modifier mon profil</button>
        </form>
      <?php }?>

    <div class="text-center" >
            <a href="/correction/blog2" class="btn btn-danger">Retour a l'accueil</a>
    </div>
    
</body>
</html>