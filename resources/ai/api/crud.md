# CRUD Guidelines

In this project, a CRUD is not just a set of controllers.
It is a complete flow with clear separation of responsibilities.

## Required Components

For each CRUD, the following components must be created:

- Routes
- Form Requests
- Controller
- Actions (one per operation)
- Resources
- Domain Exceptions (when applicable)
- Tests (Unit + Feature)

---

## Actions

Each operation has its own Action:

- ListEntitiesAction
- ShowEntityAction
- CreateEntityAction
- UpdateEntityAction
- DeleteEntityAction

Actions:
- Return domain entities or collections.
- Throw domain exceptions when needed.
- Never return arrays or responses.

---

## Requests

- One Request per write operation.
- Expose typed getters.
- Perform validation only.

Example:
- StoreEntityRequest
- UpdateEntityRequest

---

## Resources

- All responses must use Resources.
- Collections must use Resource::collection().
- Resources define the public API contract.

---

## Error Handling

- "Not found" is a domain error when an entity is expected.
- Empty collections are valid results.
- Domain exceptions are translated in the Handler.

---

## CRUD Is Complete Only If

- All layers are present.
- Actions are unit tested.
- Endpoints are feature tested.
- Resources define the output explicitly.
