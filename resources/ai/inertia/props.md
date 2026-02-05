# Inertia Props Conventions

Props are how Laravel sends data to pages.

Manage them carefully for performance.

---

## Basic Props

Pass data from controllers:

```php
return Inertia::render('Users/Show', [
    'user' => UserData::from($user),
    'canEdit' => $request->user()->can('update', $user),
]);
```

Receive in page component:

```vue
<script setup lang="ts">
interface Props {
  user: App.Data.UserData
  canEdit: boolean
}

const props = defineProps<Props>()
</script>
```

---

## Shared Data

Share data globally via middleware:

```php
// app/Http/Middleware/HandleInertiaRequests.php
class HandleInertiaRequests extends Middleware
{
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user()
                    ? UserData::from($request->user())
                    : null,
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
        ]);
    }
}
```

Access anywhere:

```vue
<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const user = computed(() => page.props.auth.user)
</script>
```

---

## Lazy Props

Defer expensive data:

```php
return Inertia::render('Users/Show', [
    'user' => UserData::from($user),
    'activity' => Inertia::lazy(fn () =>
        ActivityData::collect($user->activity()->latest()->take(10)->get())
    ),
]);
```

Load on demand:

```vue
<script setup lang="ts">
import { router } from '@inertiajs/vue3'

function loadActivity() {
  router.reload({ only: ['activity'] })
}
</script>
```

---

## Optional Props

For conditionally included data:

```php
return Inertia::render('Users/Index', [
    'users' => UserData::collect($users),
    'stats' => Inertia::optional(fn () =>
        $this->calculateStats()
    ),
]);
```

---

## Partial Reloads

Reload specific props:

```javascript
import { router } from '@inertiajs/vue3'

// Reload only 'users' prop
router.reload({ only: ['users'] })

// Reload everything except 'stats'
router.reload({ except: ['stats'] })
```

---

## Deferred Props

Load after initial render:

```php
return Inertia::render('Dashboard', [
    'quickStats' => $quickStats,
    'heavyReport' => Inertia::defer(fn () =>
        $this->generateReport()
    ),
]);
```

---

## Flash Messages

Set in Laravel:

```php
return redirect()
    ->route('users.index')
    ->with('success', 'User created successfully.');
```

Display in layout:

```vue
<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const flash = computed(() => page.props.flash)
</script>

<template>
  <div v-if="flash.success" class="alert-success">
    {{ flash.success }}
  </div>
  <div v-if="flash.error" class="alert-error">
    {{ flash.error }}
  </div>
</template>
```

---

## Type Safety

Define shared props globally:

```typescript
// types/inertia.d.ts
import type { PageProps as InertiaPageProps } from '@inertiajs/core'

declare module '@inertiajs/core' {
  interface PageProps extends InertiaPageProps {
    auth: {
      user: App.Data.UserData | null
    }
    flash: {
      success: string | null
      error: string | null
    }
  }
}
```

---

## Preserving State

Preserve local state on navigation:

```javascript
import { router } from '@inertiajs/vue3'

router.visit(url, {
  preserveState: true,
})
```

---

## Data Transfer Objects

Use Laravel Data for type-safe props:

```php
class UserData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public Carbon $created_at,
    ) {}
}
```

---

## Golden Rules

- Use DTOs/Data objects for props.
- Lazy load expensive computations.
- Share only essential global data.
- Type all props in TypeScript.
- Use partial reloads for updates.
