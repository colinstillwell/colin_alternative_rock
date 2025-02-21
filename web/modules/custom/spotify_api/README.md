# Spotify API Module

A custom Drupal module that provides API integration with Spotify, allowing other modules to authenticate and interact with the Spotify Web API.

## Features

- Provides a settings form for configuring `Client ID` and `Client Secret`.
- Stores API credentials securely in Drupal’s configuration system.
- Ensures only users with the correct permissions can modify API settings.
- Designed to be used by other modules, such as `spotify_artist`, to fetch artist data from the Spotify API.

## Configuration

1. Navigate to **Configuration > Web Services > Spotify API** (`/admin/config/services/spotify-api`).
2. Enter your `Client ID` and `Client Secret`.
3. Click **Save Configuration** to store your credentials securely.

## Permissions

To configure API settings, users must have the `administer spotify api settings` permission.

## Additional Notes

For more details on design decisions and technical considerations, see the [Notes](https://github.com/colinstillwell/colin_alternative_rock#notes) section in the main project README.
