<div class="wrap">
    <h2><?php _e('Scoutnet Kalender', 'scoutnet_kalender'); ?></h2>

    <p><?php _e('Die Kalender werden über die Scoutnet-ID angezeigt.', 'scoutnet_kalender'); ?></p>

    <form method="post" action="options.php">
        <?php settings_fields('snk-opt'); ?>
        <table class="form-table">

            <tr valign="top">
                <th scope="row"><?php _e('Scoutnet-ID', 'scoutnet_kalender'); ?></th>
                <td><input type="text" name="scoutnet_kalender_ssid" value="<?php echo get_option('scoutnet_kalender_ssid'); ?>" />
                    <span class="description"><?php _e('ID des Kalenders', 'scoutnet_kalender'); ?></span>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>
    </form>

</div>