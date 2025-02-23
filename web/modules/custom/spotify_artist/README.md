# Spotify Artist Module

A custom Drupal module that integrates with the Spotify API to manage and display artist details within a Drupal site.

## Requirements

[Imagecache External](https://www.drupal.org/project/imagecache_external) is used to apply image styles to external artist images.

Whitelisting is disabled by default, so images should display correctly without additional configuration. If you have previously used this module, you may need to review your settings.

To check or adjust this setting, navigate to:
**Admin > Configuration > Media > Imagecache External** (`/admin/config/media/imagecache_external`).

## Features

- Allows administrators to store up to 20 Spotify Artist IDs.
- Provides a **Spotify Artists** block that displays artist names with links to individual pages, which show the artist’s name, image, and associated genres.
- Fetches artist data from the Spotify API upon entity creation for better performance and offline access.
- Ensures only users with the correct permissions can view artist detail pages.

## Drush Commands

The module provides a Drush command to create a set of predefined example Spotify Artists:

```bash
drush spotify-artist:create-examples
# or
drush spotify-artist:ce
```

This command will create a predefined list of 20 Spotify Artists with their respective Spotify IDs.

## Content Management

Navigate to **Content > Spotify Artists** (`/admin/content/spotify-artists`) to manage Spotify Artists.

## Permissions

To manage Spotify Artists, users must have the `administer spotify artist entities` permission.

To view Spotify Artists, users must have the `view spotify artist entities` permission.

## Additional Notes

For more details on design decisions and technical considerations, see the [Notes](https://github.com/colinstillwell/colin_alternative_rock#notes) section in the main project README.
