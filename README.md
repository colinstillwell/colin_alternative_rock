# Spotify Showcase for CACI Digital Experience (formerly Cyber-Duck)

A Drupal 11 project implementing a custom module for Spotify API integration.

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

### Conventional Commits

All commits in this project are prefixed with [master]. This is because I have globally configured Conventional Commits to integrate with Jira. If no ticket ID is provided (as in this case), the prefix defaults to the name of the branch I am working on.

Conventional Commits help automate the creation of release notes, providing a structured approach to commit history.

### Coding Standards

The `colin_alternative_rock.code-workspace` file contains Visual Studio Code settings and recommended extensions to enforce Drupal coding standards. These settings are loosely based on [Drupal.org’s official standards](https://www.drupal.org/node/2918206) and demonstrate my understanding of best practices.

I am aware that some of these settings would typically be configured per-user. I also have experience configuring this setup for PhpStorm, another widely used Drupal IDE.

Additionally, files in the root directory, such as Node packages and dotfiles, help support this.

### Recipes

I explored Drupal Recipes but chose an installation profile (spotify_showcase) instead. Profiles run during site install, making them better for setting up a new project from scratch, while recipes are more suited for post-install changes.

### Profile

The Spotify Showcase profile (spotify_showcase) is a custom installation profile that preconfigures the site with essential modules, settings and configurations. It streamlines the installation process, ensuring the project is ready to use straight from the quick start.
