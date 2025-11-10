-- Create test database
CREATE DATABASE news_task;
CREATE DATABASE news_task_test;


-- Create necessary extensions
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pg_trgm";

-- Grant privileges
GRANT ALL PRIVILEGES ON DATABASE news_task TO postgres;
GRANT ALL PRIVILEGES ON DATABASE news_task_test TO postgres;

-- Connect to test database and create extensions there too
\c task_management_test;
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pg_trgm";
