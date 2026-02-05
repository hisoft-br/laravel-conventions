# Testing Strategy

Testing is part of the architecture.

Tests validate behavior, not implementation details.

---

## Testing Layers

### Unit Tests
- Target Actions.
- Validate business rules.
- Assert exceptions.
- Use the database when needed.

### Feature Tests
- Target HTTP endpoints.
- Validate status codes.
- Validate Resources output.
- Validate validation errors.

---

## Tools

- Pest is the testing framework.
- RefreshDatabase is used by default.
- PostgreSQL is used in testing.

---

## What to Test

### Actions
- Successful execution.
- Domain rule violations.
- Exception throwing.

### Controllers / Endpoints
- HTTP status codes.
- JSON structure.
- Resource formatting.

---

## What NOT to Test

- Internal Laravel behavior.
- Framework internals.
- Private implementation details.

---

## Golden Rule

If business logic exists,
it must be covered by an Action test.
