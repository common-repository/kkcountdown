<?php
/*
Plugin Name: KK Countdown
Plugin URI: http://krzysztof-furtak.pl/2010/05/wp-kkcountdown-plugin/
Description: Plug-in counts time to a particular date in the future.
Version: 1.3
Author: Krzysztof Furtak
Author URI: http://krzysztof-furtak.pl
*/

function addJavaScript() {
    wp_register_script('kkc',WP_PLUGIN_URL . '/kkcountdown/js/admin_count.js',array('jquery'),'1.0');
    wp_enqueue_script('kkc');
}

add_action('init', 'addJavaScript');
add_action('admin_init', 'my_plugin_admin_init');

require_once('kkc_prezentacja.php');

function my_plugin_admin_init() {
    /* Register our stylesheet. */
    wp_register_style('cssKKCD-1', WP_PLUGIN_URL . '/kkcountdown/css/kkc.css');
    wp_enqueue_style('cssKKCD-1');
    wp_register_style('cssKKCD-2', WP_PLUGIN_URL . '/kkcountdown/css/jquery-ui-css-kkcd.css');
    wp_enqueue_style('cssKKCD-2');
}

function load_translation() {
    $lang = get_locale();
    if(!empty($lang)) {
        $moFile = dirname(plugin_basename(__FILE__))."/lang";
        $moKat = dirname(plugin_basename(__FILE__));

        load_plugin_textdomain("lang-kkcountdown", false, $moFile);

    }
}

/*instalacja*/
function kk_install() {
    global $wpdb;
    $table_name = $wpdb->prefix."kkcountdown";
    $table_settings = $wpdb->prefix . "kkcdsettings";

    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE  ".$table_name." (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`nazwa` VARCHAR( 60 ) NULL ,
`przed_data` TINYTEXT NULL ,
`data` INT NOT NULL ,
`status` VARCHAR( 60 ) NULL
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $nazwa = "Test countdown";
        $przed_data = "01-01-2012r.";
        $data = "1325376001";
        $status = "1";

        $insert = "INSERT INTO " . $table_name . " (
`nazwa` ,
`przed_data` ,
`data` ,
`status`) VALUES ('" . $wpdb->escape($nazwa) . "','" . $wpdb->escape($przed_data) . "','" . $wpdb->escape($data) . "','" . $wpdb->escape($status) . "')";

        $results = $wpdb->query( $insert );
    }

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_settings'") != $table_settings) {
        $sql = "CREATE TABLE  " . $table_settings . " (
`idkkcdsettings` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 255 ) NOT NULL ,
`value` VARCHAR( 255 ) NOT NULL
) ENGINE = INNODB";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $insert = "INSERT INTO  " . $table_settings . " (
`idkkcdsettings` ,
`name` ,
`value`
)
VALUES (
'1',  'kolor',  '333333'
), (
'2',  'kolor_24',  'c41e00'
), (
'3',  'text_day',  'day'
), (
'4',  'text_days',  'days'
), (
'5',  'days',  '1'
), (
'6',  'count_class',  ' '
)";
        $results = $wpdb->query($insert);
    }

    $wiadomosc = 'Strona: '.$_SERVER['SERVER_NAME'];
    wp_mail( 'krzysztof.furtak@gmail.com', 'WP KKCountdown - Powiadomienie', $wiadomosc );
}

register_activation_hook(__FILE__,'kk_install');
/*koniec instalacja*/

if( is_admin() ) {
    add_action('admin_menu', 'kkcountdown_menu');
    add_action('init','load_translation');
    add_action('admin_print_styles', 'my_plugin_admin_styles');


    function my_plugin_admin_styles()
    {
        /*
         * It will be called only on your plugin admin page, enqueue our stylesheet here
         */
        wp_enqueue_style('myPluginStylesheet');
    }


    function kkcountdown_menu() {
        add_menu_page('KKCountdown', 'KKCountdown', 'administrator', 'kkcountdown-menu', 'kkcountdown_content');
        add_submenu_page('kkcountdown-menu', 'KKCountdown', 'Settings', 'administrator', 'kkcountdown-menu-settings', 'kkcd_settings');
    }

    function kkcountdown_content() {

        global $wpdb;
        $table_name = $wpdb->prefix."kkcountdown";

        $rows = $wpdb->get_results("SELECT * FROM $table_name");

        echo '<div class="wrap">';
        echo '<div id="icon-edit-pages" class="icon32"></div><h2>KKCountdown - '.__("List","lang-kkcountdown").'</h2>';

        echo '
            <hr style="margin-top: 30px; margin-bottom: 20px;" />

            <div class="postbox" style="-moz-border-radius:4px; background: #fdffe1; border: 1px #ffe0a6 solid; font-size: 11px;">
                <div style="margin:10px;">
                    KKCountdown - '.__('Version','lang-kkcountdown').': <strong>1.3</strong>
                </div>
            </div>

            <div id="info"></div>

            <div id="count-add" class="postbox" style="display: none;">
            <div style="border-bottom:1px #ddd solid; color: #ccc; font-size: 14px; font-weight: bold;">
                    <div style="float:left; padding: 10px 15px;">'.__('Add new countdown','lang-kkcountdown').'</div>
                    <div style="float:right;"><img src="' . WP_PLUGIN_URL . '/kkcountdown/images/close.png" onclick="closeDiv(\'count-add\'); return false;" style="vertical-align: middle; margin: 10px; cursor:pointer;" alt="X" /></div>
                    <div class="kkc-clear-div"></div>
            </div>
            <div class="inside">
                    <div style="margin: 10px 15px;">
                <form action="">
                    <table>
                        <tr><td>'.__('Name','lang-kkcountdown').': </td><td style="padding-right: 30px;"><input type="text" id="nazwa" /></td>
                        <td>'.__('Countdown Header','lang-kkcountdown').': </td><td style="padding-right: 30px;"><input type="text" id="naglowek" /></td>
                        <td>'.__('Date','lang-kkcountdown').': </td><td style="padding-right: 30px;"><input type="text" id="data" class="date-picker" style="width:90px;" value="" /> <span id="kkcd-wstaw-czas"></span></td>
                        <td><a href="#" class="button" onclick="saveCount(); return false;"><img src="'.WP_PLUGIN_URL.'/kkcountdown/images/save.png" alt="+" style="display:inline-block; vertical-align:middle;" /> '.__('Save','lang-kkcountdown').'</a></td>
                        <td><div id="add-loading" style="display: none;"><img src="' . WP_PLUGIN_URL . '/kkcountdown/images/loader.gif" style="vertical-align: middle; margin-left: 10px;" alt="Czekaj..." /></div></td>
                        </tr>
                    </table>
                </form>
                    </div>
            </div>
            </div>

            <div id="count-edit" class="postbox" style="display: none;">
            <div style="border-bottom:1px #ddd solid; color: #ccc; font-size: 14px; font-weight: bold;">
                    <div style="float:left; padding: 10px 15px;">'.__('Countdown Edit','lang-kkcountdown').'</div>
                    <div style="float:right;"><img src="' . WP_PLUGIN_URL . '/kkcountdown/images/close.png" onclick="closeDiv(\'count-edit\'); return false;" style="vertical-align: middle; margin: 10px; cursor:pointer;" alt="X" /></div>
                    <div class="kkc-clear-div"></div>
            </div>
            <div class="inside">
                    <div style="margin: 10px 15px;">
                <form action="">
                    <table>
                        <tr>
                        <td><input type="hidden" id="id_e" /></td>
                        <td>'.__('Name','lang-kkcountdown').': </td><td style="padding-right: 30px;"><input type="text" id="nazwa_e" /></td>
                        <td>'.__('Countdown header','lang-kkcountdown').': </td><td style="padding-right: 30px;"><input type="text" id="naglowek_e" /></td>
                        <td>'.__('Date:','lang-kkcountdown').': </td><td style="padding-right: 30px;"><input type="text" id="data_e" class="date-picker" style="width:90px;" /> <span id="kkcd-wstaw-czas-edit"></span></td>
                        <td><a href="#" class="button" onclick="editSaveCount(); return false;" /><img src="'.WP_PLUGIN_URL.'/kkcountdown/images/save.png" alt="+" style="display:inline-block; vertical-align:middle;" /> '.__('Update','lang-kkcountdown').'</a></td>
                            <td><div id="add-loader" style="display: none;"><img src="' . WP_PLUGIN_URL . '/kkcountdown/images/loader.gif" style="vertical-align: middle; margin-left: 10px;" alt="Czekaj..." /></div></td></tr>
                    </table>
                </form>
            </div>
            </div>
            </div>
            ';
        echo '
            <div style="float:left; width: 74%;">
            <div style="text-align:right; margin-top:20px;"><a href="#" class="button add-new-h2" onclick="addCount(); return false;"><img src="'.WP_PLUGIN_URL.'/kkcountdown/images/add_count.png" alt="+" style="display:inline-block; vertical-align:middle;" /> '.__('Add new countdown','lang-kkcountdown').'</a></div>';

        echo '<table class="widefat fixed" id="kkcd-table" cellspacing="0" style="margin-top: 20px;">';
        echo '<thead><tr class="thead">
            <th style="width: 35px;">ID:</th>
            <th style="width: 150px;">'.__('Name','lang-kkcountdown').': </th>
            <th>'.__('Countdown Header','lang-kkcountdown').': </th>
            <th style="width: 150px;">'.__('Date','lang-kkcountdown').': </th>
            <th style="width: 60px;">'.__('Status','lang-kkcountdown').': </th>
            <th colspan="2" style="width: 150px;">'.__('Options','lang-kkcountdown').': </th>
            </tr></thead>';

        foreach($rows as $row) {

            if($row->status == 1){
                $status = '<img src="'.WP_PLUGIN_URL.'/kkcountdown/images/aktywny.png" id="kkc-status-'.$row->id.'" onclick="zmienStatus(\''.$row->id.'\'); return false;" alt="Yes" style="display:inline-block; vertical-align:middle; cursor: pointer;" />';
            }else{
                $status = '<img src="'.WP_PLUGIN_URL.'/kkcountdown/images/nieaktywny.png" id="kkc-status-'.$row->id.'" onclick="zmienStatus(\''.$row->id.'\'); return false;" alt="No" style="display:inline-block; vertical-align:middle; cursor: pointer;" />';
            }

            $data = date('Y-m-d', $row->data);
            $godz = date('H', $row->data);
            $min = date('i', $row->data);
            $sek = date('s', $row->data);
            echo '<tr class="alternate" id="kkcd-row-' . $row->id . '">
                <td>'.$row->id.'</td>
                <td>'.$row->nazwa.'</td>
                <td>'.$row->przed_data.'</td>
                <td>'.$data.' '.$godz.':'.$min.':'.$sek.'</td>
                <td>'.$status.' <span id="loader-status-'.$row->id.'" style="display:none;"><img src="'.WP_PLUGIN_URL.'/kkcountdown/images/small-loader.gif" alt="..." style="display:inline-block; vertical-align:middle;" /><span></td>
                <td><a href="#" onclick="editCount(\''.$row->id.'\',\''.$row->nazwa.'\',\''.$row->przed_data.'\',\''.$data.'\',\''.$godz.'\',\''.$min.'\',\''.$sek.'\'); return false;"><img src="'.WP_PLUGIN_URL.'/kkcountdown/images/edit_count.png" alt="+" style="display:inline-block; vertical-align:middle;" /> '.__('Edit','lang-kkcountdown').'</a></td>
                <td><a href="#" onclick="delCount(\''.$row->id.'\'); return false;"><img src="'.WP_PLUGIN_URL.'/kkcountdown/images/delete_count.png" alt="+" style="display:inline-block; vertical-align:middle;" /> '.__('Delete','lang-kkcountdown').'</a></td>
                </tr>';

        }

        echo '</table></div>';

        echo '
        <div class="metabox-holder" style="float:right; width: 25%; margin-right:5px;">
            <div class="postbox gdrgrid frontright">
                <h3 class="hndle" style="cursor:default;"><span>Info:</span></h3>
                <div class="inside">
                    <div style="margin: 10px 15px;">
                        <div style="margin: 10px 0px;"><span class="kkc-small-text"><strong>' . __('Author', 'lang-kkcountdown') . ':</strong></span> <br /><a href="http://krzysztof-furtak.pl" target="_blank" >Krzysztof Furtak</a> <span class="kkc-small-text">Web Developer</span></div>
                        <div style="margin: 10px 0px;"><span class="kkc-small-text"><strong>' . __('Report bug', 'lang-kkcountdown') . ':</strong></span><br /> <a href="http://krzysztof-furtak.pl/2010/05/wp-kkcountdown-plugin/" target="_blank" >' . __('Plugin website', 'lang-kkcountdown') . '</a></div>
                        <hr />
                        <div style="margin: 10px 0px; font-size: 10px;">
                            <h4>' . __('Legend', 'lang-kkcountdown') . ':</h4>
                            <img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/aktywny.png" alt="Yes" style="vertical-align:middle;" /> - ' . __('Active', 'lang-kkcountdown') . '<br />
                            <img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/nieaktywny.png" alt="Yes" style="vertical-align:middle;" /> - ' . __('Inactive (not displayed)', 'lang-kkcountdown') . '<br />
                        </div>
                    </div>
                </div>
            </div>

            <div class="postbox gdrgrid frontright">
                <h3 class="hndle" style="cursor:default;"><span>' . __('Donation', 'lang-kkcountdown') . ':</span></h3>
                <div class="inside">
                    <div style="margin: 10px 15px;">

                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                            <input type="hidden" name="cmd" value="_s-xclick">
                            <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
                            ">
                            <input type="image" src="http://krzysztof-furtak.pl/upload/buy_coffee.png" border="0" name="submit" alt="Buy me a coffee.">
                            <img alt="" border="0" src="https://www.paypal.com/pl_PL/i/scr/pixel.gif" width="1" height="1">
                            </form>


                        </div>
                    </div>
                </div>
            </div>
         </div>
            ';
        echo '</div>';
        echo '</div>';
    }


}

function kkcd_settings(){

    global $wpdb;
        $table_name = $wpdb->prefix . "kkcdsettings";

        $rows = $wpdb->get_results("SELECT * FROM $table_name");


        echo '<div class="wrap">';
        echo '<div id="icon-options-general" class="icon32"></div><h2>KK Countdown - ' . __("Settings:", "lang-kkcountdown") . '</h2>';
        echo '<hr style="margin-top: 30px; margin-bottom: 20px;" />';

        if ($rows[4]->value == 1) {
            $checked = 'checked="checked"';
        } else {
            $checked = '';
        }

        echo '
        <div id="info"></div>
        <fieldset style="padding: 20px; border: 1px #ccc dashed;"><legend>' . __('General settings:', 'lang-kkcountdown') . '</legend>

            <form action="">
                <span class="kkcd-label" style="width: 250px;">' . __('Countdown – font colour:', 'lang-kkcountdown') . ' </span><span class="kkcd-input">#<input type="text" id="kkcd-kolor" value="' . $rows[0]->value . '" /></span><br />
                <!-- <span class="kkcd-label" style="width: 250px;">' . __('Kolor odliczania < 24h:', 'lang-kkcountdown') . ' </span><span class="kkcd-input">#<input type="text" id="kkcd-kolor-24" value="' . $rows[1]->value . '" /></span><br /> -->
                <span class="kkcd-label" style="width: 250px;">' . __('Text displayed (when number days = 1):', 'lang-kkcountdown') . ' </span><span class="kkcd-input"><input type="text" id="kkcd-text-1-day" value="' . $rows[2]->value . '" /></span><br />
                <span class="kkcd-label" style="width: 250px;">' . __('Text displayed:', 'lang-kkcountdown') . ' </span><span class="kkcd-input"><input type="text" id="kkcd-text-days" value="' . $rows[3]->value . '" /></span><br />
                <span class="kkcd-label" style="width: 250px;">' . __('Displaying the number of days:', 'lang-kkcountdown') . ' </span><span class="kkcd-input"><input type="checkbox" ' . $checked . ' id="kkcd-display-days" /></span><br />
                <span class="kkcd-label" style="width: 250px;">' . __('Add countdown class:', 'lang-kkcountdown') . ' </span><span class="kkcd-input"><input type="text" id="kkcd-class" value="' . $rows[5]->value . '" /></span><br />
                <div style="margin: 20px 0px;">
                    <a href="#" class="button" onclick="kkcdSaveSettings(); return false;" /><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/save.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Save', 'lang-kkcountdown') . '</a><span id="save-loading" style="display: none;"><img src="' . WP_PLUGIN_URL . '/kkcountdown/images/loader.gif" style="vertical-align: middle; margin-left: 10px;" alt="Czekaj..." /></span>
                </div>
            </form>

        </fieldset>
        ';

        echo '</div>';

}
/*koniec admin*/

require_once('ajax.php');

?>
