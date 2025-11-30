# Backend Testing Guide

This guide covers testing for the Laravel backend application using PHPUnit.

## 📋 Table of Contents

- [Overview](#overview)
- [Test Framework](#test-framework)
- [Running Tests](#running-tests)
- [Test Structure](#test-structure)
- [Writing Tests](#writing-tests)
- [Database Testing](#database-testing)
- [API Testing](#api-testing)
- [Troubleshooting](#troubleshooting)
- [Best Practices](#best-practices)

---

## 🔍 Overview

The backend test suite includes:
- **Feature tests**: API endpoints, authentication, integration tests
- **Unit tests**: Models, services, business logic
- **Test framework**: PHPUnit 11.5
- **Database**: SQLite in-memory for fast, isolated tests
- **Laravel version**: 12.x

**Test coverage:**
- ✅ Authentication (AuthTest.php) - login, register, logout, token validation
- ✅ Stations API (StationsTest.php) - CRUD operations, validation
- ✅ Routes calculation (RoutesTest.php) - route finding, distance calculation
- ✅ Models (Unit tests) - Distance, Station models

---

## 🧪 Test Framework

### Technology Stack

- **PHPUnit**: Industry-standard PHP testing framework
- **Laravel Testing**: Built-in testing helpers and assertions
- **SQLite**: In-memory database for fast, isolated tests
- **Faker**: Generate fake data for testing

### Configuration Files

| File | Purpose |
|------|---------|
| `phpunit.xml` | PHPUnit configuration and test settings |
| `.env.testing` | Testing environment configuration |
| `test.sh` | Test runner script with proper setup |
| `tests/TestCase.php` | Base test case with common setup |

---

## 🚀 Running Tests

### Quick Commands

```bash
# Run all tests (recommended)
./test.sh

# Run tests directly with PHPUnit
vendor/bin/phpunit

# Run specific test file
./test.sh tests/Feature/AuthTest.php

# Run specific test method
./test.sh --filter testLoginSuccess

# Run test suite (Feature or Unit)
./test.sh --testsuite Feature
./test.sh --testsuite Unit
```

### Test Script (./test.sh)

The `test.sh` script ensures proper test environment:

```bash
#!/bin/bash

# Ensure we're using SQLite for tests
export DB_CONNECTION=sqlite
export DB_DATABASE=:memory:

# Run PHPUnit with all arguments passed through
vendor/bin/phpunit "$@"
```

**Why use test.sh?**
- ✅ Forces SQLite in-memory database (fast, isolated)
- ✅ Prevents accidental changes to development database
- ✅ Consistent test environment
- ✅ Easy to add pre-test setup if needed

### Detailed Test Commands

#### 1. Run All Tests
```bash
./test.sh
```

**Output:**
```
PHPUnit 11.5.0 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.4.0
Configuration: /path/to/backend/phpunit.xml

...........                                                      11 / 11 (100%)

Time: 00:01.234, Memory: 24.00 MB

OK (11 tests, 45 assertions)
```

#### 2. Run Specific Test Suite
```bash
# Feature tests (API, integration)
./test.sh --testsuite Feature

# Unit tests (models, services)
./test.sh --testsuite Unit
```

#### 3. Run Specific Test File
```bash
./test.sh tests/Feature/AuthTest.php
./test.sh tests/Unit/StationTest.php
```

#### 4. Run Specific Test Method
```bash
# By exact name
./test.sh --filter testLoginSuccess

# By pattern
./test.sh --filter '/Login/'
./test.sh --filter 'Auth'
```

#### 5. Verbose Output
```bash
# Show test names as they run
./test.sh --verbose

# Show even more details
./test.sh --debug
```

#### 6. Code Coverage
```bash
# Generate HTML coverage report
vendor/bin/phpunit --coverage-html coverage

# View coverage report
open coverage/index.html

# Coverage summary in terminal
vendor/bin/phpunit --coverage-text
```

---

## 📁 Test Structure

```
backend/tests/
├── TestCase.php                # Base test case
├── Feature/
│   ├── AuthTest.php           # Authentication tests
│   ├── RoutesTest.php         # Routes calculation tests
│   └── StationsTest.php       # Stations API tests
└── Unit/
    ├── DistanceTest.php       # Distance model tests
    └── StationTest.php        # Station model tests
```

### Test Types

**Feature Tests** (`tests/Feature/`)
- Test complete features end-to-end
- Make HTTP requests to API endpoints
- Test authentication flows
- Verify responses and status codes
- Test database interactions

**Unit Tests** (`tests/Unit/`)
- Test individual classes and methods
- Test model relationships
- Test business logic
- Test validation rules
- Fast and isolated

---

## ✍️ Writing Tests

### Basic Test Structure

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase; // Reset database before each test

    /**
     * Test description
     */
    public function test_example(): void
    {
        // Arrange: Set up test data
        $user = User::factory()->create();

        // Act: Perform action
        $response = $this->actingAs($user)->get('/api/endpoint');

        // Assert: Verify result
        $response->assertStatus(200);
    }
}
```

### Feature Test Example: Authentication

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        // Arrange: Create a user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Act: Attempt login
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // Assert: Check response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'name', 'email'],
            ]);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        // Arrange: Create a user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Act: Attempt login with wrong password
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        // Assert: Should fail
        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid credentials',
            ]);
    }

    public function test_authenticated_user_can_access_protected_route(): void
    {
        // Arrange: Create and authenticate user
        $user = User::factory()->create();

        // Act: Make authenticated request
        $response = $this->actingAs($user)
            ->getJson('/api/user');

        // Assert: Should succeed
        $response->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'email' => $user->email,
            ]);
    }
}
```

### Unit Test Example: Model

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Station;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StationTest extends TestCase
{
    use RefreshDatabase;

    public function test_station_can_be_created(): void
    {
        // Arrange & Act
        $station = Station::factory()->create([
            'name' => 'Test Station',
            'code' => 'TST',
        ]);

        // Assert
        $this->assertDatabaseHas('stations', [
            'name' => 'Test Station',
            'code' => 'TST',
        ]);
    }

    public function test_station_name_is_required(): void
    {
        // Expect validation exception
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Try to create station without name
        Station::create([
            'code' => 'TST',
        ]);
    }

    public function test_station_has_unique_code(): void
    {
        // Arrange: Create station with code
        Station::factory()->create(['code' => 'TST']);

        // Expect validation exception
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Try to create another station with same code
        Station::factory()->create(['code' => 'TST']);
    }
}
```

---

## 💾 Database Testing

### Using RefreshDatabase

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyTest extends TestCase
{
    use RefreshDatabase; // Resets database before each test

    public function test_something(): void
    {
        // Database is fresh and empty
        // Any changes are rolled back after test
    }
}
```

**What RefreshDatabase does:**
- Runs migrations before tests
- Wraps each test in a transaction
- Rolls back transaction after test
- Keeps tests isolated and fast

### Using Factories

```php
// Create one model
$user = User::factory()->create();

// Create multiple models
$users = User::factory()->count(5)->create();

// Create with specific attributes
$user = User::factory()->create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
]);

// Create without persisting to database
$user = User::factory()->make();
```

### Database Assertions

```php
// Assert record exists
$this->assertDatabaseHas('users', [
    'email' => 'test@example.com',
]);

// Assert record doesn't exist
$this->assertDatabaseMissing('users', [
    'email' => 'deleted@example.com',
]);

// Assert record count
$this->assertDatabaseCount('users', 5);

// Assert soft-deleted record
$this->assertSoftDeleted('users', [
    'id' => $user->id,
]);
```

---

## 🌐 API Testing

### Making HTTP Requests

```php
// GET request
$response = $this->get('/api/stations');
$response = $this->getJson('/api/stations'); // Expects JSON

// POST request
$response = $this->post('/api/stations', [
    'name' => 'New Station',
]);
$response = $this->postJson('/api/stations', [...]);

// PUT/PATCH request
$response = $this->put('/api/stations/1', [...]);
$response = $this->patch('/api/stations/1', [...]);

// DELETE request
$response = $this->delete('/api/stations/1');
```

### Authenticated Requests

```php
// Using actingAs
$user = User::factory()->create();
$response = $this->actingAs($user)->get('/api/user');

// Using withToken (JWT)
$token = 'your-jwt-token';
$response = $this->withToken($token)->get('/api/user');

// Using withHeaders
$response = $this->withHeaders([
    'Authorization' => 'Bearer ' . $token,
])->get('/api/user');
```

### Response Assertions

```php
// Status codes
$response->assertStatus(200);
$response->assertOk();              // 200
$response->assertCreated();         // 201
$response->assertNoContent();       // 204
$response->assertNotFound();        // 404
$response->assertUnauthorized();    // 401
$response->assertForbidden();       // 403
$response->assertUnprocessable();   // 422

// JSON structure
$response->assertJsonStructure([
    'data' => [
        '*' => ['id', 'name', 'email']
    ]
]);

// JSON content
$response->assertJson([
    'success' => true,
    'data' => ['id' => 1]
]);

// Exact JSON match
$response->assertExactJson([
    'success' => true,
]);

// JSON fragment
$response->assertJsonFragment([
    'name' => 'John Doe',
]);

// JSON missing
$response->assertJsonMissing([
    'password' => 'secret',
]);

// JSON path
$response->assertJsonPath('data.name', 'John Doe');

// JSON count
$response->assertJsonCount(5, 'data');
```

### Example: Complete API Test

```php
public function test_user_can_create_station(): void
{
    // Arrange: Create authenticated user
    $user = User::factory()->create();

    // Act: Make POST request
    $response = $this->actingAs($user)
        ->postJson('/api/stations', [
            'name' => 'New Station',
            'code' => 'NSW',
            'latitude' => 46.5197,
            'longitude' => 6.6323,
        ]);

    // Assert: Check response
    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'code',
                'latitude',
                'longitude',
                'created_at',
            ]
        ])
        ->assertJsonFragment([
            'name' => 'New Station',
            'code' => 'NSW',
        ]);

    // Assert: Check database
    $this->assertDatabaseHas('stations', [
        'name' => 'New Station',
        'code' => 'NSW',
    ]);
}
```

---

## 🐛 Troubleshooting

### Common Issues and Solutions

#### 1. "Database file not found"

**Problem:** Tests trying to use development database

**Solution:** Always use `./test.sh` or set environment:
```bash
export DB_CONNECTION=sqlite
export DB_DATABASE=:memory:
vendor/bin/phpunit
```

#### 2. "Class not found"

**Problem:** Autoloader not updated

**Solution:**
```bash
composer dump-autoload
./test.sh
```

#### 3. "SQLSTATE[HY000]: General error: 1 no such table"

**Problem:** Migrations not run

**Solution:**
```php
// Make sure test uses RefreshDatabase
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyTest extends TestCase
{
    use RefreshDatabase; // Add this
}
```

#### 4. "Target class [X] does not exist"

**Problem:** Service provider or binding not registered

**Solution:**
- Check `config/app.php` providers
- Run `php artisan config:clear`
- Check namespace in test file

#### 5. Tests Pass Locally but Fail in CI

**Possible causes:**
- Different PHP version
- Missing extensions
- Environment differences

**Solutions:**
```bash
# Check PHP version matches CI (8.4)
php -v

# Check required extensions
php -m | grep -E 'pdo|sqlite|pgsql'

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

#### 6. "Failed asserting that false is true"

**Problem:** Generic assertion failure

**Solution:** Add more specific assertions:
```php
// Instead of
$this->assertTrue($response->successful());

// Use specific assertions
$response->assertStatus(200);
$response->assertJsonFragment(['success' => true]);

// Or dump response for debugging
dd($response->json());
```

---

## 💡 Best Practices

### 1. Use RefreshDatabase

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyTest extends TestCase
{
    use RefreshDatabase; // Always use this for database tests
}
```

### 2. Use Factories Instead of Manual Creation

```php
// ✅ Good - Use factories
$user = User::factory()->create();

// ❌ Bad - Manual creation
$user = new User();
$user->name = 'Test';
$user->email = 'test@example.com';
$user->save();
```

### 3. Test One Thing Per Test

```php
// ✅ Good - Single responsibility
public function test_user_can_login(): void { }
public function test_user_cannot_login_with_invalid_password(): void { }

// ❌ Bad - Testing multiple things
public function test_authentication(): void {
    // tests login
    // tests logout
    // tests password reset
    // tests registration
}
```

### 4. Use Descriptive Test Names

```php
// ✅ Good
public function test_user_can_create_station_with_valid_data(): void { }
public function test_unauthenticated_user_cannot_access_stations(): void { }

// ❌ Bad
public function test_station(): void { }
public function test_works(): void { }
```

### 5. Follow Arrange-Act-Assert Pattern

```php
public function test_example(): void
{
    // Arrange: Set up test data
    $user = User::factory()->create();
    
    // Act: Perform action
    $response = $this->actingAs($user)->get('/api/user');
    
    // Assert: Verify result
    $response->assertOk();
}
```

### 6. Use Type Declarations

```php
// ✅ Good
public function test_example(): void
{
    $user = User::factory()->create();
    $this->assertInstanceOf(User::class, $user);
}

// ❌ Bad - No return type
public function test_example()
{
    // ...
}
```

### 7. Clean Up After Tests

```php
protected function tearDown(): void
{
    // Clean up resources
    Storage::fake()->deleteDirectory('test-files');
    
    parent::tearDown();
}
```

### 8. Use Data Providers for Similar Tests

```php
/**
 * @dataProvider invalidEmailProvider
 */
public function test_registration_fails_with_invalid_email(string $email): void
{
    $response = $this->postJson('/api/register', [
        'email' => $email,
        'password' => 'password123',
    ]);

    $response->assertUnprocessable();
}

public static function invalidEmailProvider(): array
{
    return [
        ['not-an-email'],
        ['@example.com'],
        ['test@'],
        [''],
    ];
}
```

### 9. Test Edge Cases

```php
public function test_station_name_cannot_exceed_255_characters(): void
{
    $longName = str_repeat('a', 256);
    
    $response = $this->postJson('/api/stations', [
        'name' => $longName,
    ]);
    
    $response->assertUnprocessable();
}
```

### 10. Keep Tests Fast

```php
// ✅ Good - Use SQLite in-memory
// Configured in phpunit.xml

// ✅ Good - Use factories efficiently
$users = User::factory()->count(10)->create();

// ❌ Bad - Creating too many records
User::factory()->count(10000)->create(); // Slow!
```

---

## 📊 Test Configuration

### phpunit.xml

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="vendor/autoload.php">
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
    </php>
</phpunit>
```

### .env.testing

```env
APP_ENV=testing
APP_DEBUG=true

DB_CONNECTION=sqlite
DB_DATABASE=:memory:

CACHE_DRIVER=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
```

---

## 📚 Additional Resources

- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Laravel Testing Guide](https://laravel.com/docs/testing)
- [Laravel HTTP Tests](https://laravel.com/docs/http-tests)
- [Laravel Database Testing](https://laravel.com/docs/database-testing)
- [Testing Best Practices](https://www.thinktocode.com/testing-best-practices/)

---

## 🎯 Current Test Suite

### Test Commands Reference

```bash
# Basic commands
./test.sh                          # Run all tests
./test.sh --testsuite Feature      # Run feature tests only
./test.sh --testsuite Unit         # Run unit tests only

# Specific tests
./test.sh tests/Feature/AuthTest.php              # Run one file
./test.sh --filter testLoginSuccess               # Run one test
./test.sh --filter Auth                           # Run tests matching pattern

# Output options
./test.sh --verbose                # Show test names
./test.sh --testdox               # Human-readable output

# Coverage
vendor/bin/phpunit --coverage-html coverage       # Generate HTML report
vendor/bin/phpunit --coverage-text                # Console coverage
```

### Coverage Summary

| Test Type | Location | Purpose |
|-----------|----------|---------|
| Feature Tests | `tests/Feature/` | API endpoints, authentication, integration |
| Unit Tests | `tests/Unit/` | Models, services, business logic |
| Database | SQLite :memory: | Fast, isolated test database |
| Framework | PHPUnit 11.5 | Industry-standard PHP testing |

---

**Happy Testing! 🧪✨**
