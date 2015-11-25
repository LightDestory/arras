<?php
/**
 * autoloader.php
 *
 * A simple class autoloader.
 *
 * @author: Caspar Green <https://caspar.green>
 * @package: Arras
 * @version: 1.0.0
 */

namespace Arras;

// Oh no you di'n't
if ( ! defined ( 'ABSPATH' ) ) {
	exit( 'Oh no you di\'n\'t' );
}

/**
 * Class autoloader
 * @param  string $class fully qualified class name
 * @return null
 */
function autoloader( $class ) {
	$namespace_parts = explode( '\\', $class );
	$file_parts = array_map( __NAMESPACE__ . '\\convert_namespace_part_to_file_part', $namespace_parts);
	$file = get_theme_root() . '/' . implode( DIRECTORY_SEPARATOR, $file_parts ) . '-class.php';
	if ( file_exists( $file ) ) {
		require_once $file;
	}
}

/**
 * Convert namespace part to file part
 * @param  string $part namespace part
 * @return string       filename part
 */
function convert_namespace_part_to_file_part( $part ) {
	return str_replace( '_', '-', strtolower( $part ) );
}

spl_autoload_register( __NAMESPACE__ . '\\autoloader' );
