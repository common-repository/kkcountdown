<?php
add_action('widgets_init', create_function('', 'return register_widget("kkcountdown");'));
add_shortcode('kkcountdown', 'bartag_func');

class kkcountdown extends WP_Widget {
    function kkcountdown() {
        // widget actual processes
        parent::WP_Widget(false, $name = 'KKCountdown');
    }

    function form($instance) {
        // outputs the options form on admin

        $title = esc_attr($instance['title']);
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
        <?php 

    }

    function update($new_instance, $old_instance) {
        // processes widget options to be saved
        return $new_instance;
    }

    function widget($args, $instance) {
        // outputs the content of the widget

        global $wpdb;
        $table_name = $wpdb->prefix."kkcountdown";

        $sql = "SELECT * FROM $table_name";
        $wyniki = $wpdb->get_results($sql,ARRAY_A);

        $table_settings = $wpdb->prefix . "kkcdsettings";
        $rows = $wpdb->get_results("SELECT * FROM $table_settings");
        
        $i = 0;

        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        echo $before_widget;
        echo $before_title;
        echo $title;
        echo $after_title;

        foreach ($wyniki as $wynik) {
            if($wynik['status'] == 1) {
                if($rows[4]->value == 1){
                echo '
            <div style="margin-bottom: 10px;">
                <div style="border-bottom: 1px #ccc solid;">'.$wynik['przed_data'].'</div>
                <div style="text-align:right;">
                    <div id="count'.$i.'" class="kkcount-down '.$rows[5]->value.'" time="'.$wynik['data'].'" style="font-size:16px; font-weight: bold; color: #'.$rows[0]->value.';">
                            
                                <span class="kkc-dni"></span> <span class="kkcd-day-text" style="display:none;">'.$rows[2]->value.'</span><span class="kkcd-days-text" style="display:none;">'.$rows[3]->value.'</span>
                                <span class="kkc-godz"></span> :
                                <span class="kkc-min"></span> :
                                <span class="kkc-sec"></span>
                            
                    </div>
                </div>
            </div>
            ';
                }else{
                    echo '
            <div style="margin-bottom: 10px;">
                <div style="border-bottom: 1px #ccc solid;">'.$wynik['przed_data'].'</div>
                <div style="text-align:right;">
                    <div id="count'.$i.'" class="kkcount-down  '.$rows[5]->value.'" time="'.$wynik['data'].'" style="font-size:16px; font-weight: bold; color: #'.$rows[0]->value.';">
                            
                                <span class="kkc-godz"></span> :
                                <span class="kkc-min"></span> :
                                <span class="kkc-sec"></span>
                            
                    </div>
                </div>
            </div>
            ';
                }
            }

            $i++;
        }

        echo $after_widget;

    }

}


function bartag_func($atts) {
    extract(shortcode_atts(array(
            'idkkc' => 'noid',
            'kkchead' => 0,
            ), $atts));

    if($idkkc != '' && $idkkc != 'noid') {
        global $wpdb;
        $table_name = $wpdb->prefix."kkcountdown";

        $sql = "SELECT * FROM $table_name WHERE id = '$idkkc'";
        $wynik = $wpdb->get_row($sql,ARRAY_A);

        $table_settings = $wpdb->prefix . "kkcdsettings";
        $rows = $wpdb->get_results("SELECT * FROM $table_settings");
        $i = 0;

        if($kkchead == 1) {
             if($rows[4]->value == 1){
                return '
            <div style="margin-bottom: 10px;">
                <div style="border-bottom: 1px #ccc solid;">'.$wynik['przed_data'].'</div>
                <div style="text-align:right;">
                    <div id="count'.$i.'" class="kkcount-down '.$rows[5]->value.'" time="'.$wynik['data'].'" style="font-size:16px; font-weight: bold; color: #'.$rows[0]->value.';">

                                <span class="kkc-dni"></span> <span class="kkcd-day-text" style="display:none;">'.$rows[2]->value.'</span><span class="kkcd-days-text" style="display:none;">'.$rows[3]->value.'</span>
                                <span class="kkc-godz"></span> :
                                <span class="kkc-min"></span> :
                                <span class="kkc-sec"></span>

                    </div>
                </div>
            </div>
            ';
                }else{
                    return '
            <div style="margin-bottom: 10px;">
                <div style="border-bottom: 1px #ccc solid;">'.$wynik['przed_data'].'</div>
                <div style="text-align:right;">
                    <div id="count'.$i.'" class="kkcount-down  '.$rows[5]->value.'" time="'.$wynik['data'].'" style="font-size:16px; font-weight: bold; color: #'.$rows[0]->value.';">

                                <span class="kkc-godz"></span> :
                                <span class="kkc-min"></span> :
                                <span class="kkc-sec"></span>

                    </div>
                </div>
            </div>
            ';
                }
        }else if($kkchead == 0) {
            if($rows[4]->value == 1){
                return '
            <div style="margin-bottom: 10px;">
                <div style="text-align:right;">
                    <div id="count'.$i.'" class="kkcount-down '.$rows[5]->value.'" time="'.$wynik['data'].'" style="font-size:16px; font-weight: bold; color: #'.$rows[0]->value.';">

                                <span class="kkc-dni"></span> <span class="kkcd-day-text" style="display:none;">'.$rows[2]->value.'</span><span class="kkcd-days-text" style="display:none;">'.$rows[3]->value.'</span>
                                <span class="kkc-godz"></span> :
                                <span class="kkc-min"></span> :
                                <span class="kkc-sec"></span>

                    </div>
                </div>
            </div>
            ';
                }else{
                    return '
            <div style="margin-bottom: 10px;">
                <div style="text-align:right;">
                    <div id="count'.$i.'" class="kkcount-down  '.$rows[5]->value.'" time="'.$wynik['data'].'" style="font-size:16px; font-weight: bold; color: #'.$rows[0]->value.';">

                                <span class="kkc-godz"></span> :
                                <span class="kkc-min"></span> :
                                <span class="kkc-sec"></span>

                    </div>
                </div>
            </div>
            ';
                }
        }
    }

}

?>
