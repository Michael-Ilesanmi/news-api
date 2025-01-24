# News Aggregator Backend

This project is the backend implementation for a news aggregator website. It fetches articles from various news sources, stores them in a local database, and provides API endpoints for accessing and filtering the news.

---

## Prerequisites

To set up and run this project, ensure you have the following installed:

1. PHP >= 8.0
2. Composer
3. Laravel 10.x
4. MySQL (or any other database supported by Laravel)
5. Redis (optional, if using queue management)

---

## Setup Instructions

1. **Clone the Repository**:
   ```bash
   git clone <repository_url>
   cd <repository_directory>
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   ```

3. **Environment Configuration**:
   - Create a `.env` file by copying `.env.example`:
     ```bash
     cp .env.example .env
     ```
   - Update the `.env` file with your database and API credentials:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=news_aggregator
     DB_USERNAME=root
     DB_PASSWORD=yourpassword

     NEWS_API_KEY=your_news_api_key
     GUARDIAN_NEWS_API_KEY=your_guardian_api_key
     NEW_YORK_TIMES_API_KEY=your_new_york_times_api_key
     ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Run Database Migrations**:
   ```bash
   php artisan migrate
   ```
6. **Populate Database with News Articles**:
   ```bash
   php artisan app:fetch-guardian-news
   php artisan app:fetch-news-api-command
   php artisan app:fetch-new-york-times 
   ```

7. **Set Up Scheduler**:
   - Add the following cron job to your server to enable scheduled tasks:
     ```bash
     * * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
     ```

8. **Run the Application**:
   - Start the development server:
     ```bash
     php artisan serve
     ```

---

## Scheduled Commands

The application fetches news articles from the following sources every 15 minutes using Laravel's scheduler:

- **NewsAPI**: Fetches articles via the `FetchNewsApiCommand`.
- **The Guardian**: Fetches articles via the `FetchGuardianNews` command.
- **New York Times**: Fetches articles via the `FetchNewYorkTimes` command.

### Configured Scheduler (`console.php`):
```php
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\FetchNewsApiCommand;
use App\Console\Commands\FetchGuardianNews;
use App\Console\Commands\FetchNewYorkTimes;

Schedule::command(FetchGuardianNews::class)->everyFifteenMinutes();
Schedule::command(FetchNewsApiCommand::class)->everyFifteenMinutes();
Schedule::command(FetchNewYorkTimes::class)->everyFifteenMinutes();
```

---

## API Endpoints

### Base URL
- `http://localhost:8000/api/` (or your deployed URL)

### Routes

1. **API Version Check**:
   ```http
   GET /
   ```
   - **Description**: Returns the API version.
   - **Response**:
     ```json
     "News Aggregator Backend"
     ```

2. **Search News**:
   ```http
   GET /search
   ```
   - **Description**: Retrieves paginated news articles based on filters and search queries.
   - **Query Parameters**:
     - `sources` (string, optional): Comma-separated list of news sources to filter by.
     - `categories` (string, optional): Comma-separated list of categories to filter by.
     - `authors` (string, optional): Comma-separated list of authors to filter by.
     - `start_date` (string, optional): Start date for filtering articles (format: `YYYY-MM-DD`).
     - `end_date` (string, optional): End date for filtering articles (format: `YYYY-MM-DD`).
     - `page` (integer, optional): The page number for pagination (default: 1).

   - **Example Request**:
     ```http
     GET GET /search?sources=bbc-news,cnn&categories=technology&start_date=2025-01-01&end_date=2025-01-24
     ```
   - **Response**:
     ```json
     {
         "current_page": 2,
         "data": [
             {
                 "id": 1,
                 "title": "Breaking News Article",
                 "source": "BBC",
                 "category": "World",
                 "author": "John Doe",
                 "published_at": "2025-01-23T12:00:00Z",
                 "content": "Lorem ipsum dolor sit amet..."
             },
             ...
         ],
         "from": 21,
         "to": 40,
         "total": 100
     }
     ```

---

## Best Practices Implemented

1. **Clean Code Principles**:
   - **DRY** (Don't Repeat Yourself): Reusable methods for query building and filtering.
   - **SOLID** principles applied in the Service and Repository layers.
2. **Task Scheduling**: Laravel's scheduler ensures data is updated at regular intervals.
3. **Pagination**: Limits API responses to 20 articles per page for better performance.

---

For further assistance, feel free to reach out!

