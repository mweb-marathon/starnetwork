<?php
$leaderboard_ad_rotate_id = get_option('select-leaderboard');
$explodeId = explode(' ', $leaderboard_ad_rotate_id);
if (count($explodeId) > 1) {
    $id = $explodeId[1];
    ?>
    <div class="banner">
        <?php
        if ($explodeId[0] == 'Ad') {
            echo adrotate_ad($id);
        } else {
            $group_ad = schneps_adrotate_group($id, true);
            if (!empty($group_ad)) {
                $matches = array();
                preg_match('%<a(.*)/a>%', $group_ad, $matches);
                echo $matches[0];
            }
        }
        ?>
    </div>
<?php } ?>