<?php

date_default_timezone_set( exec( 'date +%Z' ) );

$photos_dir = $_SERVER['HOME'] . '/Pictures/Photos Library.photoslibrary';
$photos_library = $photos_dir . '/database/Library.apdb';
$photos_library_local = dirname( __FILE__ ) . '/Library.apdb';
$links_dir = dirname( __FILE__ ) . '/links';

// Store all symlinks in a sub-directory
if ( ! is_dir( $links_dir ) )
	mkdir( $links_dir );

// Move the Photos library here to avoid locking and permission issues
if ( file_exists( $photos_library_local ) )
	unlink( $photos_library_local );

copy( $photos_library, $photos_library_local );

$db = new PDO( 'sqlite:' . $photos_library_local );

$query = $db->query(
	'SELECT
		imagePath as path,
		( imageDate + 978307200 ) as timestamp
	FROM
		RKMaster
	'
);

$photos = $query->fetchAll( PDO::FETCH_ASSOC );

foreach ( $photos as $photo ) {

	$target = sprintf( '%s/Masters/%s', $photos_dir, $photo['path'] );
	$link_folder = sprintf( '%s/%s', $links_dir, date( 'Y-m', $photo['timestamp'] ) );
	$link = sprintf( '%s/%s-%s', $link_folder, md5( $photo['path'] ), basename( $photo['path'] ) );

	if ( ! file_exists( $target ) )
		continue;

	if ( ! is_dir( $link_folder ) )
		mkdir( $link_folder );

	if ( ! file_exists( $link ) )
		symlink( $target, $link );

}

unlink( $photos_library_local );
