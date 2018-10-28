<?php
    header('Location: C:\Users\huygh\Desktop\form-to-csv.csv')
    header('Content-Disposition: attachment; filename="form-to-csv.csv"');
    header('Content-Type: text/csv');
    readfile('form-to-csv.csv')
?>