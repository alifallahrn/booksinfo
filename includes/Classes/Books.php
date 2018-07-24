<?php

namespace Classes;

use Helper, Books_Info;

class Books
{
	public static $post_type = 'book';

	public static function setup()
	{
		self::register_post_type();
		self::register_taxonomy();
	}

	public static function register_post_type()
	{
		Helper\Core::register_post_type(self::$post_type, [
			'name' => __('Books', Books_Info::DOMAIN),
			'singular_name' => __('Book', Books_Info::DOMAIN),
			'menu_name' => __('Book', Books_Info::DOMAIN),
			'all_items' => __('All Books', Books_Info::DOMAIN),
			'add_new' => __('Add New', Books_Info::DOMAIN),
			'add_new_item' => __('Add New Book', Books_Info::DOMAIN),
			'edit_item' => __('Edit Book', Books_Info::DOMAIN),
			'new_item' => __('New Book', Books_Info::DOMAIN),
			'view_item' => __('View Book', Books_Info::DOMAIN),
			'view_items' => __('View Books', Books_Info::DOMAIN),
			'search_items' => __('Search Books', Books_Info::DOMAIN),
			'not_found' => __('Nothing found', Books_Info::DOMAIN),
			'not_found_in_trash' => __('Nothing found in Trash', Books_Info::DOMAIN),
			'items_list' => __('Books List', Books_Info::DOMAIN),
		]);
	}

	public static function register_taxonomy()
	{
		// authors taxonomy
		Helper\Core::register_taxonomy('authors', self::$post_type, [
			'name' => __('Authors', Books_Info::DOMAIN),
			'singular_name' => __('Author', Books_Info::DOMAIN),
			'menu_name' => __('Authors', Books_Info::DOMAIN),
			'all_items' => __('All Authors', Books_Info::DOMAIN),
			'edit_item' => __('Edit Author', Books_Info::DOMAIN),
			'view_item' => __('View Author', Books_Info::DOMAIN),
			'add_new_item' => __('Add New Author', Books_Info::DOMAIN),
			'search_items' => __('Search Authors', Books_Info::DOMAIN),
		]);

		// publishers taxonomy
		Helper\Core::register_taxonomy('publishers', self::$post_type, [
			'name' => __('Publishers', Books_Info::DOMAIN),
			'singular_name' => __('Publisher', Books_Info::DOMAIN),
			'menu_name' => __('Publishers', Books_Info::DOMAIN),
			'all_items' => __('All Publishers', Books_Info::DOMAIN),
			'edit_item' => __('Edit Publisher', Books_Info::DOMAIN),
			'view_item' => __('View Publisher', Books_Info::DOMAIN),
			'add_new_item' => __('Add New Publisher', Books_Info::DOMAIN),
			'search_items' => __('Search Publishers', Books_Info::DOMAIN),
		]);
	}
}