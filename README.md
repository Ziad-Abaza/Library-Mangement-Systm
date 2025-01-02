### Library Management System Documentation

Welcome to the **Library Management System**! This document will help you understand how to set up and interact with the project effectively. Whether you are a beginner or have some experience, this guide is designed to assist you in navigating the repository and running the application.

---

#### 1. **Project Overview**

The Library Management System allows users to manage books, authors, categories, and more. It supports functionalities such as:

- User authentication
- Book management (adding, updating, deleting)
- Author and category management
- Commenting on books
- Role-based permissions

---

#### 2. **Getting Started**

**Prerequisites:**

- **PHP**: Make sure you have PHP installed (preferably version 8.0 or later).
- **Composer**: Install Composer for dependency management.
- **MySQL**: Install MySQL for the database.
- **Laravel**: This project is built using Laravel, so ensure you have it set up.

**Clone the Repository:**

```bash
git clone https://github.com/Ziad-Abaza/Library-Mangement-Systm.git
cd Library-Mangement-Systm
```

**Install Dependencies:**

```bash
composer install
```

**Set Up the Environment File:**

1. Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

2. Update the `.env` file with your database credentials:

```plaintext
DB_DATABASE=library
DB_USERNAME=root
DB_PASSWORD=yourpassword
```

**Generate Application Key:**

```bash
php artisan key:generate
```

**Migrate the Database:**

```bash
php artisan migrate
```

**Seed the Database (optional):**

To seed the database with some initial data, you can run:

```bash
php artisan db:seed
```

---

#### 3. **Running the Application**

Start the development server:

```bash
php artisan serve
```

Your application will be available at `http://localhost:8000`.

---

#### 4. **API Endpoints**

The application exposes several API endpoints. Below are some key endpoints you might find useful:

- **User Authentication:**
  - `POST /api/login`: Log in a user.
  - `POST /api/logout`: Log out a user.

- **Books Management:**
  - `GET /api/books`: List all books.
  - `POST /api/books`: Create a new book.
  - `PUT /api/books/{id}`: Update an existing book.
  - `DELETE /api/books/{id}`: Delete a book.
  - `GET /api/books/{id}/download`: Download a specific book.

- **Comments:**
  - `POST /api/books/{bookId}/comments`: Add a comment on a book.
  - `PUT /api/books/{bookId}/comments/{commentId}`: Update a comment.
  - `DELETE /api/books/{bookId}/comments/{commentId}`: Delete a comment.
  - `GET /api/books/{bookId}/comments`: Retrieve comments for a book.

- **Authors:**
  - `GET /api/authors`: List all authors.
  - `POST /api/authors`: Create a new author.
  - `GET /api/authors/{id}`: Retrieve a specific author.
  - `PUT /api/authors/{id}`: Update an existing author.
  - `DELETE /api/authors/{id}`: Delete an author.

- **Categories:**
  - `GET /api/categories`: List all categories.
  - `POST /api/categories`: Create a new category.
  - `PUT /api/categories/{id}`: Update an existing category.
  - `DELETE /api/categories/{id}`: Delete a category.

---

#### 5. **Project Structure**

- **Controllers**: Contains all the logic for handling API requests.
  - `API\BookController.php`: Manages book-related operations.
  - `API\AuthorController.php`: Manages author-related operations.
  - `API\CategoryController.php`: Manages category-related operations.

- **Models**: Represents the data structure.
  - `Book.php`: Represents a book entity.
  - `Author.php`: Represents an author entity.
  - `Category.php`: Represents a category entity.
  
- **Routes**: Defines the application's routing.
  - `api.php`: All API routes are defined here.

---

#### 6. **Key Features and Classes**

- **Book Class**: Represents the books in the library, includes relationships for comments, downloads, and authors.
- **Author Class**: Represents authors and their respective books.
- **Category Class**: Represents book categories and their relationships to books.
- **Role and Permission Management**: Users can be assigned roles and permissions, enhancing security and access control.

---

#### 7. **Conclusion**

This Library Management System provides a robust framework for managing a library's resources effectively. By following this documentation, you should be able to set up the project locally and start exploring its features. If you encounter any issues, feel free to check the [GitHub Issues](https://github.com/Ziad-Abaza/Library-Mangement-Systm/issues) for help or create a new issue.

Happy coding!