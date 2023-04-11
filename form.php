<?php

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // verification de la méthode utilisée par le formulaire si POST :

    // definition du poids max de l'image
    $maxSizeFile = 1000000;
    // definition du dossier destination de l'image upload
    $uploadDir = "uploads/";
    // récupération de l'extension de l'image upload
    $fileExtension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    // definition des extensions d'image prises en charge 
    $authorizedExtension = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    // création du nom unique pour l'image upload
    $newFileName = uniqid(basename($_FILES['avatar']['name']), $fileExtension);

    // Verification de l'extension de l'image upload vs les extensions autorisées 
    if ((!in_array($fileExtension, $authorizedExtension))) {
        $errors[] = 'Veuillez sélectionner une image de type Jpg ou Jpeg ou Png !';
    }
    // Verification du poid de l'image upload vs le poid maxi autorisé 
    if (file_exists($_FILES['avatar']['tmp_name']) && filesize($_FILES['avatar']['tmp_name']) > $maxSizeFile) {
        $errors[] = "Votre fichier doit faire moins de 1Mo !";
    }
    if (empty($errors)) {
        // génération du chemin complet de l'image upload 
        $filePath = $uploadDir . $newFileName;
        move_uploaded_file($_FILES['avatar']['tmp_name'], $filePath);
    }
    // verification de sécurité pour tous les autres champs du formulaire 
    if (!isset($_POST['firstname']) || trim($_POST['firstname']) === '')
        $errors[] = 'Please enter a firstname';

    if (!isset($_POST['lastname']) || trim($_POST['lastname']) === '')
        $errors[] = 'Please enter a lastname';

    if (!isset($_POST['age']) || trim($_POST['age']) === '')
        $errors[] = 'Please enter your age';
        
}

// var_dump($errors);
// ?>

<?php 
 // affichage des erreurs sous forme d'une liste HTML si il y a des erreurs
    if (!empty($errors)){ ?>
        <ul>
            <?php foreach ($errors as $error){ ?>
            <li><?=$error?></li>
        <?php } 
    }
?>
<?php
// si pas d'erreur, et si le chemin du fichier est bien defini plus haut, affichage du message avec la photo uploadée. 
    if (isset($filePath)){ ?>
        <p> Bonjour <?= $_POST['firstname'] .' ' . $_POST ['lastname'] . ','?>bienvenue sur meetic pour Springfield ! Nous esperons que vous trouverez l'âme soeur rapidement ! Vous avez <?= $_POST['age'] . 'ans'?>. Nous allons donc vous faire rencontrer des personnes dans votre tranche d'âge. Voici votre avatar : <img src= "<?=$filePath ?>" alt = "votre avatar"/>.

    <?php } ?>


<?php // formulaire ?>

<form method="post" enctype="multipart/form-data">
    <label for="firstname"> Votre prénom</label>
    <input type="text" name="firstname" />
    <label for="lastname"> Votre nom</label>
    <input type="text" name="lastname" />
    <label for="age"> Votre age</label>
    <input type="int" name="age" />
    <label for="imageUpload">Votre avatar</label>
    <input type="file" name="avatar" id="imageUpload" />
    <button name="send">Send</button>
</form>