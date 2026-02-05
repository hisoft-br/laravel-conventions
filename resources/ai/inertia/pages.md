# Inertia Pages Conventions

Pages are the entry points for Inertia applications.

They connect Laravel routes to frontend components.

---

## Directory Structure

```
resources/js/
  Pages/
    Auth/
      Login.vue
      Register.vue
    Users/
      Index.vue
      Show.vue
      Create.vue
      Edit.vue
    Dashboard.vue
```

---

## Naming Conventions

- Use PascalCase for page components.
- Mirror Laravel resource naming (Index, Show, Create, Edit).
- Group by domain/feature, not by action.

```
// Good
Pages/Users/Index.vue
Pages/Users/Show.vue
Pages/Orders/Index.vue

// Bad
Pages/ListUsers.vue
Pages/UserDetails.vue
```

---

## Page Component Structure

Standard page structure:

```vue
<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

interface Props {
  users: App.Data.UserData[]
  filters: {
    search: string
  }
}

const props = defineProps<Props>()
</script>

<template>
  <Head title="Users" />

  <AuthenticatedLayout>
    <template #header>
      <h2>Users</h2>
    </template>

    <!-- Page content -->
  </AuthenticatedLayout>
</template>
```

---

## Layouts

Use layouts for consistent structure:

```vue
// Layouts/AuthenticatedLayout.vue
<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'

const page = usePage()
</script>

<template>
  <div class="min-h-screen">
    <nav><!-- Navigation --></nav>

    <header v-if="$slots.header">
      <slot name="header" />
    </header>

    <main>
      <slot />
    </main>
  </div>
</template>
```

---

## Controller to Page

Return Inertia responses from controllers:

```php
class UserController extends Controller
{
    public function index(ListUsersAction $action): Response
    {
        return Inertia::render('Users/Index', [
            'users' => UserData::collect($action->execute()),
            'filters' => request()->only(['search', 'status']),
        ]);
    }

    public function show(User $user): Response
    {
        return Inertia::render('Users/Show', [
            'user' => UserData::from($user),
        ]);
    }
}
```

---

## TypeScript Types

Generate types from Laravel:

```typescript
// types/generated.d.ts
declare namespace App.Data {
  export interface UserData {
    id: number
    name: string
    email: string
    created_at: string
  }
}
```

Use tools like `spatie/laravel-typescript-transformer`.

---

## Persistent Layouts

For layouts that persist across navigation:

```vue
<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineOptions({
  layout: AuthenticatedLayout,
})
</script>
```

---

## Title and Meta

Use `<Head>` for SEO:

```vue
<Head>
  <title>{{ user.name }} - Profile</title>
  <meta name="description" :content="user.bio" />
</Head>
```

---

## Golden Rules

- One page component per route.
- Pages receive data, components are reusable.
- Keep pages thin, extract logic to composables.
- Type all props from Laravel.
- Use layouts for shared UI.
