#!/bin/bash

cd "$(dirname "$0")"

mount_afp afp://western.local/Public /Volumes/Public

php symlink-photos.php && rsync -avL links/ /Volumes/Public/Photos
