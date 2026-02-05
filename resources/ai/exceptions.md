# Exception Handling

Exceptions represent domain-level failures.

They are not implementation details.
They are part of the business language.

---

## Domain Exceptions

Domain exceptions:
- Extend from a base DomainException.
- Represent invalid states or missing entities.
- Are thrown inside Actions.

Examples:
- UserNotFoundException
- InvalidCpfException
- EntityAlreadyExistsException

---

## When to Throw Exceptions

Throw an exception when:
- A required entity does not exist.
- A business rule is violated.
- Continuing the flow would produce an invalid state.

Do NOT throw exceptions when:
- Returning empty collections.
- Handling optional data.

---

## HTTP Translation

- Actions never return HTTP responses.
- Controllers never catch domain exceptions.
- Exception mapping happens in the global Handler.

Example mapping:
- UserNotFoundException → 404
- BusinessRuleViolationException → 422

---

## Benefits

- Clean controllers.
- Centralized error handling.
- Predictable API behavior.
- Easy testing.

---

## Rule

If the system cannot continue correctly,
the Action must throw a domain exception.
