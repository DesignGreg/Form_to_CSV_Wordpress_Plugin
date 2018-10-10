<?php

/*
Plugin Name: Email to CSV
Plugin URI: 
Description: Get subscribers in a CSV file.
Version: 1.0
Author:
Author URI:
License: 
License URI:
Text Domain: email-to-csv
*/


/* 1. HOOKS */

// 1.1: register shortcode on init
add_action ('init', 'etc_register_shortcodes');

// 1.2: register custom admin column header
add_filter ('manage_edit-etc_subscriber_columns', 'etc_subscriber_column_headers');
add_filter ('manage_edit-etc_list_columns', 'etc_list_column_headers');

// 1.3: register custom admin column data
add_filter ('manage_etc_subscriber_posts_custom_column', 'etc_subscriber_column_data', 1, 2);
add_action ('admin_head-edit.php', 'etc_register_custom_admin_titles');
add_filter ('manage_etc_list_posts_custom_column', 'etc_list_column_data', 1, 2);


/* 2. SHORTCODES */

// 2.1: register shortcode
function etc_register_shortcodes() {
    
    add_shortcode('etc_form', 'etc_form_shortcode');
}

// 2.2: returns a html string for an email form
function etc_form_shortcode($args, $content="") {
    
    //get the list id
    $list_id = 0;
    if (isset($args['id'])) {
        $list_id = (int)$args['id'];
    }
    
    // setup our output variable - the form html
    $output = '
    
        <div class="etc">
            <form id="etc_form" name="etc_form" class="etc-form" method="post" action="/wp-admin/admin-ajax.php?action=etc_save_subscription" method="post">
            
            <input type="hidden" name="etc_list" value="'. $list_id .'">
            
                <p class="etc-input-container">
                    <label>Your Name</label>
                    <input type="text" name="etc_fname" placeholder="First Name">
                    <input type="text" name="etc_lname" placeholder="Last Name">
                </p>
                
                <p class="etc-input-container">
                    <label>Your Email</label>
                    <input type="email" name="etc_email" placeholder="your@email.com">           
                </p>';
        
                // including content in the form html if content is passed into the function
                if(strlen($content)) {
                    
                    $output .= '<div class="etc-content>' . wpautop($content) . '</div>';
                
                }    
                
                // completing the form html
                $output .= '<p class="etc-input-container">
                    <input type="submit" name="etc_submit" value="Sign Me Up!">    
                </p>
            </form>
        </div>
    
    ';
    
    return $output;
}


/* 3. FILTERS */

// 3.1
function etc_subscriber_column_headers ($columns) {
    // creating custom column header data
    $columns = array (
        'cb' => '<input type="checkbox">',
        'title' =>__('Subscriber Name'),
        'email' =>__('Email Address'),
    );
    
    return $columns;
}

//3.2
function etc_subscriber_column_data ($column, $post_id) {
    
    $output = '';
    
    switch ($column) {
            
        case 'title':
            $fname = get_field('etc_fname', $post_id);
            $lname = get_field('etc_lname', $post_id);
            $output .= $fname .' '. $lname;    
            break;
        case 'email':
            $email = get_field('etc_email', $post_id);
            $output .= $email;    
            break;
    }
    
    echo $output;
}

//3.2.2: special custom admin title columns
function etc_register_custom_admin_titles() {
    add_filter (
        'the_title',
        'etc_custom_admin_titles',
        99,
        2
    );
}

//3.3.3: handles custom admin title "title" column data for post types without title
function etc_custom_admin_titles ($title, $post_id) {
    global $post;
    
    $output = $title;
    
    if (isset($post->post_type)) {
        switch ($post->post_type) {
            case 'etc_subscriber':
                $fname = get_field('etc_fname', $post_id);
                $lname = get_field('etc_lname', $post_id);
                $output = $fname .' '. $lname;    
                break;
        }
    }
    
    return $output;
}

// 3.3
function etc_list_column_headers ($columns) {
    // creating custom column header data
    $columns = array (
        'cb' => '<input type="checkbox">',
        'title' =>__('List Name'),
    );
    
    return $columns;
}

//3.4
function etc_list_column_data ($column, $post_id) {
    
    $output = '';
    
    switch ($column) {
            
        case 'example':
//            $fname = get_field('etc_fname', $post_id);
//            $lname = get_field('etc_lname', $post_id);
//            $output .= $fname .' '. $lname;    
            break;
    }
    
    echo $output;
}



/* 4. EXTERNAL SCRIPTS */

/* 5. ACTIONS */

/* 6. HELPERS */

/* 7. CUSTOM POST TYPES */

/* 8. ADMIN PAGES */

/* 9. SETTINGS */





?>