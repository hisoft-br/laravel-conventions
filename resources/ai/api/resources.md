# API Resources

Laravel Resources define the public API contract.

They control how data is presented to consumers.

---

## Purpose

- Transform models into JSON responses.
- Hide internal structure from API consumers.
- Provide a stable contract for frontend/mobile clients.
- Enable versioning without changing models.

---

## Resource Structure

Every Resource must:
- Extend `JsonResource`.
- Define explicit fields in `toArray()`.
- Never expose all model attributes blindly.

```php
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
```

---

## Collections

- Use `Resource::collection()` for lists.
- Create dedicated ResourceCollection when pagination metadata is needed.

```php
// Simple collection
return UserResource::collection($users);

// With pagination
return new UserCollection($users->paginate(15));
```

---

## Nested Resources

- Use Resources for relationships.
- Load relationships explicitly to avoid N+1.

```php
return [
    'id' => $this->id,
    'author' => new AuthorResource($this->whenLoaded('author')),
    'tags' => TagResource::collection($this->whenLoaded('tags')),
];
```

---

## Conditional Fields

Use `when()` and `whenLoaded()` for optional data:

```php
return [
    'id' => $this->id,
    'secret' => $this->when($request->user()->isAdmin(), $this->secret),
    'profile' => new ProfileResource($this->whenLoaded('profile')),
];
```

---

## Pagination Response

Paginated responses include metadata:

```json
{
    "data": [...],
    "links": {
        "first": "...",
        "last": "...",
        "prev": null,
        "next": "..."
    },
    "meta": {
        "current_page": 1,
        "last_page": 10,
        "per_page": 15,
        "total": 150
    }
}
```

---

## Error Responses

Standardize error format in the Exception Handler:

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

---

## Golden Rules

- Never return raw models from controllers.
- Resources are the only way to output data.
- Keep Resources focused — one per entity.
- Document breaking changes when modifying Resources.
