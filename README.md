# Translation Service API

This is a RESTful API built with Laravel for managing translations. It allows you to create, read, update, and delete translations for multiple locales and contexts.

## Setup

1. Clone this repository.
2. Install dependencies using `composer install`.
3. Create a database and configure your database credentials in `.env`.
4. Run the database migrations using `php artisan migrate`.
5. Start the development server with `php artisan serve`.

## API Endpoints

*   **GET /translations**
    *   Retrieve a list of translations with optional filtering by locale and context.
*   **POST /translations**
    *   Create a new translation.
*   **GET /translations/{key}**
    *   Retrieve a translation by key.
*   **PUT /translations/{key}**
    *   Update an existing translation.
*   **DELETE /translations/{key}**
    *   Delete a translation.
*   **GET /translations/export**
    *   Export all translations in JSON format.

## Design Choices

*   **Relational database:** I chose a relational database (MySQL) for storing translations to ensure data consistency and efficient querying.
*   **JSON for context:** I used a JSON column to store context tags to allow for flexibility and scalability in defining context relationships.
*   **API endpoints:** I designed RESTful API endpoints for easy integration with different clients.
*   **Export endpoint:** I included an export endpoint to provide a convenient way to fetch all translations in a single JSON file.

## Usage

You can access and interact with the API using any HTTP client. For example, you can use `curl` to make requests:

```bash
curl http://dev.translation-management.com/api/translations
