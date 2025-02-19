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
ddev drush site-install standard --account-name=admin --account-pass=admin --yes
```

Get a one-time login URL:

```bash
ddev drush uli
```

Visit the site:
https://colin-alternative-rock.ddev.site
