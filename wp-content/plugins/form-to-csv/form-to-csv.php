<?php
/*
    Plugin Name: Form to CSV
    Version: 1.0
    Author: Grégory Huyghe
*/



// 1. Code HTML du formulaire d'inscription
function ftc_htmlCode () {
    readfile("form-to-csv.html", 1);
}


// 2. Shortcode
function ftc_shortcode() {
    ftc_htmlCode();
}

add_shortcode( 'form_csv', 'ftc_shortcode' );


// 3. Onglet plugin dans panneau admin pour voir et télécharger les données collectées
function ftc_menu_item() {
    add_menu_page(
        __( 'Form to CSV', 'textdomain' ),
        'Form to CSV',
        'manage_options',
        'form-to-csv',
        'menu_plugin',
        'dashicons-portfolio',
        21
    );
}

add_action('admin_menu', 'ftc_menu_item');


// 4. Ecrire les données dans un fichier

// 4.1 Variables
$error = '';
$fname = sanitize_text_field($_POST['prenom']);
$lname = sanitize_text_field($_POST['nom']);
$email = sanitize_email($_POST['email']);
$checkbox = $_POST['films'];

// 4.2 Clean_text
function clean_text($clean) {
    $clean = trim($clean);
    $clean = stripslashes($clean);
    $clean = htmlspecialchars($clean);
    return $clean;
}

// 4.3 Soumission form
if(isset($_POST['submit'])) {
    
    if(!empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['email'])) {
        
        $fname = clean_text($_POST['prenom']);
        $lname = clean_text($_POST['nom']);
        $email = clean_text($_POST['email']);
        
        // Ecriture dans fichier csv
        $file_open = fopen('../form-to-csv.csv', 'a');
        $no_rows = count(file('form-to-csv.csv'));
        
        if ($no_rows > 1) {
            $no_rows = ($no_rows - 1) +1;
        }
        
        $form_data = array(
        'id' => $no_rows,
        'prenom' => $fname,
        'nom' => $lname,
        'email' => $email,
        'films' => $checkbox
        );
        
//        $csvData = $fname . ',' . $lname . ',' . $email . ',' .  $checkbox;       
//        if ($file_open){
//            fwrite($file_open, $csvData . '\n' );
//            fclose($file_open);
//        }
        
        //fputcsv($file_open, $form_data);
        
        file_put_contents('form-to-csv.csv', $form_data, FILE_USE_INCLUDE_PATH || FILE_APPEND);
        
//        $error = '<p>Votre inscription a bien été prise en compte</p>';
//        $fname = '';
//        $lname = '';
//        $email = '';
//        $checkbox = '';
    }
}

    
// 5. Récupérer ses infos dans un custom post accessible depuis le panneau admin. Pas d'envoi de mail.

// 5.1 Récupérer les données
$file_read = fopen('../form-to-csv.csv', 'r');

//// 5.2 Afficher les données dans l'onglet du plugin
function menu_plugin() {
    
    
    //Télécharger ce fichier .csv depuis l'onglet du plugin
    ?>
       
        <a href="file:///C:MAMP/htdocs/Plugin/wp-content/plugins/form-to-csv.csv">
        <button>Télécharger fichier CSV</button>
        </a>
        
    <?php
    header('Content-type: text/csv');
    header('Content-disposition: attachment; filename = form-to-csv.csv');
}

fclose($file_read);
    
   
