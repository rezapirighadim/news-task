markdown
# News Aggregator Backend

A Laravel backend service that aggregates news from multiple external APIs (NewsAPI, The Guardian, NY Times).  
Includes caching, queue workers, scheduling, Swagger documentation, and clean architecture with Services, Repositories, and Adapters.

---

##  Features
- Fetch and normalize articles from 3 external sources
- Redis caching & queued background fetching
- Automatic scheduled synchronization
- Clean modular architecture (Service / Repository / Adapters)
- Full Swagger API docs (`/docs`)
- User preferences & personalized feed

---

##  Setup

### 1. Install Dependencies
```bash
composer install
````

### 2. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Add to `.env`:

```
NEWSAPI_KEY=your_key
GUARDIAN_API_KEY=your_key
NYTIMES_API_KEY=your_key

QUEUE_CONNECTION=redis
CACHE_DRIVER=redis
```

### 3. Database

```bash
php artisan migrate
php artisan db:seed
```

### 4. Swagger Documentation (Optional but recommended)

Generate docs:

```bash
php artisan l5-swagger:generate
```

Swagger UI available at:

```
http://localhost:8000/api/documentation
```

### 5. Queue Worker

```bash
php artisan queue:work
```

### 6. Scheduler (Production)

Add to server crontab:

```
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

##  Usage

### Manual Fetch
```bash
php artisan articles:fetch
php artisan articles:fetch --source=newsapi
```

### REST API Endpoints

#### Articles

```
GET /api/v1/articles
GET /api/v1/articles?search=technology
GET /api/v1/articles?source=newsapi&category=tech&from=2024-01-01
GET /api/v1/articles/{id}
```

#### Metadata

```
GET /api/v1/sources
GET /api/v1/categories
GET /api/v1/authors
```

#### User Personalization (Authenticated)

```
GET /api/v1/preferences
POST /api/v1/preferences
GET /api/v1/feed
```

---

##  Testing

```bash
php artisan test
```

---

##  Architecture Overview

| Layer          | Responsibility                                              |
| -------------- | ----------------------------------------------------------- |
| **Adapters**   | Communicate with external APIs (NewsAPI, Guardian, NYTimes) |
| **Repository** | Database queries & Eloquent access                          |
| **Services**   | Business logic & app rules                                  |
| **Jobs**       | Background fetching & processing                            |
| **Scheduler**  | Automated hourly updates                                    |

Directory structure:

```
app/
├── Http/Controllers
├── Http/Requests
├── Services
├── Repositories
├── Adapters
├── Jobs
└── Models
```

---

## API Keys

Register & get keys here:

* NewsAPI: [https://newsapi.org/](https://newsapi.org/)
* The Guardian: [https://open-platform.theguardian.com/](https://open-platform.theguardian.com/)
* NY Times: [https://developer.nytimes.com/](https://developer.nytimes.com/)


