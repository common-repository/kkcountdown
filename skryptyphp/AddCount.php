<?php

require('../../../../wp-blog-header.php');

$nazwa = $_POST['nazwa'];
$naglowek = $_POST['naglowek'];
$dat = $_POST['data'];

$table_name = $wpdb->prefix."kkcountdown";

$data = explode(' ',$dat);

$data_a = explode('-', $data[0]);
$data_b = explode(':', $data[1]);

if($data_b[0] == '') {
    $data_b[0] = '00';
}
if($data_b[1] == '') {
    $data_b[1] = '00';
}
if($data_b[2] == '') {
    $data_b[2] = '00';
}

$data = mktime($data_b[0], $data_b[1], $data_b[2], $data_a[1], $data_a[2], $data_a[0]);

$sql = "INSERT INTO ".$table_name." (
`id` ,
`nazwa` ,
`przed_data` ,
`data` ,
`status`
)
VALUES (
NULL , '$nazwa', '$naglowek', '$data', '1'
);";

$wynik = $wpdb->query($sql);

if($wynik) {
    echo 1;
}else {
    echo 0;
}

?>