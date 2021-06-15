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
    <?php require_once dirname(__FILE__)."/../navbar.php ";

    foreach($resultatRequeteUtilisateur as $user){?>

    <h2>Modification de votre Profil</h2>

    <img src="images/profiles/<?php echo $user['image']?>" alt="">


    <form action="" method="post" enctype="multipart/form-data">

        <input type="file" name="pictureToUpload" id="">
        <input type="hidden" name="profilePic" value="upload">
        <input type="hidden" name="userId" value="<?php echo $user['id'] ?>">
        <button type="submit" class="btn btn-success">Changer la photo</button>

    </form>


    <p>Indentifiant: <?php echo $user['username'];  ?></p>

    <form action="" method="POST">

    <input type="hidden" name="userAModifier" value="<?php echo $user['id'] ?>">
    

    <input class="form-control" type="text" name="displaynameProfil" id="" value="<?php echo $user['displayname'] ?>" placeholder="votre pseudo">
    <input class="form-control" type="email" name="emailProfil" id="" value="<?php echo $user['email'] ?>" placeholder='email'>
   
    <input class="form-control btn btn-success" type="submit" value="Enregistrer les modifications">
    


</form>

    <?php } ?>
    <div class="text-center" >
            <a href="/correction/blog2" class="btn btn-danger">Retour a l'accueil</a>
    </div>
    
</body>
</html>