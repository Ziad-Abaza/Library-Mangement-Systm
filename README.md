# Library Management System

This project is a Library Management System built using React for the frontend and a backend that handles data processing. The frontend is a React-based application, and the backend is implemented in PHP. This guide will take you through the steps to run the project locally, build it, and deploy it to the server.

## Steps to Download and Run the Project

### 1. Clone the Repository
First, clone the repository to your local machine:
```bash
git clone https://github.com/Ziad-Abaza/Library-Mangement-Systm.git
cd Library-Mangement-Systm
```

### 2. Set up the Backend

The backend is a PHP project. Follow these steps to set it up:

1. **Install Dependencies:**
   Make sure you have Composer installed. Run the following command in the backend directory:
   ```bash
   composer install
   ```

2. **Configure Environment:**
   Copy the `.env.example` file to `.env` and update the configuration settings, especially for the database:
   ```bash
   cp .env.example .env
   ```

3. **Generate the Application Key:**
   Run the following command to generate the application key:
   ```bash
   php artisan key:generate
   ```

4. **Run Migrations:**
   To set up the database, run the migrations:
   ```bash
   php artisan migrate
   ```

5. **Run the Backend Server:**
   You can now run the backend server:
   ```bash
   php artisan serve
   ```

### 3. Set up the Frontend (React)

The frontend is a React application. To run it, follow these steps:

1. **Install Dependencies:**
   Navigate to the frontend directory:
   ```bash
   cd frontend
   ```

   Install the dependencies using npm or yarn:
   ```bash
   npm install
   # or
   yarn install
   ```

2. **Configure Environment:**
   Inside the `frontend` directory, locate the `.env.production` file and update the backend URL:
   ```env
   VITE_BACKEND_URL=https://backend.stylingresume.com
   ```

3. **Run the Development Server:**
   To start the development server, run:
   ```bash
   npm run dev
   # or
   yarn dev
   ```

   This will launch the React app on `http://localhost:3000`.

### 4. Build the Project for Production

Once you are satisfied with the development environment, you can build the project for production.

1. **Build the React App:**
   Run the following command inside the `frontend` directory to create the production build:
   ```bash
   npm run build
   # or
   yarn build
   ```

   This will create a `dist/` folder with the built files.

2. **Transfer the Files to the Server:**
   - Move the contents of the `dist/` directory to your main server's public directory.
   - Move the backend files to the subdomain directory (e.g., `backend.stylingresume.com`).

### 5. Update `.htaccess` for Frontend

For proper routing in the React app, you need to update the `.htaccess` file in the frontend directory. Here's the configuration:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Disable MultiViews to prevent issues with extensions
    Options -MultiViews

    # Redirect Trailing Slashes If Not A Folder
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Allow Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Send all requests to index.html if not an existing file or directory
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.html [L]
</IfModule>
```

### 6. Fix CSRF Issues After Deployment

If you encounter a CSRF issue after deploying the project to the hosting server, you need to adjust the following settings:

- **In `.env`:**

   Update the environment configuration:
   ```env
   APP_ENV=prod
   APP_URL=https://backend.stylingresume.com

   SESSION_DOMAIN=.stylingresume.com
   SANCTUM_STATEFUL_DOMAINS=stylingresume.com
   ```

- **In `config/cors.php`:**

   Update the allowed origins:
   ```php
   'paths' => ['*'],
   'allowed_methods' => ['*'],
   'allowed_origins' => ['https://stylingresume.com'],
   'allowed_origins_patterns' => [],
   'allowed_headers' => ['*'],
   'exposed_headers' => [],
   'max_age' => 0,
   'supports_credentials' => true,
   ```

- **In `config/session.php`:**

   Update the session cookie configuration:
   ```php
   'secure' => env('SESSION_SECURE_COOKIE', true),
   ```

### 7. Create a Symlink for `index.php`

Instead of copying `index.php` from the `public` directory, you can create a symlink by running the following command:
```bash
ln -s /home/u388559904/domains/stylingresume.com/public_html/backend/public/index.php /home/u388559904/domains/stylingresume.com/public_html/backend/index.php
```

This command will ensure that the `index.php` is properly linked in the server's public directory.

