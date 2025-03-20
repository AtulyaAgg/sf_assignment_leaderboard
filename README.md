# Leaderboard API - Laravel

A Laravel-based leaderboard API that allows users to earn points, view rankings, generate QR codes for addresses, and track winners every 5 minutes.

**Note - We have used laravel sail to setup local environemnt**

## Features

- User management (add, update, delete)
- Leaderboard with dynamic ranking
- QR code generation using queues
- Scheduled job to store the highest scorer every 5 minutes (avoiding ties)
- API endpoints to interact with the system


## Prerequisites

Ensure you have
- **Docker** installed [(Install Docker)](https://docs.docker.com/engine/install/ubuntu/)
- **Laravel Sail** installed [(Laravel sail installation)](https://laravel.com/docs/12.x/sail)


## Setup & Run with Laravel Sail
 
### 1. Clone the repository
```
git clone https://github.com/AtulyaAgg/sf_assignment_leaderboard.git

```

### 2. Copy environment file
```
cp .env.example .env
```

### 3. Start Laravel Sail
```
./vendor/bin/sail up -d
```

### 4. Run Migrations
```
./vendor/bin/sail artisan migrate
```

### 5. Seed the database (optional)
```
./vendor/bin/sail artisan db:seed
```

### 6. Run the queue worker
```
./vendor/bin/sail artisan queue:work
```

### 7. Run the scheduler
```
./vendor/bin/sail artisan schedule:work
```


## API Endpoints

| Method | Endpoint | Description |
|----------|----------|----------|
| GET | /api/leaderboard | Fetch all users ordered by points |
| POST | /api/users | Add a new user |
| DELETE | /api/users/{id} | Delete a user |
| PATCH | /api/users/{id}/points | Increment/Decrement user points |
| GET | /api/users/{id} | Fetch user details |
| POST | /api/reset-scores | Reset all scores |
| GET | /api/users-grouped | Get users grouped by score with average age |
| GET | /api/winners | Fetch the recorded winners |


## Additional Commands

### -Clear Cache
```
./vendor/bin/sail artisan cache:clear
```

### -Run Tests:
```
./vendor/bin/sail artisan test
```

### -Tinker (for debugging):
```
./vendor/bin/sail artisan tinker
```







