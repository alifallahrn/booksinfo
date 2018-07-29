<?php

namespace Helper;

use Classes\Books;
use Books_Info;
use WP_List_Table;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class ISBN extends WP_List_Table
{
	public function __construct()
	{
		parent::__construct( [
			'singular' => __('ISBN', Books_Info::TABLE_NAME),
			'plural' => __('ISBNs', Books_Info::DOMAIN),
			'ajax' => false
		]);
	}

	public static function get_isbn($per_page = 5, $page_number = 1)
	{
		global $wpdb;
		$table = $wpdb->prefix.Books_Info::TABLE_NAME;
		$sql = "SELECT * FROM {$table}";
		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}
		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		return $result;
	}

	public static function delete_isbn($id)
	{
		global $wpdb;
		$table = $wpdb->prefix.Books_Info::TABLE_NAME;
		$wpdb->delete($table, array('id' => $id));
	}

	public static function record_count() {
		global $wpdb;
		$table = $wpdb->prefix.Books_Info::TABLE_NAME;
		return $wpdb->get_var("SELECT COUNT(*) FROM {$table}");
	}

	public function no_items()
	{
		_e('Nothing found', Books_Info::DOMAIN);
	}

	function get_columns()
	{
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'isbn' => __( 'ISBN', Books_Info::DOMAIN),
			'book' => __( 'Book', Books_Info::DOMAIN),
		);
		return $columns;
	}

	function column_cb($item)
	{
		return sprintf('<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']);
	}

	function column_isbn($item)
	{
		$delete_nonce = wp_create_nonce('delete_isbn');
		$actions = array('delete' => sprintf('<a href="?post_type='.Books::$post_type.'&page=%s&action=%s&isbn=%s&_wpnonce=%s">'.__('Delete', \Books_Info::DOMAIN).'</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce ));
		return $item['isbn'] . $this->row_actions( $actions );
	}

	function column_book($item)
	{
		$post = get_post($item['post_id']);
		return '<a href="'.admin_url('post.php?post='.$post->ID.'&action=edit').'">' . $post->post_title . '</a>';
	}

	public function get_sortable_columns() {
		$sortable_columns = array();
		return $sortable_columns;
	}

	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => __('Delete', Books_Info::DOMAIN)
		];
		return $actions;
	}

	function prepare_items()
	{
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->process_bulk_action();
		$per_page     = $this->get_items_per_page('isbn_per_page', 5);
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();
		$this->set_pagination_args(array(
			'total_items' => $total_items,
			'per_page'    => $per_page
		));
		$this->items = self::get_isbn($per_page, $current_page);
	}

	function process_bulk_action()
	{
		if ( 'delete' === $this->current_action() ) {
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );
			if ( ! wp_verify_nonce($nonce, 'delete_isbn')) {
				die(__('Sorry, you are not allowed to access this page'));
			} else {
				self::delete_isbn( absint( $_GET['isbn'] ) );
				wp_redirect(admin_url('edit.php?post_type='.Books::$post_type.'&page=books_isbn'));
				exit;
			}
		}
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )  || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )) {
			$delete_ids = esc_sql( $_POST['bulk-delete'] );
			foreach ( $delete_ids as $id ) {
				self::delete_isbn( $id );
			}
			wp_redirect(admin_url('edit.php?post_type='.Books::$post_type.'&page=books_isbn'));
			exit;
		}
	}
}