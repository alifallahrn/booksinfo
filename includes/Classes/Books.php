<?php

namespace Classes;

use Helper;

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
			'name' => __('Books', 'books-info'),
			'singular_name' => __('Book', 'books-info'),
			'menu_name' => __('Book', 'books-info'),
			'all_items' => __('All Books', 'books-info'),
			'add_new' => __('Add New', 'books-info'),
			'add_new_item' => __('Add New Book', 'books-info'),
			'edit_item' => __('Edit Book', 'books-info'),
			'new_item' => __('New Book', 'books-info'),
			'view_item' => __('View Book', 'books-info'),
			'view_items' => __('View Books', 'books-info'),
			'search_items' => __('Search Books', 'books-info'),
			'not_found' => __('Nothing found', 'books-info'),
			'not_found_in_trash' => __('Nothing found in Trash', 'books-info'),
			'items_list' => __('Books List', 'books-info'),
		]);
	}

	public static function register_taxonomy()
	{
		// authors taxonomy
		Helper\Core::register_taxonomy('authors', self::$post_type, [
			'name' => __('Authors', 'books-info'),
			'singular_name' => __('Author', 'books-info'),
			'menu_name' => __('Authors', 'books-info'),
			'all_items' => __('All Authors', 'books-info'),
			'edit_item' => __('Edit Author', 'books-info'),
			'view_item' => __('View Author', 'books-info'),
			'add_new_item' => __('Add New Author', 'books-info'),
			'search_items' => __('Search Authors', 'books-info'),
		]);

		// publishers taxonomy
		Helper\Core::register_taxonomy('publishers', self::$post_type, [
			'name' => __('Publishers', 'books-info'),
			'singular_name' => __('Publisher', 'books-info'),
			'menu_name' => __('Publishers', 'books-info'),
			'all_items' => __('All Publishers', 'books-info'),
			'edit_item' => __('Edit Publisher', 'books-info'),
			'view_item' => __('View Publisher', 'books-info'),
			'add_new_item' => __('Add New Publisher', 'books-info'),
			'search_items' => __('Search Publishers', 'books-info'),
		]);
	}
}