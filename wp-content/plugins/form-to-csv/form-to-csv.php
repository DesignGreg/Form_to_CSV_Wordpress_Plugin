<?php
/*
    Plugin Name: Form to CSV
    Version: 1.0
    Author: Grégory Huyghe
*/



// 1. Code HTML du formulaire d'inscription
function ftc_htmlCode () {
?> 
<form action="" method="post">
    <label for="prenom">Prénom</label>
    <input class="main-content__form--input" pattern="[a-zA-Z0-9 ]+" id="prenom" type="text" name="prenom" required>
    
    <label for="nom">Nom</label>
    <input class="main-content__form--input" pattern="[a-zA-Z0-9 ]+" id="nom" type="text" name="nom" required>
    
    <label for="email">Email</label>
    <input class="main-content__form--input" id="email" type="email" name="email" required>
    
    <fieldset class="main-content__form--checkbox">
        <legend class="main-content__form--legend">Sélection des films</legend>
        
        <input type="checkbox" id="laHaine" name="films" value="La Haine">
        <label for="laHaine">La Haine</label>
        
        <input type="checkbox" id="odyssee" name="films" value="l'Odyssée de l'espace">
        <label for="odyssee">l'Odyssée de l'espace</label>
        
        <input type="checkbox" id="requiem" name="films" value="Requiem for a dream">
        <label for="requiem">Requiem for a dream</label>
        
        <input type="checkbox" id="mulholland" name="films" value="Mulholland Drive">
        <label for="mulholland">Mulholland Drive</label>
        
        <input type="checkbox" id="Carnage" name="films" value="Carnage">
        <label for="Carnage">Carnage</label>
        
        <input type="checkbox" id="under" name="films" value="Under the skin">
        <label for="under">Under the skin</label>
        
        <input type="checkbox" id="edward" name="films" value="Edward aux mains d'argent">
        <label for="edward">Edward aux mains d'argent</label>
        
        <input type="checkbox" id="lost" name="films" value="Lost in translation">
        <label for="lost">Lost in translation</label>
    </fieldset>

    <input class="main-content__form--input" type="submit" value="S'inscrire">
</form>
 
<?php   
 
}


// 2. Shortcode
function ftc_shortcode() {
    ftc_htmlCode();
}

add_shortcode( 'form_csv', 'ftc_shortcode' );


// 3. Menu Admin pour voir données collectées
function ftc_menu_item() {
    add_menu_page(
        __( 'Form to CSV', 'textdomain' ),
        'Form to CSV',
        'manage_options',
        'custom_menu_page',
        '',
        'dashicons-portfolio',
        21
    );
}

add_action('admin_menu', 'ftc_menu_item');

    
// 4. Récupérer ses infos dans un custom post accessible depuis le panneau admin. Pas d'envoi de mail. Envoi données vers DB
$prenom = $_POST['prenom'];
$nom = $_POST['nom'];
$email = $_POST['email'];

function insertuser( $prenom, $nom, $email ) {
  global $wpdb;

  $table_name = $wpdb->prefix . 'inscription';
  $wpdb->insert( $table_name, array(
    'prenom' => $prenom,
    'nom' =>$nom,
    'email' => $email
  ) );
}

insertuser( $prenom, $nom, $email );


// 5. Pouvoir extraire ses infos dans un fichier .csv depuis le panneau admin. Requête DB
    
   
