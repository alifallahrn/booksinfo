<?php
/**
 * Plugin Name: Books Info
 * Description: A Simple WordPress Plugin For Display Books Info
 * Plugin URI:  https://lahijweb.com
 * Version:     1.0
 * Author:      Ali Fallah
 * Text Domain: books-info
 * Domain Path: /languages
 */

add_action('plugins_loaded', array(Books_Info::get_instance(), 'plugin_setup'));

class Books_Info
{
    protected static $instance = NULL;
    public $plugin_url = '';
    public $plugin_path = '';

    public static function get_instance()
    {
        NULL === self::$instance and self::$instance = new self;
        return self::$instance;
    }

    public function plugin_setup()
    {
        $this->plugin_url = plugins_url('/', __FILE__);
        $this->plugin_path = plugin_dir_path(__FILE__);
        $this->load_language('books-info');
        spl_autoload_register(array($this, 'autoload'));
        add_action('init', array($this, 'init'));
    }

	public function init()
	{
		Classes\Books::setup();
    }

    public function load_language($domain)
    {
        load_plugin_textdomain($domain, FALSE, $this->plugin_path . '/languages');
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