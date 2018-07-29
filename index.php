<?php
/**
 * Plugin Name: Books Info
 * Description: A Simple WordPress Plugin For Display Books Info
 * Plugin URI:  https://lahijweb.com
 * Version:     1.0
 * Author:      Ali Fallah
 * Text Domain: books
 * Domain Path: /languages/
 */

add_action('plugins_loaded', array(Books_Info::get_instance(), 'plugin_setup'));
register_activation_hook(__FILE__, array(Books_Info::get_instance(), 'activation_hook'));

class Books_Info
{
    protected static $instance = NULL;
	const DOMAIN = 'books';
	const TABLE_NAME = 'books_info';
    public $plugin_url = '';
    public $plugin_path = '';
    public $languages_path = '';

    public static function get_instance()
    {
        NULL === self::$instance and self::$instance = new self;
        return self::$instance;
    }

    public function plugin_setup()
    {
        $this->plugin_url = plugins_url('/', __FILE__);
        $this->plugin_path = plugin_dir_path(__FILE__);
        $this->languages_path = basename($this->plugin_path).'/languages';
        $this->load_language(Books_Info::DOMAIN);
        spl_autoload_register(array($this, 'autoload'));
        add_action('init', array($this, 'init'));
    }

    public function activation_hook()
    {
	    global $wpdb;
	    $charset_collate = $wpdb->get_charset_collate();
	    $table_name = $wpdb->prefix.Books_Info::TABLE_NAME;
	    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id int(11) NOT NULL AUTO_INCREMENT,
			post_id int(11) NOT NULL,
			isbn varchar(255) DEFAULT '' NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";
	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    dbDelta($sql);
    }

	public function init()
	{
		Classes\Books::setup();
    }

    public function load_language($domain)
    {
        load_plugin_textdomain($domain, FALSE, $this->languages_path);
    }

    public function autoload($class)
    {
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        if (!class_exists($class)) {
            $class_full_path = $this->plugin_path . 'includes/' . $class . '.php';
            if (file_exists($class_full_path)) {
            	require $class_full_path;
            }
        }
    }
}