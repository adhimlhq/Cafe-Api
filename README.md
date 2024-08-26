# Cafe Management API
A Laravel-based REST API for managing cafes, including user roles (superadmin, owner, manager) and functionalities like cafe management, menu management, and user authentication.

## Features
- User authentication and role-based access control
- CRUD operations for cafes, menus, and users
- Rate limiting for API requests
- JWT authentication with Laravel Sanctum

## Installation

1. Clone the repository:
   ```bash
    git clone https://github.com/adhhimlhq/cafe-management.git
2. Navigate project directory
    cd repository-name
3. Install dependencies:
    composer install
    npm install
    npm run dev
4. Copy Env
    cp .env.example .env
5. Generate Key Application :
    php artisan key:generate
6. Setup Database
    php artisan migrate
7. Start :
    php artisan serve


== Postman Documentation ==
https://documenter.getpostman.com/view/5829963/2sAXjF9v75
