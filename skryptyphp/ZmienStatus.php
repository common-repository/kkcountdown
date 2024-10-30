<?php

require('../../../../wp-blog-header.php');

$id = $_POST['id'];

$table_name = $wpdb->prefix."kkcountdown";

$sql = "SELECT status FROM $table_name WHERE id = '$id' LIMIT 1";
$dane = $wpdb->get_row($sql,ARRAY_A);

if($dane['status'] == 1){

    $sqla = "UPDATE ".$table_name." SET status = '0' WHERE id = '$id' LIMIT 1";
    $wynika = $wpdb->query($sqla);

    if($wynika){
        echo 1;
    }

}else if($dane['status'] == 0){

    $sqla = "UPDATE ".$table_name." SET status = '1' WHERE id = '$id' LIMIT 1";
    $wynika = $wpdb->query($sqla);

    if($wynika){
        echo 2;
    }

}

?>
