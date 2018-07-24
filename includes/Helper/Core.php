<?php
namespace Helper;

class Core
{
	public static function register_post_type($name, $labels = array(), $options = array())
	{
		$defaults = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_rest' => true,
			'has_archive' => true,
			'show_in_menu' => true,
			'exclude_from_search' => true,
			'capability_type' => 'post',
			'map_meta_cap' => true,
			'hierarchical' => true,
			'rewrite' => true,
			'supports' => array('title', 'editor', 'thumbnail'),
		);
		$args = wp_parse_args($options, $defaults);
		$test = register_post_type($name, $args);
	}

	public static function register_taxonomy($name, $post_type, $labels = array(), $options = array())
	{
		$defaults = array(
			'labels' => $labels,
			'public' => true,
			'hierarchical' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'query_var' => true,
			'show_admin_column' => false,
			'show_in_rest' => false,
			'show_in_quick_edit' => false,
		);
		$args = wp_parse_args($options, $defaults);
		register_taxonomy($name, $post_type, $args);
	}
}