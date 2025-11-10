# News Aggregator Backend

Laravel backend for news aggregator with NewsAPI, The Guardian, and NY Times integration.

## Setup

### 1. Install Dependencies
```bash
composer install
```

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

### 3. Database Setup
```bash
php artisan migrate
php artisan db:seed
```

### 4. Queue Worker
```bash
php artisan queue:work
```

### 5. Scheduler (Production)
Add to crontab:
```
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## Usage

### Manual Fetch
```bash
php artisan articles:fetch
php artisan articles:fetch --source=newsapi
```

### API Endpoints

**List Articles**
```
GET /api/v1/articles
GET /api/v1/articles?search=technology
GET /api/v1/articles?source=newsapi&category=tech&from=2024-01-01
```

**Get Article**
```
GET /api/v1/articles/{id}
```

**Metadata**
```
GET /api/v1/sources
GET /api/v1/categories
GET /api/v1/authors
```

**User Preferences** (Authenticated)
```
GET /api/v1/preferences
POST /api/v1/preferences
GET /api/v1/feed
```

## Testing
```bash
php artisan test
```

## Architecture

- **Adapters**: NewsAPI, Guardian, NYTimes
- **Repository**: Article data access
- **Services**: Business logic
- **Jobs**: Background fetching
- **Scheduler**: Hourly updates

## API Keys

- NewsAPI: https://newsapi.org/
- The Guardian: https://open-platform.theguardian.com/
- NY Times: https://developer.nytimes.com/
