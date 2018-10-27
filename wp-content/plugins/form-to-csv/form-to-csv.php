<?php
/*
    Plugin Name: Form to CSV
    Version: 1.0
    Author: Grégory Huyghe
*/


// 1. Shortcode
function ftc_shortcode() {
    readfile("form-to-csv.html", 1);
}

add_shortcode( 'form_csv', 'ftc_shortcode' );


// 2. Onglet plugin dans panneau admin pour voir et télécharger les données collectées
function ftc_menu_item() {
    add_menu_page(
        __( 'Form to CSV', 'textdomain' ),
        'Form to CSV',
        'manage_options',
        'form-to-csv',
        'ftc_menu_plugin',
        'dashicons-portfolio',
        21
    );
}

add_action('admin_menu', 'ftc_menu_item');


// 3. Ecrire les données dans un fichier

// 3.1 Variables
$error = '';
$fname = sanitize_text_field($_POST['prenom']);
$lname = sanitize_text_field($_POST['nom']);
$email = sanitize_email($_POST['email']);
$checkbox = implode(" / ", (array)$_POST['films']);

// 3.2 Clean_text
function clean_text($clean) {
    $trimmed = trim($clean);
    $stripped = stripslashes($clean);
    $special = htmlspecialchars($clean);
    return $clean;
}

// 3.3 Submission form
if(isset($_POST['submit'])) {
    $success = true;
    
    if(empty($_POST['prenom']) OR empty($_POST['nom']) OR empty($_POST['email'])) {
        $error = '<p>Veuillez réessayer</p>';
    } else {
        $fname = clean_text($_POST['prenom']);
        $lname = clean_text($_POST['nom']);
        $email = clean_text($_POST['email']);
        }
        
    if($error == '' && $success = true) {
            
        // Ecriture dans fichier csv
        $file_open = fopen('C:\Users\huygh\Desktop\form-to-csv.csv', 'a');
        $index = count(file('C:\Users\huygh\Desktop\form-to-csv.csv')); 
        if ($index == 0) {
            $index = $index +1;
        } else if ($index > 0) {
            $index = $index +1;
        }
        
        $form_data = array(
        'id' => $index,
        'prenom' => $fname,
        'nom' => $lname,
        'email' => $email,
        'films' => $checkbox
    );
        fputcsv($file_open, $form_data);
        header( 'Location: index.php' );
        exit();
    }           
}

    
// 4. Récupérer ses infos dans un custom post accessible depuis le panneau admin. Pas d'envoi de mail.

// 4.1 Récupérer et  Afficher les données dans l'onglet du plugin

function ftc_menu_plugin() {
    
    $row = 1;
    
    if (($file_read = fopen('C:\Users\huygh\Desktop\form-to-csv.csv', 'r')) !== FALSE) {
        while (($data = fgetcsv($file_read, 1000, ',')) !== FALSE) {
            $num = count($data);
            echo "<p> $num champs à la ligne $row: <br /></p>\n";
            $row++;
                for ($c=0; $c < $num; $c++) {
                    echo $data[$c] . "br />\n";
                }

        }
    }
    fclose($file_read);
}

add_action('admin_menu', 'ftc_menu_plugin');
    
// 4.2 Télécharger ce fichier .csv depuis l'onglet du plugin
    ?>

<a href="data:text/csv;charset=utf-8,'+escape(csv)+'" download="form-to-csv.csv">
    <button>Télécharger fichier CSV</button>
</a>

<!--
<a href="file:///C:MAMP/htdocs/Plugin/wp-content/plugins/form-to-csv.csv">
    <button>Télécharger fichier CSV</button>
</a>
-->