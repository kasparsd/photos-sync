# Sync and Backup OS X Photos to Any Location

![Screenshot of a synchronized Photos folder](https://raw.githubusercontent.com/kasparsd/photos-sync/master/screenshot.png)

The PHP script creates symlinks to all of the OS X Photos originals which can be _Rsynced_ to any location. I use it to share all my photos with the rest of the family using a shared WD EX2 drive. This allows me to avoid adding everything to the Family shared album on iCloud Photos.

    $ git clone https://github.com/kasparsd/photos-sync.git
    $ php photos-sync/symlink-photos.php
  
or add it to cron (`crontab -e`):

	30 * * * * php /home/USERNAME/photos-sync/symlink-photos.php

It reads the OS X Photos database (SQLite) at `~/Pictures/Photos Library.photoslibrary/database/Library.apdb` (a copy of it) and creates symlinks to all the original photos and videos in the `photos-sync/links` folder organized by month and prepended with an `md5` hash of the file path to each filename to avoid collisions.

It can then be sent to any location using rsync (see `sync.sh` for an example):

	rsync -avL photos-sync/links/ /Volumes/Public/Photos

where `-L` ensures that Rsync resolves the symlinks.
