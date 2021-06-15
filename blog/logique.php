<?php 

session_start();
if(isset($_POST['logOut'])){
   session_unset();
} 

$racineSite = "http://localhost/correction/blog2";

require_once dirname(__FILE__)."/../authentification/auth.php";
require_once dirname(__FILE__)."/../access/db.php";

$isUser = false; 


//admin 

if( $isAdmin){
   $maRequeteArticleAdmin = "SELECT posts.id, posts.title, posts.authorid, posts.published, users.username FROM posts INNER JOIN users On  users.id = posts.authorid";

   $resultatDeMaRequeteArticleAdmin = mysqli_query($maConnection, $maRequeteArticleAdmin);
}

   //publication
if( isset($_POST['publishAdmin']) && $_POST['publishAdmin'] !="" ){

   $postId = $_POST['publishAdmin']; 

   if( $isAdmin) {

   $maRequetePublicationAdmin = "UPDATE posts SET published = 1 WHERE id ='$postId'";
   $resultatRequete = mysqli_query($maConnection, $maRequetePublicationAdmin);

      if($resultatRequete){
         header("Location: admin.php");
      }

   }
}
   //article en prive
if( isset($_POST['unpublishAdmin']) && $_POST['unpublishAdmin'] !="" ){

   $postId = $_POST['unpublishAdmin']; 

   if( $isAdmin) {

   $maRequetePublication = "UPDATE posts SET published = 0 WHERE id ='$postId'";
   $resultatRequete = mysqli_query($maConnection, $maRequetePublication);
   if($resultatRequete){
      header("Location: admin.php");
   }
   }
}
   //supprimer un article

   
   if(isset($_POST['idSuppressionAdmin'])){

      $idASupprimer = $_POST['idSuppressionAdmin'];
   
      if( $isAdmin ){
      $maRequeteDeSuppression = "DELETE FROM posts WHERE id=$idASupprimer";

      $maSuppression= mysqli_query($maConnection, $maRequeteDeSuppression);

      header("Location: admin.php");

      }    

    }


    if( $isAdmin){
       $maRequeteUtilisateurs = "SELECT * FROM users"; 
       $resultatRequeteUtilisateur = mysqli_query($maConnection, $maRequeteUtilisateurs); 
    }



    if(isset($_POST['idSuppressionUserAdmin'])){

      $idUserASupprimer = $_POST['idSuppressionUserAdmin'];
   
      if( $isAdmin ){
      $maRequeteDeSuppressionUser = "DELETE FROM users WHERE id=$idUserASupprimer";

      $maSuppression= mysqli_query($maConnection, $maRequeteDeSuppressionUser);

      header("Location: admin.php");

      }    

    }


//publication 


   if( isset($_POST['publish']) && $_POST['publish'] !="" ){

      $postId = $_POST['publish']; 
      $userId = $_POST['userId']; 

      if($isLoggedIn && $userId == $_SESSION['userId'] && verifyOwnership($userId, $postId, $maConnection)) {

      $maRequetePublication = "UPDATE posts SET published = 1 WHERE id ='$postId'";
      $resultatRequete = mysqli_query($maConnection, $maRequetePublication);
         if($resultatRequete){
            header("Location: postUnique.php?postId=$postId&info=published");
         } else {
            header("Location: postUnique.php?postId=$postId&info=publishedError");
         }
      }
   }
   
if( isset($_POST['unpublish']) && $_POST['unpublish'] !="" ) {
      $userId = $_POST['userId']; 
      $postId = $_POST['unpublish']; 

      if($isLoggedIn && $userId == $_SESSION['userId'] && verifyOwnership($userId, $postId, $maConnection)) {

      $maRequetePublication = "UPDATE posts SET published = 0 WHERE id ='$postId'";
      $resultatRequete = mysqli_query($maConnection, $maRequetePublication);
         if($resultatRequete){
            header("Location: postUnique.php?postId=$postId&info=unpublished");
         } else {
            header("Location: postUnique.php?postId=$postId&info=unPublishedError");
         }
      }
   }

   //formulaire ajout de commentaire 

if($isLoggedIn){
   if(isset($_POST['comment']) && $_POST['comment']!=""){
      $postId = $_POST['idPostComment']; 
      $comment = $_POST['comment'];
      $authorId = $_POST['authorId']; 
      if($_SESSION['userId'] == $authorId && $comment != ""){
         $maRequeteAjoutComment= "INSERT INTO comments(content, author_id, post_id) VALUES ('$comment', '$authorId', '$postId')"; 
         $resultatRequeteAjoutComment = mysqli_query($maConnection, $maRequeteAjoutComment); 
         if($resultatRequeteAjoutComment){
            header("Location: postUnique.php?postId=$postId&info=commentadded");
         }else{
            header("Location: postUnique.php?postId=$postId&info=errorcomment");

         }
      }else{
         header("Location: postUnique.php?postId=$postId&info=commentdenied");
      }
   }
}


   //Formulaire de la photo de profil 

   if( isset($_POST['profilePic']) && $_POST['profilePic'] == "upload"){
      if(isset($_FILES['pictureToUpload']['name'])){

         if($_SESSION['userId'] = $_POST['userId']){

            $userId = $_POST['userId'];


         $extensionsAutorisees=array('jpeg', 'jpg', 'png'); 
         $hauteurMax = 720;
         $largeurMax = 900; 
         $tailleMax = 3000000;

            
          $uploaddir ="images/profiles/"; 
  
          $mesInfos = getimagesize($_FILES['pictureToUpload']['tmp_name']);
          $monTableauExtension = explode("/", $mesInfos['mime']);
          $monExtension = $monTableauExtension[1];
  
  
          
          $nomTemporaire = $_FILES['pictureToUpload']['tmp_name'];
  
          $tableauTmpName= explode("\\", $nomTemporaire);
   
          $nomFichier = end($tableauTmpName);
          
          $nomFinalDuFichier = $nomFichier.".".$monExtension;
          $destinationFinal = $uploaddir.$nomFinalDuFichier;
  
  
          $largeurFichier = $mesInfos[0];
          $hauteurFichier = $mesInfos[1];
          $tailleFichier = $_FILES['pictureToUpload']['size'];
          
           if(in_array($monExtension, $extensionsAutorisees)){
                  //on vérifie les extensions
                  echo 'Extension correcte';
              if($tailleFichier <= $tailleMax){
                   //on verifie la taille du fichier
                  echo "largeur hauteur correct"; 
                  if($largeurFichier <= $largeurMax && $hauteurFichier <= $hauteurMax){
                          //on verifie la largeur et la hauteur
                      if(move_uploaded_file($nomTemporaire, $destinationFinal)){
                              //on effectue l'upload
                             
                              $maRequeteUploadImage = "UPDATE users SET image ='$nomFinalDuFichier' WHERE id = '$userId' ";
                              $resultatRequeteUploadImage = mysqli_query($maConnection, $maRequeteUploadImage);
                              if($resultatRequeteUploadImage){
                                 header("Location: profil.php?userId=$userId&info=uploaded");
                              }else{
                                 die(mysqli_error($maConnection));
                              }
                      }else{
                        header("Location: profil.php?userId=$userId&info=uploadFailed");
                      }
                  }else {
                     header("Location: profil.php?userId=$userId&info=resolution");
                  }
  
              } else{
               header("Location: profil.php?userId=$userId&info=oversized");       
              }
          }else{
            header("Location: profil.php?userId=$userId&info=extension");
               
          }







         }
         
          
      }
  
  }



     //Requete pour les profils
     if( (isset($_GET['userId']) && $_GET['userId'])|| (isset($_POST['userId']) && $_POST['userId']!="") ){

      if(isset($_GET['userId'])){
         $userId = $_GET['userId'];
         $maRequeteUtilisateur = "SELECT  username, displayname, email, image FROM users where id='$userId'";
      }else{
         $userId = $_POST['userId'];
         $maRequeteUtilisateur = "SELECT id,username, displayname, email, image FROM users where id='$userId'";

      }
      
         $resultatRequeteUtilisateur = mysqli_query($maConnection, $maRequeteUtilisateur);

         if($isLoggedIn && $_SESSION['userId'] == $userId){

            $isUser = true;
         }
     }

 // modification d'un profil
 if(isset($_POST['displaynameProfil']) && isset($_POST['emailProfil'])){
         
   $idUserAModifier = $_POST['userAModifier'];
   
   if( $_SESSION['userId'] == $idUserAModifier ){
    $emailEdite = $_POST['emailProfil'];
    
    $displayNameEdite = $_POST['displaynameProfil'];

      $maRequeteUpdateUser = "UPDATE users SET displayname  = '$displayNameEdite', email = '$emailEdite' WHERE id = $idUserAModifier";

      $monResultatUpdate = mysqli_query($maConnection, $maRequeteUpdateUser);
      if(!$monResultatUpdate){
         die(mysqli_error($maConnection));
      }else{
        header("Location: profil.php?userId=$idUserAModifier&info=modified");
      } 

   }else{

      die("vous n'avez pas le droit de modifier ce profil");

   }
 }





   //upload image de post
   if( isset($_POST['postPic']) && $_POST['postPic'] == 'upload'){

      if (    isset($_FILES['postPictureToUpload']['name']   )        ){
            if($_SESSION['userId']== $_POST['authorId']){
               $postId = $_POST['postId']; 
               $extensionsAutorisees = array("jpeg", "jpg", "png");
   
               $hauteurMax = 720;
               $largeurMax = 900;
           
               $tailleMax = 3000000;
                        
               $repertoireUpload = "images/posts/";
            
            $nomTemporaireFichier = $_FILES['postPictureToUpload']['tmp_name'];
     
   
           $mesInfos = getimagesize($_FILES['postPictureToUpload']['tmp_name']);
   
           $monTableauExtensions = explode("/",$mesInfos['mime']); 
            $extensionUploadee = $monTableauExtensions[1];
   
          $unTableau =    explode("\\", $nomTemporaireFichier);
   
            $nomTemporaireSansChemin =  end($unTableau);
                                                   
            $nomFinalDuFichier = $nomTemporaireSansChemin.".".$extensionUploadee;
            
            $destinationFinale = $repertoireUpload.$nomFinalDuFichier;
   
             $maLargeur = $mesInfos[0];
            $maHauteur = $mesInfos[1];
            
            $maTaille = $_FILES['postPictureToUpload']['size'];
   
   
            if( in_array($extensionUploadee, $extensionsAutorisees) ){
   
                if($maTaille <= $tailleMax){
   
                    if($maLargeur <= $largeurMax && $maHauteur <= $hauteurMax){
   
                                if(move_uploaded_file($nomTemporaireFichier, $destinationFinale)){
   
   
                                        $requeteUploadPhotoProfile = "UPDATE posts SET image = '$nomFinalDuFichier' WHERE id = '$postId'";
                                          $resultatRequete = mysqli_query($maConnection, $requeteUploadPhotoProfile);
                                       if($resultatRequete){
                                          header("Location: postUnique.php?postId=$postId&info=picUploaded");
   
                                       }else{
                                          die(mysqli_error($maConnection) );
                                       }
   
   
                                    }else{
   
                                       header("Location: postUnique.php?postId=$postId&info=uploadFailed");
                                    }
   
                                    //
                    }else{
   
                     header("Location: postUnique.php?postId=$postId&info=resolution");
                    }
   
                }else{
   
                  header("Location: postUnique.php?postId=$postId&info=oversized");
                }
   
   
            }else{
   
               header("Location: postUnique.php?postId=$postId&info=extension");
            }
   
   
   
   
   
   
            }else{
   
               echo "ce n'est pas VOTRE post, bas les pattes";
            }
   
   
      }
   }




    //Suppression d'un article

    if(isset($_POST['idSuppression'])){

      $idASupprimer = $_POST['idSuppression'];
   
      if($isLoggedIn && verifyOwnership($_SESSION['userId'], $idASupprimer, $maConnection) ){
      $maRequeteDeSuppression = "DELETE FROM posts WHERE id=$idASupprimer";

      $maSuppression= mysqli_query($maConnection, $maRequeteDeSuppression);

      header("Location: ../index.php");

      }    

    }
   


    // modification d'un article

      if(isset($_POST['titreEdite']) && isset($_POST['texteEdite'])){
         
            $titreEdite = $_POST['titreEdite'];
      
            $texteEdite = $_POST['texteEdite'];

            $idArticleAModifier = $_POST['idAModifier'];
            

            if($isLoggedIn && verifyOwnership($_SESSION['userId'], $idArticleAModifier, $maConnection) ){
            

               $maRequeteUpdate = "UPDATE posts SET title  = '$titreEdite', content = '$texteEdite' WHERE id = $idArticleAModifier";

               $monResultat = mysqli_query($maConnection, $maRequeteUpdate);

               header("Location: postUnique.php?postId=$idArticleAModifier&info=edited");
            } else{
               header("Location: postUnique.php?postId=$idArticleAModifier&info=pasLeDroit");

            }
         }






    //creation d'article

    if( isset($_POST['nouveauTitre']) && isset($_POST['nouveauTexte']) ){
            if( $_POST['nouveauTitre'] !== "" && $_POST['nouveauTexte'] !== "" ){
                    $nouveauTitre = $_POST['nouveauTitre'];
                    $nouveauTexte = $_POST['nouveauTexte'];
                    $authorId = $_SESSION['userId'];
                    $statusUpload = "default";
                     //requete par defaut
                     $maRequete = "INSERT INTO posts(title, content, authorid, image, published) VALUES ('$nouveauTitre', '$nouveauTexte', '$authorId', 'default.jpg', 0)";



                    if(isset($_FILES['uploadPostPic']['name']) && $_FILES['uploadPostPic']['name'] != ""){


                     $extensionsAutorisees=array('jpeg', 'jpg', 'png'); 
                     $hauteurMax = 720;
                     $largeurMax = 900; 
                     $tailleMax = 3000000;
            
                        
                      $uploaddir ="images/posts"; 
              
                      $mesInfos = getimagesize($_FILES['uploadPostPic']['tmp_name']);

                      if($mesInfos){
                      $monTableauExtension = explode("/", $mesInfos['mime']);
                      $monExtension = $monTableauExtension[1];
              
              
                      
                      $nomTemporaire = $_FILES['uploadPostPic']['tmp_name'];
                      $tableauTmpName= explode("\\", $nomTemporaire);
                      $nomFichier = end($tableauTmpName);

                      
                      $nomFinalDuFichier = $nomFichier.".".$monExtension;
                      $destinationFinal = $uploaddir.$nomFinalDuFichier;
              
              
                      $largeurFichier = $mesInfos[0];
                      $hauteurFichier = $mesInfos[1];
                      $tailleFichier = $_FILES['uploadPostPic']['size'];
                      
                       if(in_array($monExtension, $extensionsAutorisees)){
                          if($tailleFichier <= $tailleMax){
                             
                              if($largeurFichier <= $largeurMax && $hauteurFichier <= $hauteurMax){
                                     
                                  if(move_uploaded_file($nomTemporaire, $destinationFinal)){
                                    
                                 $maRequete = "INSERT INTO posts(title, content, authorid, image, published) VALUES ('$nouveauTitre', '$nouveauTexte', '$authorId', '$nomFinalDuFichier', 0)";
                                         
                                  }else{
                                     $statusUpload = "failed"; 
                                  }

                              }else {
                                 $statusUpload = "resolution";

                              }
              
                          } else{
                           $statusUpload = "oversized";
      
                          }
                      }else{
                        $statusUpload = "extension";
                           
                      } 
                      
                     }else{
                         $statusUpload ="notPicture";
                      }
                      }

                   
                     $statusUpload = "added";
                   
                     
                     $leResultatDeMonAjoutArticle = mysqli_query($maConnection, $maRequete);
                   
                   
                     // TEST qu ne doit pas etre visible pour les uilisateurs
                     if(!$leResultatDeMonAjoutArticle){
                        die("RAPPORT ERREUR ".mysqli_error($maConnection));
                        
                     } 
                     
                     header("Location: ../index.php?info=$statusUpload");
                  } else{
            echo "remplis ton formulaire en entier";
         }
           
    }
    
    //effectuer une requete pour un article spécifique:
     if(  isset($_GET['postId']) || isset($_POST['postId']) ){

           if(isset($_GET['postId'])){
              $postId = $_GET['postId'];
           }else{
            $postId = $_POST['postId'];
           }   
           $ownership = false;
           if($isLoggedIn && verifyOwnership($_SESSION['userId'], $postId, $maConnection) ){
              $ownership = true;
           }
             $maRequeteArticleUnique = "SELECT posts.id, posts.title, posts.content, posts.image, posts.authorid, posts.published FROM posts WHERE id=$postId";
             $leResultatDeMaRequeteArticleUnique = mysqli_query($maConnection, $maRequeteArticleUnique);
             $mesCommentaires=getCommentsByPostId($postId, $maConnection);

      }else if( isset($_POST['myPosts']) && $isLoggedIn){ 
      //requete pour tous les articles d'un user   
         $userId = $_SESSION['userId'];
         $maRequeteArticleUser = "SELECT posts.id, posts.title, posts.content, posts.authorid,users.username, users.displayname, posts.image, posts.published FROM posts INNER JOIN users On users.id = posts.authorid   WHERE authorid='$userId'";
         $leResultatDeMaRequete = mysqli_query($maConnection, $maRequeteArticleUser);
        



     }else{    //effectuer une requete SQL pour récupérer TOUS les posts

        $maRequete = "SELECT posts.id, posts.title, posts.content, posts.authorid, posts.image, users.displayname, users.username FROM posts INNER JOIN users On  users.id = posts.authorid WHERE posts.published = 1";

        $leResultatDeMaRequete = mysqli_query($maConnection, $maRequete);
        



     }

   
function verifyOwnership($userId, $idPost, $maConnexion){

   $maRequeteVerifyOwner = "SELECT * FROM posts WHERE id ='$idPost'";
   $monResultatVerifyOwner = mysqli_query($maConnexion, $maRequeteVerifyOwner);
   

   foreach($monResultatVerifyOwner as $value){
      $authorId = $value['authorid'];
   }
  
   $ownerVerified = false; 
   if($userId == $authorId){
      $ownerVerified = true;
   }

   if($ownerVerified){
      return  true;
   }else{
      return false;
   }
   
}

function getCommentsByPostId($postId, $maConnection){

   $maRequeteComments = "SELECT comments.content, users.displayname, users.username FROM comments INNER JOIN users
                        ON comments.author_id = users.id WHERE comments.post_id = '$postId'";
   $resultatRequeteComment = mysqli_query($maConnection, $maRequeteComments);
   return $resultatRequeteComment; 
}

0


    


    







?>