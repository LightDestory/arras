<?php
/**
 * Repository.php
 *
 * [Description]
 *
 * @author: Caspar Green <https://caspar.green>
 * @package: Arras
 * @version: 1.0.0
 */

namespace Arras\Library;

// Oh no you di'n't
if ( ! defined ( 'ABSPATH' ) ) {
	exit( 'Oh no you di\'n\'t' );
}

class Repository {

	/**
	 * WordPress querey object.
	 *
	 * @var WP_Query
	 */
	private $query;

	/**
	 * Constructor.
	 *
	 * @param \WP_Query $query
	 */
	public function __construct( \WP_Query $query ) {
		$this->query = $query;
	}

	/**
	 * Initialize the Repository.
	 *
	 * @return Repository
	 */
	public static function init() {
		return new self( new \WP_Query() );
	}

	/**
	 * Save a post to the repository. Returns the post ID or a WP_Error.
	 *
	 * @param array $post
	 *
	 * @return int|\WP_Error
	 */
	public function save( array $post ) {
		if ( ! empty( $post['ID'] ) ) {
			return wp_update_post( $post, true );
		}

		return wp_insert_post( $post, true );
	}

	/**
	 * Remove a given post from the repository.
	 *
	 * @param WP_Post $post
	 * @param bool|false $force
	 */
	public function remove( WP_Post $post, $force = false ) {
		wp_delete_post( $post->ID, $force );
	}

	/**
	 * Find a post using the given post ID.
	 *
	 * @param $id
	 *
	 * @return WP_Post|null
	 */
	public function find_by_id( $id ) {
		return $this->find_single( array( 'p' => $id ) );
	}

	/**
	 * Find posts by a given author.
	 *
	 * @param \WP_User $author
	 * @param int $limit
	 *
	 * @return WP_Post[]
	 */
	public function find_by_author( \WP_User $author, $limit = 10 ) {
		return $this->find( array(
			'author'         => $author->ID,
			'posts_per_page' => $limit
		) );
	}

	/**
	 * Find posts in a given category.
	 * Will find multiple categories by passing a string of comma-separated category ids.
	 *
	 * @param $category_id
	 * @param int $limit
	 *
	 * @return WP_Post[]
	 */
	public function find_by_category( $category_id, $limit = 10 ) {
		return $this->find( array(
			'cat'            => $category_id,
			'posts_per_page' => $limit
		) );
	}

	/**
	 * Find all post objects for the given query.
	 *
	 * @param array $query_arguments
	 *
	 * @return WP_Post[]
	 */
	private function find( array $query_arguments ) {
		$query_arguments = array_merge( array(
			'no_found_rows'          => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => false
		), $query_arguments );

		return $this->query->query( $query_arguments );
	}

	/**
	 * Find a single post object for the given query. Return null if nothing is found.
	 *
	 * @param array $query_arguments
	 *
	 * @return WP_Post[]|null
	 */
	private function find_single( array $query_arguments ) {
		$query_arguments = array_merge( $query_arguments, array(
			'posts_per_page' => 1
		) );

		$posts = $this->find( $query_arguments );

		return ! empty( $posts[0] ) ? $posts : null;
	}


}