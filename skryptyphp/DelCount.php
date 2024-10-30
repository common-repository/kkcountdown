<?php

require('../../../../wp-blog-header.php');

$id = $_POST['id'];

$table_name = $wpdb->prefix."kkcountdown";

$sql = "DELETE FROM ".$table_name." WHERE id = '$id' LIMIT 1";

$wynik = $wpdb->query($sql);

if($wynik) {
    echo 1;
}else {
    echo 0;
}

?>
