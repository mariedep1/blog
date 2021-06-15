<?php require_once "logique.php"?>

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

<?php if( isset($_GET['info']) && $_GET['info'] == 'edited' ){?>

<div class="alert alert-dismissible alert-success">
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  <strong>Well done!</strong> You successfully edited <a href="#" class="alert-link">this article</a>.
</div>
<?php } ?>

<?php if( isset($_GET['info']) && $_GET['info'] == 'pasLeDroit' ){?>

<div class="alert alert-dismissible alert-danger">
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  Vous n'avez pas le droit de modifier l'article.
</div>
<?php } ?>

<?php if( isset($_GET['info']) && $_GET['info'] == 'commentadded' ){?>

<div class="alert alert-dismissible alert-success">
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  Votre commentaire a bien été ajouté.
</div>
<?php } ?>

<?php if( isset($_GET['info']) && $_GET['info'] == 'errorcomment' ){?>

<div class="alert alert-dismissible alert-danger">
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  Votre commentaire n'a pas pu être ajouté.
</div>
<?php } ?>
<?php if( isset($_GET['info']) && $_GET['info'] == 'commentdenied' ){?>

<div class="alert alert-dismissible alert-danger">
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  Vous ne pouvez pas commenter.
</div>
<?php } ?>

<?php if( isset($_GET['info']) && $_GET['info'] == 'publishedError' ){?>

<div class="alert alert-dismissible alert-danger">
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  Une erreur est survenu. Le poste n'a pas pu être publié.
</div>
<?php } ?>

<?php if( isset($_GET['info']) && $_GET['info'] == 'published' ){?>

<div class="alert alert-dismissible alert-success">
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  Votre article a été publié.
</div>
<?php } ?>

<?php if( isset($_GET['info']) && $_GET['info'] == 'unPublishedError' ){?>

<div class="alert alert-dismissible alert-danger">
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  Une erreur est survenue. Le poste n'a pas pu être passé en privé.
</div>
<?php } ?>

<?php if( isset($_GET['info']) && $_GET['info'] == 'unpublished' ){?>

<div class="alert alert-dismissible alert-danger">
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  Votre poste est en mode privé. Vous êtes le seul à pouvoir le voir. 
</div>
<?php } ?>

    <div class="container mt-5">


        <?php foreach($leResultatDeMaRequeteArticleUnique as $value){?>
                  
                  <div class="row text-center">
                  
                    <h2><?php echo $value["title"];?></h2>
                  
                  <img src="images/posts/<?php echo $value['image'] ?>" alt="">
                  
                  </div>
                  
                  <div class="text-center">
                      <p><?php echo $value['content'];?></p>
                  </div>              
   

      <div class="row justify-content-center">
        <?php if($isLoggedIn && $ownership){?>
          <form action="edition.php" method="post" class="col-2 text-center">
            <button type="submit" name="postId" value="<?php echo $value['id']?>" class="btn btn-primary">Modifier l'article</button>
          </form>

          <form action="" method="post" class="col-2 text-center ">
            <input type="hidden" name="userId" value="<?php echo $_SESSION['userId']?>">
            <?php if($value['published']){ ?> 
              <button type="submit" name="unpublish" class="btn btn-danger " value="<?php echo $value['id']?>">Mettre en privé</button>
              
            <?php }else{?>
              <button type="submit" name="publish" value="<?php echo $value['id']?>" class="btn btn-success ">Publier</button>
            <?php } ?>

          </form>

            <div class="text-center col-2" >
              <a href="/correction/blog2" class="btn btn-danger">Retour a l'accueil</a>
            </div>
      </div>

 <?php }?>

      <?php if($isLoggedIn){?>
    <div class="row">
          <form action="" method="post">
          <div class="form-group">
             <input type="text" name="comment"  class="form-control" placeholder="Votre commentaire">
             <input type="hidden" name="idPostComment" value="<?php echo $postId?>">
             <input type="hidden" name="authorId" value="<?php echo $_SESSION['userId'] ?>">
          </div>
       
          <div class="form-group">
                      <button type="submit" class="btn btn-success">Poster le commentaire</button>

          </div>
          </form> 
    </div>
    <?php }?>

   

      <div class="row">
          <?php foreach($mesCommentaires as $comment){ ?>
              <div class="col-8">
              <hr>
                <h4><?php if($comment['displayname']==""){
                  echo $comment['username'];
                }else{
                  echo $comment['displayname'];

                } ?></h4>
                <hr>
                  
                <p><?php echo $comment['content']; ?></p>
                <hr>
              </div>
      
          <?php } ?>
      </div>
    </div>
        <?php }?>
      
      </div>

      

    
    
    
    
    
    
    
</body>
</html>