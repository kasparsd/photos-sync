<?php

function list_photos( $path ) {
	$photos = array();

	foreach ( glob( $path . '/*' ) as $file )
		if ( is_dir( $file ) )
			$photos = array_merge( $photos, list_photos( $file ) );
		else
			$photos[ $file ] = sprintf( '%s-%s', md5( $file ), basename( $file ) );

	return $photos;
}

$linked = array();
$links_dir = dirname( __FILE__ ) . '/links-alternative';

if ( ! is_dir( $links_dir ) )
	if ( ! mkdir( $links_dir ) )
		die( 'Failed to create the links directory.' );

$photos = list_photos( $_SERVER['HOME'] . '/Pictures/Photos Library.photoslibrary/Masters/' );

// Delete all existing symlinks and mark the rest as done
foreach ( glob( $links_dir . '/*' ) as $link )
	if ( ! file_exists( $link ) )
		unlink( $link );
	else
		$linked[] = basename( $link );

// Symlink only the new ones
foreach ( $photos as $path => $name )
	if ( ! in_array( $name, $linked ) )
		symlink( $path, $links_dir . '/' . $name );
