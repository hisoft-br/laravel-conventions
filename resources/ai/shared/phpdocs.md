# PHPDoc Conventions

PHPDocs are essential for code understanding.

They help developers and AI assistants understand intent.

---

## When to Document

Always document:
- Public methods in classes.
- Complex private methods.
- Method parameters and return types.
- Thrown exceptions.
- Class purpose.

Skip documentation when:
- The code is self-explanatory (getters/setters with typed properties).
- It would be redundant with type hints.

---

## Class Documentation

Every class should have a description:

```php
/**
 * Handles user registration business logic.
 *
 * Validates user data, creates the user record,
 * and dispatches welcome notification.
 */
class RegisterUserAction
{
}
```

---

## Method Documentation

Document parameters, return types, and exceptions:

```php
/**
 * Creates a new user from registration data.
 *
 * @param RegisterUserRequest $request Validated registration data
 * @return User The newly created user
 *
 * @throws EmailAlreadyExistsException When email is taken
 * @throws WeakPasswordException When password doesn't meet requirements
 */
public function execute(RegisterUserRequest $request): User
{
}
```

---

## Parameter Annotations

Use `@param` for complex types:

```php
/**
 * @param array<string, mixed> $filters Search filters
 * @param Collection<int, User> $users Users to process
 * @param callable(User): bool $callback Filter callback
 */
```

---

## Return Type Annotations

Use `@return` for complex return types:

```php
/**
 * @return Collection<int, User>
 * @return array{name: string, email: string}
 * @return User|null
 */
```

---

## Generic Types

Use generics for Collections and arrays:

```php
/**
 * @param Collection<int, Product> $products
 * @return LengthAwarePaginator<User>
 */
```

---

## Eloquent Relationships

Document relationship return types:

```php
/**
 * @return HasMany<Post>
 */
public function posts(): HasMany
{
    return $this->hasMany(Post::class);
}

/**
 * @return BelongsTo<User, $this>
 */
public function author(): BelongsTo
{
    return $this->belongsTo(User::class, 'author_id');
}
```

---

## Deprecation

Use `@deprecated` with migration path:

```php
/**
 * @deprecated Use RegisterUserAction instead. Will be removed in v3.0.
 */
public function registerUser(): void
{
}
```

---

## Property Documentation

Document class properties when type is complex:

```php
class UserService
{
    /**
     * @var Collection<int, User>
     */
    private Collection $cachedUsers;

    /**
     * @var array<string, callable>
     */
    private array $handlers;
}
```

---

## Golden Rules

- PHPDocs complement types, not replace them.
- Document the "why", not the "what".
- Keep descriptions concise.
- Update docs when code changes.
- Use `@throws` for all thrown exceptions.
