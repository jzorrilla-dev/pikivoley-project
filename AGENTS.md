# AGENTS.md - PikiVoley Development Guide

This file provides guidance for AI agents working on the PikiVoley project.

---

## Project Overview

- **Framework:** Laravel 12.x
- **PHP:** 8.2+
- **Database:** PostgreSQL 16
- **Testing:** PHPUnit 11
- **Code Style:** Laravel Pint
- **Environment:** Docker via Laravel Sail

---

## Commands

### Running the Application

```bash
# Start the Docker environment
./vendor/bin/sail up -d

# Stop the Docker environment
./vendor/bin/sail down

# Run migrations
./vendor/bin/sail artisan migrate

# Fresh migration with seeding
./vendor/bin/sail artisan migrate:fresh --seed

# Clear configuration cache
./vendor/bin/sail artisan config:clear

# Tinker (interactive console)
./vendor/bin/sail artisan tinker
```

### Running Tests

```bash
# Run all tests
./vendor/bin/sail artisan test

# Run a specific test file
./vendor/bin/sail artisan test tests/Feature/MatchTest.php

# Run a single test method
./vendor/bin/sail artisan test --filter=test_can_create_match

# Run tests by group
./vendor/bin/sail artisan test --group=unit
./vendor/bin/sail artisan test --group=feature

# Run tests with coverage
./vendor/bin/sail artisan test --coverage

# Run tests in parallel (if installed)
./vendor/bin/sail artisan test --parallel

# Run only failed tests
./vendor/bin/sail artisan test --retry-failed
```

### Code Style (Laravel Pint)

```bash
# Run Pint to fix code style issues
./vendor/bin/sail artisan pint

# Run Pint with verbose output
./vendor/bin/sail artisan pint -v

# Run Pint to test without modifying files
./vendor/bin/sail artisan pint --test
```

### Creating Files

```bash
# Create model with factory, migration, seeder, and controller
./vendor/bin/sail artisan make:model Match -mfsc

# Create API controller
./vendor/bin/sail artisan make:controller MatchController --api --model=Match

# Create Form Request
./vendor/bin/sail artisan make:request StoreMatchRequest

# Create Service
./vendor/bin/sail artisan make:service MatchResultService

# Create API Resource
./vendor/bin/sail artisan make:resource MatchResource

# Create Test
./vendor/bin/sail artisan make:test MatchTest
```

---

## Code Style Guidelines

### PHP Standards

- **PHP Version:** 8.2+ - Use typed properties and return types everywhere
- **Strict Types:** Always use `declare(strict_types=1);` in new files
- **PHP Tags:** Use `<?php` only (no short tags `<?`)

### Naming Conventions

| Element | Convention | Example |
|---------|------------|---------|
| Models | PascalCase, singular | `User`, `Match`, `MatchTeam` |
| Controllers | PascalCase, plural + Controller | `MatchController`, `UserController` |
| Migrations | Snake_case, descriptive | `create_matches_table`, `add_status_to_matches` |
| Tables | Snake_case, plural | `users`, `match_teams`, `reputation_events` |
| Methods | camelCase | `getMatches()`, `confirmParticipation()` |
| Variables | camelCase | `$match`, `$playerId`, `$betAmount` |
| Constants | UPPER_SNAKE_CASE | `STATUS_PENDING`, `MAX_TOUCHES` |
| Test Methods | snake_case, descriptive | `test_can_create_match`, `test_captain_can_confirm` |

### File Organization

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Match extends Model
{
    // Constants (if any)
    public const STATUS_PENDING = 'pending';

    // Properties (fillable, hidden, casts)
    protected $fillable = [
        'created_by',
        'status',
        'bet_amount',
    ];

    // Relationships
    public function teams(): HasMany
    {
        return $this->hasMany(MatchTeam::class);
    }

    // Accessors & Mutators
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pendiente',
            default => $this->status,
        };
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    // Methods
    public function isEditable(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}
```

### Imports and Namespaces

- Use fully qualified class names after imports
- Group imports: built-in PHP, external packages, Laravel, local
- Sort alphabetically within groups
- One class per file

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreMatchRequest;
use App\Http\Resources\MatchResource;
use App\Models\Match;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
```

### Types and Type Hints

- **Always use return types:** `public function getId(): int`
- **Use nullable types:** `public function getName(): ?string`
- **Use union types (PHP 8+):** `public function setStatus(string|int $status): void`
- **Use typed properties:** `protected string $name;`
- **Use `mixed` sparingly:** Avoid when possible
- **Void returns:** Always declare `void` for procedures

### Controller Guidelines

- Use Form Requests for validation
- Return API Resources for collections/items
- Return `JsonResponse` or typed responses
- Keep controllers thin - delegate to Services

```php
public function index(Request $request): JsonResponse
{
    $matches = Match::query()
        ->when($request->status, fn($q, $status) => $q->where('status', $status))
        ->paginate();

    return response()->json(MatchResource::collection($matches));
}

public function store(StoreMatchRequest $request): JsonResponse
{
    $match = app(MatchService::class)->createMatch($request->validated());

    return response()->json(new MatchResource($match), 201);
}
```

### Service Layer

- Create Services for complex business logic
- Use constructor injection for dependencies
- Services should be registered in Service Providers if needed

```php
<?php

namespace App\Services;

use App\Models\Match;
use App\Models\User;

class MatchService
{
    public function __construct(
        protected ReputationService $reputationService,
    ) {}

    public function createMatch(array $data): Match
    {
        // Business logic here
    }
}
```

### Database and Eloquent

- Use migrations for all schema changes
- Use foreign key constraints
- Use `uuid` for sensitive models (optional)
- Use `$table` property for custom table names
- Define relationships with proper return types

```php
public function teams(): HasMany
{
    return $this->hasMany(MatchTeam::class);
}

public function referee(): BelongsTo
{
    return $this->belongsTo(User::class, 'referee_id');
}
```

### Error Handling

- Use exceptions for business logic errors
- Use Laravel's validation for request errors
- Return appropriate HTTP status codes:
  - `200` - Success
  - `201` - Created
  - `400` - Bad Request
  - `401` - Unauthorized
  - `403` - Forbidden
  - `404` - Not Found
  - `422` - Validation Error
  - `500` - Server Error

```php
throw new \RuntimeException('El árbitro no puede ser jugador del partido');

// Or use Laravel's built-in
throw new ModelNotFoundException(Match::class);
```

### Testing Guidelines

- Feature tests for API endpoints
- Unit tests for Services and business logic
- Use descriptive test names in snake_case
- Use `$this->assertTrue`, `$this->assertEquals`, etc.
- Use `actingAs()` for authenticated tests

```php
public function test_captain_can_confirm_match(): void
{
    $captain = User::factory()->create();
    $match = Match::factory()->create();

    $response = $this->actingAs($captain)
        ->postJson("/api/v1/matches/{$match->id}/confirm");

    $response->assertStatus(200)
        ->assertJsonPath('data.status', 'confirmed');
}
```

### Database Values

- All monetary values stored in **guaraníes (PYG)** as integers
- Use `bigint` for `bet_amount` and `reputation_score`
- Enums should be stored as strings in the database

---

## Architecture Patterns

### API Routes Structure

All API routes should be under `/api/v1/` with proper versioning:

```php
Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('matches', MatchController::class);
    Route::post('matches/{match}/confirm', [MatchController::class, 'confirm']);
});
```

### Request Validation

Always use Form Requests for validation:

```php
class StoreMatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'location' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'bet_amount' => ['required', 'integer', 'min:0'],
            'modality' => ['required', 'in:1x1,2x2,3x3'],
        ];
    }
}
```

---

## Security Considerations

- Never expose sensitive data in API responses (use `$hidden` in models)
- Always validate user input
- Use parameterized queries (Eloquent handles this)
- Protect against mass assignment (use `$fillable` and `$guarded`)
- Sanitize user-generated content

---

## Resources

- Laravel Documentation: https://laravel.com/docs
- Laravel Pint: https://laravel.com/docs/pint
- PHPUnit: https://phpunit.de/documentation.html
