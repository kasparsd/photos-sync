# Sync and Backup OS X Photos to Any Location

![Screenshot of a synchronized Photos folder](https://raw.githubusercontent.com/kasparsd/photos-sync/master/screenshot.png)

A script that creates a folder with symbolic links (symlinks) to all of the OS X Photos originals which can be _rsynced_ to any location. I use it to share all my photos with the rest of the family using a shared WD EX2 drive. This allows me to avoid adding everything to the Family shared album on iCloud Photos.

	$ git clone https://github.com/kasparsd/photos-sync.git
	$ php photos-sync/symlink-photos.php

It reads the OS X Photos database (SQLite) at `~/Pictures/Photos Library.photoslibrary/database/Library.apdb` (a copy of it) and creates symlinks to all original photos and videos in the `photos-sync/links` folder organized by month and filenames prepended with an `md5` hash of the file path to avoid collisions.

It can then be sent to any location using rsync or any other tool of preference. Here is a sample bash script which could be added to cron:

	#!/bin/bash

	# Ensure that we're always relative to the current directory
	cd "$(dirname "$0")"

	# Symlink photos and rsync to the network share
	php symlink-photos.php

	# Mount the network share
	mount_afp afp://western.local/Public /Volumes/Public

	# Resolve and synchronize symlinks to the network share
	rsync -avL links/ /Volumes/Public/Photos

where `-L` ensures that rsync resolves the symlinks.

Run it every hour via cron `crontab -e`:

	30 * * * * /path/to/sync.sh
