<?php
    if(isset($_GET['delete'])) {
        $delete_file = 'C:\Users\huygh\Desktop\form-to-csv.csv';
        unlink("$delete_file");
    }
?>