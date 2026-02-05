# Engineering Conventions

These conventions define how Laravel projects are structured and how business
logic is implemented.

They are designed to ensure consistency, scalability, testability, and effective
AI-assisted development.

---

## Architectural Principles

- Controllers are thin.
- All business logic lives in Actions (Use Cases).
- Actions never return HTTP responses.
- Actions may throw domain exceptions.
- HTTP translation happens in the global Exception Handler.
- Requests validate and normalize input.
- Requests expose typed getters.
- All API responses use Laravel Resources.
- Models represent persistence, not behavior orchestration.
- Method names, database columns, tables, and variables are named in English.

---

## Layer Responsibilities

### Routes
- Define HTTP entry points.
- Do not contain logic.

### Controllers
- Receive Requests.
- Call Actions.
- Return Resources.
- Never contain business rules.

### Requests
- Validate input.
- Normalize data.
- Expose typed getters.
- Do not contain business logic.

### DTOs and Value Objects (Recommendation)
- Recommended when the project already uses DTOs/VOs.
- If the project does not use DTOs, keep using Requests + typed getters.
- Consider introducing DTOs when:
  - input/output is complex,
  - data is reused across Actions,
  - or validation/transformation grows too much inside the Request.

### Actions (Use Cases)
- Contain business rules.
- Orchestrate models, services, and repositories.
- Return domain objects or collections.
- Throw domain exceptions when rules are violated.
- Do not know anything about HTTP.

### Resources
- Define the API contract.
- Control output shape.
- Hide internal model structure.

### Exceptions
- Represent domain errors.
- Are mapped to HTTP responses in the Handler.

---

## Database

- Schema is the source of truth.
- Actions never assume schema — they rely on models.

---

## Testing Strategy

- Actions are tested with unit tests.
- Controllers are tested with feature tests.
- Resources are validated via JSON assertions.
- Pest is the standard testing framework.

---

## Golden Rules

- No business logic in Controllers.
- No HTTP concerns in Actions.
- Avoid plain arrays; use a defined contract.
- No silent failures — domain errors use exceptions.
