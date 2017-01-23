<?php
/**
 * Plugin Name:     Wordpress Simple Pagination
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     wordpress-simple-pagination
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Wordpress_Simple_Pagination
 */

/**
 * Interface Pagination
 */
interface Pagination {
	/**
	 * Return whether has multiple pages
	 *
	 * @return boolean
	 */
	public function has_pagination();

	/**
	 * Return item counts per page
	 *
	 * @return integer
	 */
	public function get_items_per_page_number();

	/**
	 * Return total page number
	 *
	 * @return integer
	 */
	public function get_total_pages_number();

	/**
	 * Return current page number
	 *
	 * @return integer
	 */
	public function get_current_page_number();

	/**
	 * Return whether previous page exists
	 *
	 * @return boolean
	 */
	public function has_prev();

	/**
	 * Return previous page URL
	 *
	 * @return string
	 */
	public function get_prev_url();

	/**
	 * Return whether next page exists
	 *
	 * @return boolean
	 */
	public function has_next();

	/**
	 * Return next page URL
	 *
	 * @return string
	 */
	public function get_next_url();

	/**
	 * Return array that smaller numbers than current
	 *
	 * @param integer $amount Number of smaller pages.
	 *
	 * @return array
	 */
	public function get_smaller_numbers_array( $amount );

	/**
	 * Return array that larger numbers than current
	 *
	 * @param integer $amount Number of larger pages.
	 *
	 * @return array
	 */
	public function get_larger_numbers_array( $amount );

	/**
	 * Return array that smaller numbers than current without first page number
	 *
	 * @param integer $amount Number of smaller pages without first.
	 *
	 * @return array
	 */
	public function get_smaller_numbers_array_without_first( $amount );

	/**
	 * Return array that smaller numbers than current without last page number
	 *
	 * @param integer $amount Number of larger pages without last.
	 *
	 * @return array
	 */
	public function get_larger_numbers_array_without_last( $amount );

	/**
	 * Return specified page URL
	 *
	 * @param integer $page Index of page.
	 *
	 * @return string
	 */
	public function get_page_url( $page );
}

/**
 * Class SimplyWordPressPagination
 */
class SimplyWordPressPagination implements Pagination {

	/**
	 * Number of posts on a page
	 *
	 * @var int
	 */
	private $__posts_per_page;
	/**
	 * Number of current page
	 *
	 * @var int
	 */
	private $__paged;
	/**
	 * Number of total pages
	 *
	 * @var int
	 */
	private $__total_pages;

	/**
	 * SimplyWordPressPagination constructor.
	 */
	public function __construct() {
		global $wp_query;

		// WP_Query.
		$this->__posts_per_page = intval( $wp_query->get( 'posts_per_page' ) );
		$this->__paged          = (int) max( 1, absint( $wp_query->get( 'paged' ) ) );
		$this->__total_pages    = (int) max( 1, absint( $wp_query->max_num_pages ) );
	}

	/**
	 * Return number of posts on a page
	 *
	 * @return int
	 */
	public function get_items_per_page_number() {
		return $this->__posts_per_page;
	}

	/**
	 * Return number of total pages
	 *
	 * @return int
	 */
	public function get_total_pages_number() {
		return $this->__total_pages;
	}

	/**
	 * Return number of current page
	 *
	 * @return int
	 */
	public function get_current_page_number() {
		return $this->__paged;
	}

	/**
	 * Return true if pagination can
	 *
	 * @return boolean
	 */
	public function has_pagination() {
		return $this->get_total_pages_number() > 1 ? true : false;
	}

	/**
	 * Return true if has prev page
	 *
	 * @return boolean
	 */
	public function has_prev() {
		return $this->get_current_page_number() > 1 ? true : false;
	}

	/**
	 * Return prev url, if missing prev page return false
	 *
	 * @return string
	 */
	public function get_prev_url() {
		if ( $this->has_prev() ) {
			return get_pagenum_link( $this->get_current_page_number() - 1 );
		}

		return false;
	}

	/**
	 * Return true if has next page
	 *
	 * @return boolean
	 */
	public function has_next() {
		return $this->get_current_page_number() < $this->get_total_pages_number() ? true : false;
	}

	/**
	 * Return next url, if missing next page return false
	 *
	 * @return string
	 */
	public function get_next_url() {
		if ( $this->has_next() ) {
			return get_pagenum_link( $this->get_current_page_number() + 1 );
		}
	}

	/**
	 * Return array include smaller numbers
	 *
	 * @param int $amount Number of array size.
	 *
	 * @return array
	 */
	public function get_smaller_numbers_array( $amount ) {
		$numbers = array();
		$current = $this->get_current_page_number();
		for ( $i = 1; $i <= $amount; $i ++ ) {
			if ( $current - $i > 0 ) {
				$numbers[] = $current - $i;
			}
		}
		krsort( $numbers );

		return $numbers;
	}

	/**
	 * Return array include smaller numbers without first
	 *
	 * @param int $amount Number of array size.
	 *
	 * @return array
	 */
	public function get_smaller_numbers_array_without_first( $amount ) {
		$numbers = array();
		$current = $this->get_current_page_number();
		for ( $i = 1; $i <= $amount; $i ++ ) {
			if ( $current - $i > 1 ) {
				$numbers[] = $current - $i;
			}
		}
		krsort( $numbers );

		return $numbers;
	}

	/**
	 * Return array include larger numbers
	 *
	 * @param int $amount Number of array size.
	 *
	 * @return array
	 */
	public function get_larger_numbers_array( $amount ) {
		$numbers = array();
		$current = $this->get_current_page_number();
		$max     = $this->get_total_pages_number();
		for ( $i = 1; $i <= $amount; $i ++ ) {
			if ( $current + $i <= $max ) {
				$numbers[] = $current + $i;
			}
		}

		return $numbers;
	}

	/**
	 * Return array include larger numbers without last
	 *
	 * @param int $amount Number of array size.
	 *
	 * @return array
	 */
	public function get_larger_numbers_array_without_last( $amount ) {
		$numbers = array();
		$current = $this->get_current_page_number();
		$max     = $this->get_total_pages_number();
		for ( $i = 1; $i <= $amount; $i ++ ) {
			if ( $current + $i < $max ) {
				$numbers[] = $current + $i;
			}
		}

		return $numbers;
	}

	/**
	 * Return page link
	 *
	 * @param int $page Index of page.
	 *
	 * @return string
	 */
	public function get_page_url( $page ) {
		return get_pagenum_link( $page );
	}
}

