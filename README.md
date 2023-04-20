### README

# Occompare

### Overview

A simple app for comparing occupations.

### System Requirements

- PHP8.1+
- Node.js v18.16.0+ (LTS)
- Composer v2+

### Setup

- Clone/Download and `cd` in to project root directory
- Create .env file from example

```bash
cp ./.env.example ./.env
```

- install composer managed dependencies

```bash
composer install
```

- install node managed dependencies

```bash
yarn
```

- Enjoy!

### Local Development

```bash
# Compiles and serves front end assets with hot reloading
yarn dev
```

```bash
# Launches local development server for php
php artisan serve
```

### Build for Production

```bash
# Compiles, builds and minifies front end assets for production
# and places them in the /public/build directory
yarn build
```

### Repo Owners

- [James Randall](mailto:james.randall@manmachineltd.com)
