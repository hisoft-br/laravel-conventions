# Engineering Conventions

- Controllers are thin.
- All business logic lives in Actions.
- Actions never return HTTP responses.
- Requests validate and normalize input.
- All API responses use Laravel Resources.
- Domain errors are represented by Exceptions.
