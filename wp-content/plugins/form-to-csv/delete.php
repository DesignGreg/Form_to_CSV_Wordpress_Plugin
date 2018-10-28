<?php
    if(isset($_GET['delete'])) {
        $delete_file = $_GET['C:\Users\huygh\Desktop\form-to-csv.csv'];
        unlink("$delete_file");
    }
?>