# Static Analysis Conventions

Static analysis catches bugs before runtime.

PHPStan with Larastan is the standard tool.

---

## Minimum Level

All projects must pass PHPStan level 5.

Level 5 includes:
- Type checking for parameters and return types.
- Dead code detection.
- Unknown method/property access.
- Incorrect function calls.

---

## Configuration

Standard `phpstan.neon`:

```neon
includes:
    - vendor/larastan/larastan/extension.neon

parameters:
    level: 5
    paths:
        - app
        - src
    tmpDir: build/phpstan
```

---

## Running Analysis

```bash
# Run analysis
./vendor/bin/phpstan analyse

# With memory limit
./vendor/bin/phpstan analyse --memory-limit=512M

# Generate baseline
./vendor/bin/phpstan analyse --generate-baseline
```

---

## Type Declarations

Always use native types when possible:

```php
// Good
public function process(User $user): Response

// Avoid
public function process($user)
```

---

## Avoiding Mixed

Avoid `mixed` type. Be explicit:

```php
// Bad
public function handle(mixed $data): mixed

// Good
public function handle(array $data): Response
public function handle(Request $data): JsonResponse
```

---

## Array Types

Specify array contents:

```php
// Bad
public function process(array $items): array

// Good
/**
 * @param array<int, Product> $items
 * @return array<string, int>
 */
public function process(array $items): array
```

---

## Ignoring Errors

Use `@phpstan-ignore-*` sparingly and with reason:

```php
// @phpstan-ignore-next-line - Dynamic method from macro
$collection->customMacro();

/** @phpstan-ignore-line - Third-party library typing issue */
$result = $legacyService->process($data);
```

---

## PHPStan Annotations

Use PHPStan-specific annotations for complex types:

```php
/**
 * @phpstan-param positive-int $page
 * @phpstan-return non-empty-array<User>
 */
public function paginate(int $page): array

/**
 * @phpstan-assert User $user
 */
public function ensureUser(mixed $user): void
```

---

## Generics

Use generics for collections:

```php
/**
 * @template T of Model
 * @param class-string<T> $model
 * @return T|null
 */
public function findOrNull(string $model, int $id): ?Model
```

---

## Baseline

Use baseline for legacy code migration:

```bash
# Generate baseline from existing errors
./vendor/bin/phpstan analyse --generate-baseline

# Configure in phpstan.neon
# includes:
#     - phpstan-baseline.neon
```

Do NOT add new errors to baseline.

---

## CI Integration

PHPStan must run in CI pipeline:

```yaml
- name: Run PHPStan
  run: ./vendor/bin/phpstan analyse --error-format=github
```

---

## Golden Rules

- Never suppress errors without documented reason.
- Fix errors, don't ignore them.
- New code must pass analysis.
- Keep baseline from growing.
- Update types when refactoring.
