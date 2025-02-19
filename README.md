# Spotify Showcase for CACI Digital Experience (formerly Cyber-Duck)

A Drupal 11 project demonstrating a custom Spotify integration module.

## 🛠️ Prerequisites

- [DDEV](https://ddev.readthedocs.io) (v1.24+ recommended)

## 🚀 Quick Start

Clone the repository:

```bash
git clone https://github.com/colinstillwell/colin_alternative_rock.git
cd colin_alternative_rock
```

Start the environment and install dependencies:

```bash
ddev start
ddev composer install
ddev drush site-install spotify_showcase --site-name='Spotify Showcase' --account-name=admin --account-pass=admin --yes
```

Get a one-time login URL:

```bash
ddev drush uli
```

Visit the site:
https://colin-alternative-rock.ddev.site

## Notes

This section highlights key technical decisions made during development.

### Recipes

I explored Drupal Recipes but chose an installation profile (spotify_showcase) instead. Profiles run during site install, making them better for setting up a new project from scratch, while recipes are more suited for post-install changes.

### Profile

The Spotify Showcase profile (spotify_showcase) is a custom installation profile that preconfigures the site with essential modules, settings and configurations. It streamlines the installation process, ensuring the project is ready to use straight from the quick start.
