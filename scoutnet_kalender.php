<?php
/*
  Plugin Name: Scoutnet Kalender
  Plugin URI: http://www.scoutnet.de/
  Description: Plugin to include image galleries from Dropbox folders.
  Version: 0.1.0
  Author: Scoutnet
  Author URI: http://www.scoutnet.de/
  Text Domain: scoutnet_kalender
  License: TODO
 */

class ScoutnetKalender {

    public static $VERSION = '0.0.1';
    public static $SNK_DIR;
    public static $SNK_URL;

    public function __construct() {

        // define some statics
        ScoutnetKalender::$SNK_DIR = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'scoutnet-kalender'; //plugin_basename( dirname(__file__) );
        ScoutnetKalender::$SNK_URL = WP_PLUGIN_URL . DIRECTORY_SEPARATOR . 'scoutnet-kalender'; //plugin_basename( dirname(__file__) );
        // hook to actions
        add_action('init', array(&$this, 'init'));
        add_action('admin_menu', array(&$this, 'admin_menu'));
        add_action('admin_init', array(&$this, 'admin_init'));

        // shortcodes
        add_shortcode('snk', array(&$this, 'inline_kalender'));

        // css an js
        //if (!is_admin()) {
            wp_enqueue_style('scoutnet_kalender_css', ScoutnetKalender::$SNK_URL . '/css/scoutnet_kalender.css', false, ScoutnetKalender::$VERSION, 'all');
            wp_enqueue_script('jquery');
            wp_enqueue_script('scoutnet_kalender_js', ScoutnetKalender::$SNK_URL . '/js/scoutnet_kalender.js');
            wp_enqueue_script('jquery_autocomplete', ScoutnetKalender::$SNK_URL . '/js/jquery.autocomplete.min.js');
        //}

        // widget
        add_action('widgets_init', create_function('', 'return register_widget("ScoutnetKalenderWidget");'));

    }

    public function init() {
        $locale_dir = basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'i18n';
        load_plugin_textdomain('scoutnet_kalender', null, $locale_dir);
    }

    public function admin_init() {
        register_setting('snk-opt', 'scoutnet_kalender_ssid');
    }

    public function admin_menu() {
        add_options_page("Scoutnet Kalender", "Scoutnet Kalender", 'activate_plugins', 'scoutnet-kalender/options.php');
    }

    public function inline_kalender($attr) {
        $ssid = isset($attr['ssid']) ? $attr['ssid'] : get_option('scoutnet_kalender_ssid');

        $events = ScoutnetKalender::getSnEvents($ssid);

        $buffer = ob_start();
        include('templates/inline_kalender_list.php');
        return ob_get_flush();
    }

    public function activation_hook() {
        
    }

    public static function getSnEvents($ssid) {
        require_once 'lib/scoutnet_webservice/class.tx_shscoutnetwebservice_sn.php';

        $SN = new tx_shscoutnetwebservice_sn();

        $filter = array(
            'limit' => '40',
            'after' => 'now()',
        );

        $ids = array($ssid);

        $events = $SN->get_events_for_global_id_with_filter($ids, $filter);
        return $events;
    }

}

class ScoutnetKalenderWidget extends WP_Widget {

    function ScoutnetKalenderWidget() {
        $widget_ops = array('classname' => 'ScoutnetKalenderWidget', 'description' => 'Anzeige von Scoutnet-Kalendern');
        $this->WP_Widget('ScoutnetKalenderWidget', 'Scoutnet Kalender', $widget_ops);
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => '', 'ssid' => '3'));
        $title = $instance['title'];
        $ssid = $instance['ssid'];
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Titel: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label><br />
            <label for="<?php echo $this->get_field_id('ssid'); ?>">Kalender-ID: <input class="widefat snk_ssid_chooser" id="<?php echo $this->get_field_id('ssid'); ?>" name="<?php echo $this->get_field_name('ssid'); ?>" type="text" value="<?php echo attribute_escape($ssid); ?>" /></label>
        </p>
<?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['ssid'] = $new_instance['ssid'];
        return $instance;
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
        $ssid = empty($instance['ssid']) ? '3' : $instance['ssid'];

        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

        $events = ScoutnetKalender::getSnEvents($ssid);
        include 'templates/widget_kalender_list.php';
        echo $after_widget;
    }

}

$snk = new ScoutnetKalender();
register_activation_hook(__FILE__, array(&$snk, 'activation_hook'));
?>
