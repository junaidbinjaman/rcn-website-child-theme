<?php
/**
 * This is the php markup file
 *
 * @package rcn-child
 */

use Sabberworm\CSS\Value\Value;

$arr = array( 1, 2, 3, 4, 5 );

$arr = array_filter(
	$arr,
	function ( $value ) {
		return 2 !== $value;
	}
);

var_dump( $arr );
