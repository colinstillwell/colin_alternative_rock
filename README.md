# Spotify Showcase

A Drupal 11 project implementing custom modules for Spotify API integration.

## Table of Contents

- 🚀 [Quick Start](#quick-start)
- 📦 [Manual Module Installation](#manual-module-installation)
- 📝 [Notes](#notes)
  - [Spotify API: Data Fetching Approach](#spotify-api-data-fetching-approach)
  - [Ordering](#ordering)
  - [Path Alias](#path-alias)
  - [Profile](#profile)
  - [Theming](#theming)
  - [Recipes](#recipes)
  - [Coding Standards](#coding-standards)
  - [Conventional Commits](#conventional-commits)

## Quick Start

#### Step 1: Prerequisites

Ensure you have [DDEV](https://ddev.readthedocs.io) installed (v1.24+ recommended).

#### Step 2: Clone the Repository

```bash
git clone https://github.com/colinstillwell/colin_alternative_rock.git
cd colin_alternative_rock
```

#### Step 3: Start the Environment and Install Dependencies

```bash
# Start the environment
ddev start

# Install dependencies
ddev composer install

# Install the site
ddev drush site-install spotify_showcase --site-name='Spotify Showcase' --account-name=admin --account-pass=admin --yes
```

#### Step 4: View the Site and Login

- Visit: https://colin-alternative-rock.ddev.site
- The **Spotify Artists** block is automatically displayed on the homepage but will be empty until artists are added.
- You can login at https://colin-alternative-rock.ddev.site/user/login (username: admin, password: admin).

#### Step 5: Configure Spotify API

Follow the instructions in the `spotify_api` module [README](https://github.com/colinstillwell/colin_alternative_rock/blob/master/web/modules/custom/spotify_api/README.md)

#### Step 6: Manage Spotify Artists

Follow the instructions in the `spotify_artist` module [README](https://github.com/colinstillwell/colin_alternative_rock/blob/master/web/modules/custom/spotify_artist/README.md)

## Manual Module Installation

If you want to use the `spotify_artist` module independently of this project, follow these steps:

#### Step 1: Copy the Module

Locate the module in the repository at `web/modules/custom/spotify_artist`.

#### Step 2: Place the Module

Place the `spotify_artist` module into `modules/custom/` in your Drupal project.

#### Step 3: Enable the module

You can enable the module using Drush, Drupal’s command-line tool:

```bash
drush en spotify_artist -y
```

If you prefer, you can enable the module through the Drupal admin UI under Extend (`/admin/modules`).

#### Step 4: Configure Spotify API

Follow the instructions in the `spotify_api` module [README](https://github.com/colinstillwell/colin_alternative_rock/blob/master/web/modules/custom/spotify_api/README.md)

#### Step 5: Manage Spotify Artists

Follow the instructions in the `spotify_artist` module [README](https://github.com/colinstillwell/colin_alternative_rock/blob/master/web/modules/custom/spotify_artist/README.md)

## Notes

This section highlights key technical decisions made during development.

#### Spotify API: Data Fetching Approach

Fetching data from an external API requires balancing performance, reliability, and user experience. After evaluating multiple approaches, I identified these as the top three most practical options for handling API data efficiently.

##### Option 1 - Fetch Data on Page Load

This approach retrieves artist data directly from the API every time the page loads. It ensures real-time updates but slows down page loads, increases dependency on an external service, and fails if the API is unavailable.

##### Option 2 - Cached API Responses

Data is temporarily stored to reduce frequent API requests. This lowers the number of calls while keeping data relatively fresh, but cached data can expire unexpectedly, causing occasional delays when refetching.

##### Option 3 - Store Data in Entity (Chosen Approach)

Artist data is fetched once when the entity is created and stored for future use. This ensures fast page loads, works offline, and provides a predictable user experience. However, data may become outdated over time.

##### Conclusion

Fetching data on page load provides real-time updates but introduces performance and reliability issues. By storing artist data in an entity, the project ensures fast page loads, resilience to API failures, and full control over when updates occur. Given more time, I would introduce an automated update mechanism, such as refreshing data if it hasn’t been updated recently or running scheduled updates via a cron job, to keep artist information more up to date.

#### Ordering

I decided to order the Spotify Artists based on popularity, then followers, then name. Since the task is open-ended in some areas, I chose this ordering based on what I felt provided the best user experience. I did consider adding an ordering mechanism where administrators could manually define the sort order, but the extra time required did not feel appropriate.

#### Path Alias

Currently, Spotify Artists have canonical URLs in the format:
`/spotify-artist/{spotify_artist}`, where `{spotify_artist}` is the entity ID.

I considered using either the Spotify ID or an aliased page title instead. Given the open-ended nature of the task, I retained the default path structure rather than implementing a custom aliasing system. Implementing a more user-friendly aliasing system would require additional time, which did not feel appropriate.

#### Profile

The Spotify Showcase profile (`spotify_showcase`) is a custom installation profile that preconfigures the site with essential modules, settings, and configurations. It streamlines the installation process, ensuring the project is ready to use straight from the quick start.

#### Theming

For this project, I selected Radix, a component-based Drupal theme that includes Bootstrap 5, Sass, ES6, BrowserSync, and BiomeJS out of the box. I didn’t spend long choosing a base theme, as many options are available.

To allow for future custom styling, I created a sub-theme (`spotify`) using a Radix starter kit. This approach ensures flexibility for modifications while keeping the base theme updatable.

The Spotify Artist module includes minimal styling to demonstrate my understanding of Drupal theming, focusing on layout rather than extensive customisation.

#### Recipes

I explored Drupal Recipes but chose an installation profile (`spotify_showcase`) instead. Profiles run during site install, making them better for setting up a new project from scratch, while recipes are more suited for post-install changes.

#### Coding Standards

The `colin_alternative_rock.code-workspace` file contains Visual Studio Code settings and recommended extensions to enforce Drupal coding standards. These settings are loosely based on [Drupal.org’s official standards](https://www.drupal.org/node/2918206), and demonstrate my understanding of best practices.

I am aware that some of these settings would typically be configured per-user. I also have experience configuring this setup for PhpStorm, another widely used Drupal IDE.

Additionally, this project includes PHPStan for static analysis and PHPCS for enforcing Drupal coding standards. Both tools are pre-configured in .phpstan.neon.dist and .phpcs.xml.dist, ensuring consistency with Drupal’s best practices.

To further enforce these standards, Husky is used to run automated checks before commits, preventing coding standard violations from entering the repository.

Files in the root directory, such as Node packages and dotfiles, help support this setup.

#### Conventional Commits

All commits in this project are prefixed with [master]. This is because I have globally configured Conventional Commits to integrate with Jira. If no ticket ID is provided (as in this case), the prefix defaults to the name of the branch I am working on.

Conventional Commits help automate the creation of release notes, providing a structured approach to commit history.
