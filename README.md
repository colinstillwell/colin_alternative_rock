# Spotify Showcase for CACI Digital Experience (formerly Cyber-Duck)

A Drupal 11 project implementing a custom module for Spotify API integration.

## Table of Contents

- 🚀 [Quick Start](#quick-start)
- 📦 [Manual Module Installation](#manual-module-installation)
- 📝 [Notes](#notes)
  - [Spotify API: Data Fetching Approach](#spotify-api-data-fetching-approach)
  - [Conventional Commits](#conventional-commits)
  - [Coding Standards](#coding-standards)
  - [Recipes](#recipes)
  - [Profile](#profile)

## Quick Start

### Step 1: Prerequisites

Ensure you have [DDEV](https://ddev.readthedocs.io) installed (v1.24+ recommended).

### Step 2: Clone the Repository

```bash
git clone https://github.com/colinstillwell/colin_alternative_rock.git
cd colin_alternative_rock
```

### Step 3: Start the Environment and Install Dependencies

```bash
# Start the environment
ddev start

# Install dependencies
ddev composer install

# Install the site
ddev drush site-install spotify_showcase --site-name='Spotify Showcase' --account-name=admin --account-pass=admin --yes
```

### Step 4: Visit the site

- URL: https://colin-alternative-rock.ddev.site
- Username: admin
- Password: admin

## Manual Module Installation

If you want to use the `spotify_artist` module independently of this project, follow these steps:

### Step 1: Copy the Module

Locate the module in the repository at `web/modules/custom/spotify_artist`.

### Step 2: Place the Module

Move the module into `modules/custom/` in your Drupal project.

### Step 3: Enable the module

You can enable the module using Drush, Drupal’s command-line tool:

```bash
drush en spotify_artist -y
```

If you prefer, you can enable the module through the Drupal admin UI under Extend (`/admin/modules`).

## Notes

This section highlights key technical decisions made during development.

### Spotify API: Data Fetching Approach

Fetching data from an external API requires careful consideration of performance, reliability, and user experience. After evaluating multiple approaches, I identified these as the top three most practical options for handling API data efficiently.

#### Option 1 - Fetch Data on Page Load

This approach retrieves artist data directly from the API every time the page loads. It ensures real-time updates but slows down page loads, increases dependency on an external service, and fails if the API is unavailable.

#### Option 2 - Cached API Responses

Data is temporarily stored to reduce frequent API requests. This lowers the number of calls while keeping data relatively fresh, but cached data can expire unexpectedly, causing occasional delays when refetching.

#### Option 3 - Store Data in Entity (Chosen Approach)

Artist data is fetched once when the entity is created and stored for future use. This ensures fast page loads, works offline, and provides a predictable user experience. However, data may become outdated over time.

#### Conclusion

Fetching data on page load provides real-time updates but introduces performance and reliability issues. By storing artist data in an entity, the project ensures fast page loads, resilience to API failures, and full control over when updates occur. Given more time, I would introduce an automated update mechanism, such as refreshing data if it hasn’t been updated recently or running scheduled updates via a cron job, to keep artist information more up to date.

### Conventional Commits

All commits in this project are prefixed with [master]. This is because I have globally configured Conventional Commits to integrate with Jira. If no ticket ID is provided (as in this case), the prefix defaults to the name of the branch I am working on.

Conventional Commits help automate the creation of release notes, providing a structured approach to commit history.

### Coding Standards

The `colin_alternative_rock.code-workspace` file contains Visual Studio Code settings and recommended extensions to enforce Drupal coding standards. These settings are loosely based on [Drupal.org’s official standards](https://www.drupal.org/node/2918206), and demonstrate my understanding of best practices.

I am aware that some of these settings would typically be configured per-user. I also have experience configuring this setup for PhpStorm, another widely used Drupal IDE.

Additionally, files in the root directory, such as Node packages and dotfiles, help support this.

### Recipes

I explored Drupal Recipes but chose an installation profile (`spotify_showcase`) instead. Profiles run during site install, making them better for setting up a new project from scratch, while recipes are more suited for post-install changes.

### Profile

The Spotify Showcase profile (`spotify_showcase`) is a custom installation profile that preconfigures the site with essential modules, settings, and configurations. It streamlines the installation process, ensuring the project is ready to use straight from the quick start.
