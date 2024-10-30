<?php

add_action('wp_ajax_add_count_kkcd', 'addCountKKCD');

function addCountKKCD() {

    global $wpdb;

    $nazwa = $_POST['nazwa'];
    $naglowek = $_POST['naglowek'];
    $dat = $_POST['data'];

    $table_name = $wpdb->prefix . "kkcountdown";

    $data = explode(' ', $dat);

    $data_a = explode('-', $data[0]);
    $data_b = explode(':', $data[1]);

    if ($data_b[0] == '') {
        $data_b[0] = '00';
    }
    if ($data_b[1] == '') {
        $data_b[1] = '00';
    }
    if ($data_b[2] == '') {
        $data_b[2] = '00';
    }

    $data = mktime($data_b[0], $data_b[1], $data_b[2], $data_a[1], $data_a[2], $data_a[0]);

    $sql = "INSERT INTO " . $table_name . " (
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
    $id_last = mysql_insert_id();

    if ($wynik) {
        $data = date('Y-m-d H:i:s', $data);
        echo '1|||<div style="background: #e0ffd2; margin:20px; padding: 10px; border-top: 1px #38bb00 solid; border-bottom: 1px #38bb00 solid;">'.__('Countdown added successfully.','lang-kkcountdown').'</div>|||'.$id_last.'|||<tr class="alternate" id="kkcd-row-' . $id_last . '">
                <td>'.$id_last.'</td>
                <td>'.$nazwa.'</td>
                <td>'.$naglowek.'</td>
                <td>'.$data.'</td>
                <td><img src="'.WP_PLUGIN_URL.'/kkcountdown/images/aktywny.png" id="kkc-status-'.$id_last.'" onclick="zmienStatus(\''.$id_last.'\'); return false;" alt="Yes" style="display:inline-block; vertical-align:middle; cursor: pointer;" /> <span id="loader-status-'.$id_last.'" style="display:none;"><img src="'.WP_PLUGIN_URL.'/kkcountdown/images/small-loader.gif" alt="..." style="display:inline-block; vertical-align:middle;" /><span></td>
                <td><a href="#" onclick="editCount(\''.$id_last.'\',\''.$nazwa.'\',\''.$naglowek.'\',\''.$data.'\'); return false;"><img src="'.WP_PLUGIN_URL.'/kkcountdown/images/edit_count.png" alt="+" style="display:inline-block; vertical-align:middle;" /> '.__('Edit','lang-kkcountdown').'</a></td>
                <td><a href="#" onclick="delCount(\''.$id_last.'\'); return false;"><img src="'.WP_PLUGIN_URL.'/kkcountdown/images/delete_count.png" alt="+" style="display:inline-block; vertical-align:middle;" /> '.__('Delete','lang-kkcountdown').'</a></td>
                </tr>|||';
    } else {
        echo '0|||<div style="background: #ffd9d9; margin:20px; padding: 10px; border-top: 1px #bb0000 solid; border-bottom: 1px #bb0000 solid;">'.__('ERROR: Countdown cant be added successfully.','lang-kkcountdown').'</div>|||';
    }
}

add_action('wp_ajax_del_count_kkcd', 'delCountKKCD');

function delCountKKCD() {

    global $wpdb;

    $id = $_POST['id'];

    $table_name = $wpdb->prefix . "kkcountdown";

    $sql = "DELETE FROM " . $table_name . " WHERE id = '$id' LIMIT 1";

    $wynik = $wpdb->query($sql);

    if ($wynik) {
        echo '1|||<div class="kkpb-ok postbox">'.__('Countdown deleted successfully.','lang-kkcountdown').'</div>|||';
    } else {
        echo '0|||<div class="kkpb-error postbox">'.__('Countdown could not be deleted. Please try again.','lang-kkcountdown').'</div>|||';
    }
}

add_action('wp_ajax_edit_count_kkcd', 'editCountKKCD');

function editCountKKCD() {

    global $wpdb;

    $id = $_POST['id'];
    $nazwa = $_POST['nazwa'];
    $naglowek = $_POST['naglowek'];
    $dat = $_POST['data'];

    $table_name = $wpdb->prefix . "kkcountdown";

    $data = explode(' ', $dat);

    $data_a = explode('-', $data[0]);
    $data_b = explode(':', $data[1]);

    if ($data_b[0] == '') {
        $data_b[0] = '00';
    }
    if ($data_b[1] == '') {
        $data_b[1] = '00';
    }
    if ($data_b[2] == '') {
        $data_b[2] = '00';
    }

    $data = mktime($data_b[0], $data_b[1], $data_b[2], $data_a[1], $data_a[2], $data_a[0]);

    $sql = "UPDATE " . $table_name . " SET nazwa = '$nazwa', przed_data = '$naglowek', data = '$data' WHERE id = '$id' LIMIT 1";

    $wynik = $wpdb->query($sql);

    if ($wynik) {
        $data = date('Y-m-d H:i:s', $data);
        echo '1|||<div style="background: #e0ffd2; margin:20px; padding: 10px; border-top: 1px #38bb00 solid; border-bottom: 1px #38bb00 solid;">'.__('Changes saved successfully..','lang-kkcountdown').'</div>|||<tr class="alternate" id="kkcd-row-' . $id . '">
                <td>'.$id.'</td>
                <td>'.$nazwa.'</td>
                <td>'.$naglowek.'</td>
                <td>'.$data.'</td>
                <td><img src="'.WP_PLUGIN_URL.'/kkcountdown/images/aktywny.png" id="kkc-status-'.$id.'" onclick="zmienStatus(\''.$id.'\'); return false;" alt="Yes" style="display:inline-block; vertical-align:middle; cursor: pointer;" /> <span id="loader-status-'.$id.'" style="display:none;"><img src="'.WP_PLUGIN_URL.'/kkcountdown/images/small-loader.gif" alt="..." style="display:inline-block; vertical-align:middle;" /><span></td>
                <td><a href="#" onclick="editCount(\''.$id.'\',\''.$nazwa.'\',\''.$naglowek.'\',\''.$data.'\'); return false;"><img src="'.WP_PLUGIN_URL.'/kkcountdown/images/edit_count.png" alt="+" style="display:inline-block; vertical-align:middle;" /> '.__('Edit','lang-kkcountdown').'</a></td>
                <td><a href="#" onclick="delCount(\''.$id.'\'); return false;"><img src="'.WP_PLUGIN_URL.'/kkcountdown/images/delete_count.png" alt="+" style="display:inline-block; vertical-align:middle;" /> '.__('Delete','lang-kkcountdown').'</a></td>
                </tr>|||';
    } else {
        echo '0|||<div style="background: #ffd9d9; margin:20px; padding: 10px; border-top: 1px #bb0000 solid; border-bottom: 1px #bb0000 solid;">'.__('ERROR: Changes have not been saved. Please try again..','lang-kkcountdown').'</div>|||';
    }
}

add_action('wp_ajax_zmien_status_kkcd', 'zmienStatusKKCD');

function zmienStatusKKCD() {

    global $wpdb;

    $id = $_POST['id'];

    $table_name = $wpdb->prefix . "kkcountdown";

    $sql = "SELECT status FROM $table_name WHERE id = '$id' LIMIT 1";
    $dane = $wpdb->get_row($sql, ARRAY_A);

    if ($dane['status'] == 1) {

        $sqla = "UPDATE " . $table_name . " SET status = '0' WHERE id = '$id' LIMIT 1";
        $wynika = $wpdb->query($sqla);

        if ($wynika) {
            echo 1;
        }
    } else if ($dane['status'] == 0) {

        $sqla = "UPDATE " . $table_name . " SET status = '1' WHERE id = '$id' LIMIT 1";
        $wynika = $wpdb->query($sqla);

        if ($wynika) {
            echo 2;
        }
    }
}

add_action('wp_ajax_settings_kkcd', 'saveSettingsAjaxKKCD');

function saveSettingsAjaxKKCD() {

    global $wpdb;

    $kolor = $_POST['kolor'];
    $kolor_doba = $_POST['kolor_doba'];
    $text_doba = $_POST['text_doba'];
    $text = $_POST['text'];
    $days = $_POST['days'];
    $kkcd_class = $_POST['count_class'];

    $table_name = $wpdb->prefix . "kkcdsettings";

    $sql = "UPDATE  " . $table_name . " SET  `value` =  '" . $kolor . "' WHERE  `idkkcdsettings` = 1 LIMIT 1";
    $wynik = $wpdb->query($sql);

    $sql = "UPDATE  " . $table_name . " SET  `value` =  '" . $kolor_doba . "' WHERE  `idkkcdsettings` = 2 LIMIT 1";
    $wynik = $wpdb->query($sql);

    $sql = "UPDATE  " . $table_name . " SET  `value` =  '" . $text_doba . "' WHERE  `idkkcdsettings` = 3 LIMIT 1";
    $wynik = $wpdb->query($sql);

    $sql = "UPDATE  " . $table_name . " SET  `value` =  '" . $text . "' WHERE  `idkkcdsettings` = 4 LIMIT 1";
    $wynik = $wpdb->query($sql);

    $sql = "UPDATE  " . $table_name . " SET  `value` =  '" . $days . "' WHERE  `idkkcdsettings` = 5 LIMIT 1";
    $wynik = $wpdb->query($sql);

    $sql = "UPDATE  " . $table_name . " SET  `value` =  '" . $kkcd_class . "' WHERE  `idkkcdsettings` = 6 LIMIT 1";
    $wynik = $wpdb->query($sql);

    echo '<div class="kkcd-ok postbox">Changes saved successfully.</div>|||';
}
?>