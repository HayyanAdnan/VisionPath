# VisionPath — Future Skills Roadmap Platform

VisionPath is a career exploration and skills-roadmap project. Users can explore career options, review skills, and work toward a learning roadmap.

## Project features

- Responsive multi-page interface
- Career and skills exploration
- Career matching and roadmap pages
- PHP endpoints and MySQL database integration
- User registration and login
- Admin utilities and career-data import scripts

## Preview

**Live demo:** https://visionpath.great-site.net/

The live demo is hosted separately. This GitHub repository contains the project source code.

## Important: GitHub Pages limitation

This project uses PHP and MySQL. GitHub Pages only hosts static files, so it cannot run the PHP backend or connect to the MySQL database. Use a PHP-capable host (such as your existing hosting provider) for the live version. The repository is for viewing the source code and project structure.

## Local setup

1. Install PHP with the PDO MySQL extension and a MySQL/MariaDB server (for example, XAMPP).
2. Create a database and import the SQL files in `database/` as appropriate. Start with `database/schema.sql`; review the other SQL files before importing them.
3. Configure the environment variables below in your local PHP/server environment.
4. Run the project through Apache/PHP; do not open PHP files directly as local files.

Required environment variables:

- `DB_HOST` — database host
- `DB_NAME` — database name
- `DB_USER` — database username
- `DB_PASS` — database password
- `ADMIN_API_KEY` — a long, random secret used by administrative endpoints

The repository's `config/database.php` intentionally contains no live database credentials. Do not add real credentials, API keys, private logs, or user data to GitHub. If your hosting provider does not support environment variables, keep a private deployment-specific config file outside the public repository and upload it separately through the hosting file manager.

## Folder overview

- `index.html`, `about.html`, `contact.html` — main pages
- `dashboard.html`, `Roadmap.html`, `skills.html`, `campare.html` — career and skills UI
- `login.php`, `register.php`, `logout.php` — account pages
- `api/` — PHP endpoints and bundled ESCO reference data
- `config/` — configuration template
- `database/` — SQL schema, migrations, and seed data
- `admin/` — admin pages

## Security notes

- Never commit production credentials.
- Rotate any database password or API key that was previously stored in a public or shared copy.
- Do not commit contact-form logs or personal messages.
- Review admin endpoints and configuration before deploying to production.
