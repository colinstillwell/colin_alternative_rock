# Spotify Artist Module

A custom Drupal module that integrates with the Spotify API to manage and display artist details within a Drupal site.

## Requirements

[Imagecache External](https://www.drupal.org/project/imagecache_external) is used to apply image styles to external artist images.

By default, images should display correctly. However, if images do not appear, you may need to manually whitelist the domain `i.scdn.co` or disable whitelisting in the module settings.

To check or adjust this setting, navigate to:
**Admin > Configuration > Media > Imagecache External** (`/admin/config/media/imagecache_external`).

## Features

- Allows administrators to store up to 20 Spotify Artist IDs.
- Provides a **Spotify Artists** block that displays artist names with links to individual pages, which show the artist’s name, image, and associated genres.
- Fetches artist data from the Spotify API upon entity creation for better performance and offline access.
- Ensures only users with the correct permissions can view artist detail pages.

## Content Management

Navigate to **Content > Spotify Artists** (`/admin/content/spotify-artists`) to manage Spotify Artists.

## Permissions

To manage Spotify Artists, users must have the `administer spotify artist entities` permission.

To view Spotify Artists, users must have the `view spotify artist entities` permission.

## Additional Notes

For more details on design decisions and technical considerations, see the [Notes](https://github.com/colinstillwell/colin_alternative_rock#notes) section in the main project README.
