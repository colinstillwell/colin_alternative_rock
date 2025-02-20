# Spotify Artist Module

A custom Drupal module that integrates with the Spotify API to manage and display artist details within a Drupal site.

## Features

- Allows administrators to store up to 20 Spotify artist IDs.
- Provides a 'Spotify Artists' block that displays artist names with links to individual pages, displaying the artist’s name, image, and associated genres.
- Fetches artist data from the Spotify API upon entity creation for better performance and offline access.
- Restricts artist detail pages to logged-in users.

## Content Management

- Spotify artists can be managed at `/admin/content/spotify-artists`.

## Configuration

- No additional configuration is required after installation.
- Permissions can be managed at `/admin/people/permissions`.

## Additional Notes

For more details on design decisions and technical considerations, see the [Notes](https://github.com/colinstillwell/colin_alternative_rock#notes) section in the main project README.
