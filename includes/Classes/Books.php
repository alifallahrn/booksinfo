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
		self::hooks();
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

	public static function hooks()
	{
		add_action('add_meta_boxes_'.self::$post_type, array(__CLASS__, 'add_meta_boxes'));
		add_action('save_post_'.self::$post_type, array(__CLASS__, 'save_post'));
		add_filter('set-screen-option', array(__CLASS__, 'set_screen_option'), 10, 3);
		add_action('admin_menu', array(__CLASS__, 'admin_menu'));
	}

	public static function add_meta_boxes()
	{
		add_meta_box('book-isbn-meta-box', __('Isbn', Books_Info::DOMAIN), array(__CLASS__, 'book_isbn_meta_box'));
	}

	public static function book_isbn_meta_box($post)
	{
		wp_nonce_field('book_isbn_notice_nonce', 'book_isbn_notice_nonce');
		$isbn = self::get_isbn_from_db($post->ID);
		echo '<input type="text" name="_isbn" value="'.$isbn.'" style="width: 100%; text-align: center;" dir="ltr" placeholder="'.__('Enter Book ISBN Here', Books_Info::DOMAIN).'" />';
	}

	public static function save_post($post_id)
	{
		if ( ! isset( $_POST['book_isbn_notice_nonce'] ) ) return;
		if ( ! wp_verify_nonce( $_POST['book_isbn_notice_nonce'], 'book_isbn_notice_nonce' ) ) return;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( ! current_user_can( 'edit_page', $post_id ) ) return;
		if ( ! isset( $_POST['_isbn'] ) ) return;
		$isbn = sanitize_text_field( $_POST['_isbn'] );
		self::save_isbn_to_db($post_id, $isbn);
	}

	public static function save_isbn_to_db($post_id, $isbn)
	{
		global $wpdb;
		$table = $wpdb->prefix.Books_Info::TABLE_NAME;
		$check_isbn = self::get_isbn_from_db($post_id);
		if(!$check_isbn) {
			$wpdb->insert($table, array('post_id' => $post_id, 'isbn' => $isbn));
		} else {
			$wpdb->update($table, array('isbn' => $isbn), array('post_id' => $post_id));
		}
	}

	public static function get_isbn_from_db($post_id)
	{
		global $wpdb;
		$table = $wpdb->prefix.Books_Info::TABLE_NAME;
		$isbn = $wpdb->get_var('SELECT isbn FROM '.$table.' WHERE post_id="'.$post_id.'"');
		return $isbn;
	}

	public static function admin_menu()
	{
		$menu_id = add_submenu_page('edit.php?post_type='.self::$post_type, __('View ISBNs', Books_Info::DOMAIN), __('View ISBNs', Books_Info::DOMAIN), 'manage_options', 'books_isbn', array(__CLASS__, 'books_isbn_page'));
		add_action('load-'.$menu_id, array(__CLASS__, 'screen_option'));
	}

	public function books_isbn_page()
	{
		?>
		<div class="wrap">
			<h2><?= __('List ISBNs', Books_Info::DOMAIN) ?></h2>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-1">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<?php $ISBN = new Helper\ISBN(); ?>
								<?php $ISBN->prepare_items(); ?>
								<?php $ISBN->display() ?>
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
		<?php
	}

	public static function set_screen_option($status, $option, $value)
	{
		return $value;
	}

	public function screen_option()
	{
		$option = 'per_page';
		$args   = [
			'label'   => 'ISBN',
			'default' => 5,
			'option'  => 'isbn_per_page'
		];
		add_screen_option( $option, $args );
	}
}