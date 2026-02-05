# Inertia Forms Conventions

Forms connect frontend input to Laravel validation.

Use `useForm` for all form handling.

---

## Basic Form Usage

```vue
<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
  password: '',
})

function submit() {
  form.post(route('users.store'), {
    onSuccess: () => form.reset(),
  })
}
</script>

<template>
  <form @submit.prevent="submit">
    <input v-model="form.name" type="text" />
    <span v-if="form.errors.name">{{ form.errors.name }}</span>

    <button type="submit" :disabled="form.processing">
      Create
    </button>
  </form>
</template>
```

---

## Form State

`useForm` provides reactive state:

- `form.data()` - Current form data
- `form.errors` - Validation errors from Laravel
- `form.processing` - Request in progress
- `form.wasSuccessful` - Last request succeeded
- `form.recentlySuccessful` - Success within last 2 seconds
- `form.isDirty` - Form has unsaved changes

---

## HTTP Methods

```javascript
// Create
form.post(route('users.store'))

// Update
form.put(route('users.update', user.id))
form.patch(route('users.update', user.id))

// Delete
form.delete(route('users.destroy', user.id))
```

---

## Handling Responses

```javascript
form.post(route('users.store'), {
  onSuccess: (page) => {
    // Redirect happened, form reset
  },
  onError: (errors) => {
    // Validation failed
    // errors available in form.errors
  },
  onFinish: () => {
    // Always called
  },
})
```

---

## File Uploads

Use `form.post` with files:

```vue
<script setup lang="ts">
const form = useForm({
  name: '',
  avatar: null as File | null,
})

function submit() {
  form.post(route('users.store'), {
    forceFormData: true,
  })
}
</script>

<template>
  <input
    type="file"
    @input="form.avatar = ($event.target as HTMLInputElement).files?.[0] ?? null"
  />
</template>
```

---

## Transform Data

Transform before sending:

```javascript
form.transform((data) => ({
  ...data,
  terms_accepted: data.terms ? 'yes' : 'no',
})).post(route('users.store'))
```

---

## Preserve State

Keep form data on navigation:

```javascript
form.post(route('users.store'), {
  preserveState: true,  // Keep component state
  preserveScroll: true, // Keep scroll position
})
```

---

## Progress Indicator

Show upload/submission progress:

```vue
<template>
  <progress
    v-if="form.progress"
    :value="form.progress.percentage"
    max="100"
  >
    {{ form.progress.percentage }}%
  </progress>
</template>
```

---

## Reset and Clear

```javascript
// Reset to initial values
form.reset()

// Reset specific fields
form.reset('password', 'password_confirmation')

// Clear errors
form.clearErrors()
form.clearErrors('email')
```

---

## Laravel Validation

Laravel handles validation normally:

```php
class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
        ];
    }
}
```

Errors are automatically sent to `form.errors`.

---

## Typed Forms

Type your form data:

```typescript
interface CreateUserForm {
  name: string
  email: string
  password: string
  password_confirmation: string
}

const form = useForm<CreateUserForm>({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})
```

---

## Golden Rules

- Always use `useForm` for form submissions.
- Disable submit button while `form.processing`.
- Show validation errors inline.
- Reset form on success when appropriate.
- Type form data in TypeScript.
