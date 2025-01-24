# Laravel URL Shortener Project

This is a simple Laravel project for creating a URL shortening service. We have four endpoints:
- `/encode`: Takes a long URL and returns a short URL.
- `/decode`: Takes a short URL and returns the original long URL.

- `/api/encode`: This is the API. Takes a long URL and returns a short URL (Test With Postman).
- `/api/decode`: This is the API. Takes a short URL and returns the original long URL (Test With Postman).

Both stores the data in the database. You need to create a database names 'short_urls', and then php artisan migrate.

---

## Requirements
To run this project, you need:
1. PHP 8.1 or later.
2. Composer (PHP dependency manager).
3. A web server (Laravel's built-in server works).

---

## How to Set Up the Project

Follow these steps to set up and run the project:

### 1. Clone the Repository
Download or clone the project to your computer:
```bash
git clone https://github.com/its-soa/Url-Shortener.git
cd url-shortener
```

### 2. Install Dependencies
Run the following command to install all the necessary packages:
```bash
composer install
```

### 3. Migrate The Database
Run the following command to migrate the database:
```bash
php artisan migrate
```

### 4. Start the Laravel Server
Run this command to start Laravel's development server:
```bash
php artisan serve
```
You should see output like this:
```
Starting Laravel development server: http://127.0.0.1:8000
```
The project is now running at `http://127.0.0.1:8000`.

---

## How to Use the Project API With Postman

Use a tool like Postman, or any HTTP client to interact with the API endpoints.

### Endpoint 1: `/api/encode` (Encode a URL)
#### Request:
- **Method:** POST
- **URL:** `http://127.0.0.1:8000/api/encode`
- **Body:** JSON (example)
  ```json
  {
    "url": "https://www.thisisalongdomain.com/with/some/parameters?and=here_too"
  }
  ```

#### Response:
- **Success:**
  ```json
  {
    "short_url": "http://short.est/1eeba7"
  }
  ```

### Endpoint 2: `/api/decode` (Decode a URL)
#### Request:
- **Method:** POST
- **URL:** `http://127.0.0.1:8000/api/decode`
- **Body:** JSON (example)
  ```json
  {
    "short_url": "http://short.est/1eeba7"
  }
  ```

#### Response:
- **Success:**
  ```json
  {
    "original_url": "https://www.thisisalongdomain.com/with/some/parameters?and=here_too"
  }
  ```
- **Error (if not found):**
  ```json
  {
    "error": "Opps! Short URL Not Found"
  }
  ```

---

## How to Run The Application's Tests

To run the tests, run
```bash
 php artisan test --filter=LinkShortenerControllerTest
```

## Notes
1. If testing on web browser, simply type in your url and you'll see the result. (Route is in the web.php file)
2. Ensure you add `/api/encode` OR `/api/decode` if testing with Postman. (Route is in the api.php file)
---

## Troubleshooting
- If the Laravel server doesn't start, ensure that you have PHP and Composer installed correctly.
- If you encounter any errors, check that your `.env` file exists, check that you've migrated your database

---

Happy coding!