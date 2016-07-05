<?php

/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 3/13/15 | 4:33 PM
 */
class sponsored_story extends WP_Widget
{
    public $widget_name = 'sponsored_story';
    public $widget_public_name = 'Sponsored Story For Left Sidebar';


    function __construct()
    {
        parent::__construct($this->widget_name, __($this->widget_public_name, $this->widget_name . '_widget'), array('description' => __('Show ' . $this->widget_name . ' ' . $this->widget_public_name, $this->widget_name . '_widget')));
    }

    public function widget($args, $instance)
    {        
        $_5_ads_or_long_pic = get_option('star-network-sidebar-choice-5-ads-or-long-pic');
        $_5_ads_or_long_pic  = (empty($_5_ads_or_long_pic) ? '5-pic' : $_5_ads_or_long_pic);
        
        $ad_rotate_data = array();
        
        if ($_5_ads_or_long_pic == '5-pic') {
            $sponsored_id_1 = get_option('star-network-listing-single-adrotate-sponsored-story-1');
            $sponsored_id_2 = get_option('star-network-listing-single-adrotate-sponsored-story-2');
            $sponsored_id_3 = get_option('star-network-listing-single-adrotate-sponsored-story-3');
            $sponsored_id_4 = get_option('star-network-listing-single-adrotate-sponsored-story-4');
            $sponsored_id_5 = get_option('star-network-listing-single-adrotate-sponsored-story-5');
            
            if ($sponsored_id_1) {
                $ad_rotate_data[] = $sponsored_id_1;
            }

            if ($sponsored_id_2) {
                $ad_rotate_data[] = $sponsored_id_2;
            }

            if ($sponsored_id_3) {
                $ad_rotate_data[] = $sponsored_id_3;
            }

            if ($sponsored_id_4) {
                $ad_rotate_data[] = $sponsored_id_4;
            }

            if ($sponsored_id_5) {
                $ad_rotate_data[] = $sponsored_id_5;
            }
        } else {
            $long_pic = get_option('star-network-listing-single-adrotate-sponsored-story-long-pic');
            if ($long_pic) {
                $ad_rotate_data[] = $long_pic;
            }            
        }

        ?>


        <div class="sponsored-story-widget-wrapper custom-widget">
<!--            <div class="large-12 columns top-header">
                <span class="left sponsored-text">
                    sponsored
                </span>
                <span class="right sponsored-advertise-link">
                    <a href="">
                        Advertise
                    </a>
                </span>
            </div>-->
            <?php if (count($ad_rotate_data) > 0): ?>
                <div class="large-12 columns sponsored-story-content">
                    <?php foreach ($ad_rotate_data as $k => $single_sponsored_story):
                        echo schneps_get_adrotate_($single_sponsored_story); ?>
                        <?php if ($k < count($ad_rotate_data) - 1 && count($ad_rotate_data) > 1): ?>
                        <div class="delimeter">
                            <hr/>
                        </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }


    function form($instance)
    {

    }

    // update widget
    function update($new_instance, $old_instance)
    {

    }
}

function sponsored_story_load_widget()
{
    register_widget('sponsored_story');
}

add_action('widgets_init', 'sponsored_story_load_widget');