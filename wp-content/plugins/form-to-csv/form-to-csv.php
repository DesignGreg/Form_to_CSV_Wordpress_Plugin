<?php
/*
    Plugin Name: Form to CSV
    Version: 1.0
    Author: Grégory Huyghe
*/



// 1. Code HTML du formulaire d'inscription
function htmlCode () {
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
    
// 2. Récupérer ses infos dans un custom post accessible depuis le panneau admin. Pas d'envoi de mail. Envoi données vers DB
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



// 3. Pouvoir extraire ses infos dans un fichier .csv depuis le panneau admin. Requête DB
    
    
    
    
    
// THE FORM  
function html_form_code() {
    
    echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
    
    echo '<p>';
    echo 'Your Name (required) <br />';
    echo '<input type="text" name="cf-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="40" />';
    echo '</p>';
    
    echo '<p>';
    echo 'Your Email (required) <br />';
    echo '<input type="email" name="cf-email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" value="' . ( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="40" />';
    echo '</p>';
    
    echo '<p>';
    echo 'Subject (required) <br />';
    echo '<input type="text" name="cf-subject" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["cf-subject"] ) ? esc_attr( $_POST["cf-subject"] ) : '' ) . '" size="40" />';
    echo '</p>';
    
    echo '<p>';
    echo 'Your Message (required) <br />';
    echo '<textarea rows="10" cols="35" name="cf-message">' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
    echo '</p>';
    
    echo '<p><input type="submit" name="cf-submitted" value="Send"/></p>';
    echo '</form>';
}

// SEND THE FORM DATA TO ADMIN EMAIL ADDRESS
function deliver_mail() {

    // if the submit button is clicked, send the email
    if ( isset( $_POST['cf-submitted'] ) ) {

        // sanitize form values
        $name    = sanitize_text_field( $_POST["cf-name"] );
        $email   = sanitize_email( $_POST["cf-email"] );
        $subject = sanitize_text_field( $_POST["cf-subject"] );
        $message = esc_textarea( $_POST["cf-message"] );

        // get the blog administrator's email address
        $to = get_option( 'admin_email' );

        $headers = "From: $name <$email>" . "\r\n";

        // If email has been process for sending, display a success message
        if ( wp_mail( $to, $subject, $message, $headers ) ) {
            echo '<div>';
            echo '<p>Thanks for contacting me, expect a response soon.</p>';
            echo '</div>';
        } else {
            echo 'An unexpected error occurred';
        }
    }
}

// SHORTCODE TO DISPLAY FORM ON WEBSITE
function cf_shortcode() {
    ob_start();
    deliver_mail();
    html_form_code();

    return ob_get_clean();
}

add_shortcode( 'form_csv', 'cf_shortcode' );

?>