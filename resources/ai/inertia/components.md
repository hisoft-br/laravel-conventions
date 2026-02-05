# Inertia Components Conventions

Components are reusable UI building blocks.

Pages use components, components don't know about pages.

---

## Directory Structure

```
resources/js/
  Components/
    UI/
      Button.vue
      Input.vue
      Modal.vue
      Card.vue
    Forms/
      TextInput.vue
      SelectInput.vue
      Checkbox.vue
    Tables/
      DataTable.vue
      Pagination.vue
    Layout/
      Sidebar.vue
      Header.vue
```

---

## Component vs Page

**Pages:**
- Receive props from Laravel
- Know about routes
- Define layout
- One per route

**Components:**
- Receive props from parents
- Don't know about routes
- Reusable anywhere
- Many per page

---

## Basic Component

```vue
<script setup lang="ts">
interface Props {
  label: string
  type?: 'button' | 'submit' | 'reset'
  variant?: 'primary' | 'secondary' | 'danger'
  disabled?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  type: 'button',
  variant: 'primary',
  disabled: false,
})

const emit = defineEmits<{
  click: [event: MouseEvent]
}>()
</script>

<template>
  <button
    :type="props.type"
    :disabled="props.disabled"
    :class="[
      'btn',
      `btn-${props.variant}`,
      { 'btn-disabled': props.disabled }
    ]"
    @click="emit('click', $event)"
  >
    <slot />
  </button>
</template>
```

---

## Props Typing

Always type props:

```typescript
interface Props {
  // Required
  user: App.Data.UserData

  // Optional with default
  showAvatar?: boolean

  // Array
  items: string[]

  // Object
  config: {
    enabled: boolean
    threshold: number
  }

  // Function
  onSelect?: (id: number) => void
}

const props = withDefaults(defineProps<Props>(), {
  showAvatar: true,
})
```

---

## Events

Type emitted events:

```typescript
const emit = defineEmits<{
  // No payload
  close: []

  // With payload
  select: [id: number]

  // Multiple params
  update: [field: string, value: unknown]
}>()

// Usage
emit('close')
emit('select', 123)
emit('update', 'name', 'John')
```

---

## Slots

Use typed slots:

```vue
<script setup lang="ts">
defineSlots<{
  default: (props: { item: string }) => any
  header?: () => any
  footer?: () => any
}>()
</script>

<template>
  <div>
    <header v-if="$slots.header">
      <slot name="header" />
    </header>

    <slot :item="currentItem" />

    <footer v-if="$slots.footer">
      <slot name="footer" />
    </footer>
  </div>
</template>
```

---

## Composables

Extract reusable logic:

```typescript
// composables/useSearch.ts
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { debounce } from 'lodash-es'

export function useSearch(initialValue = '') {
  const search = ref(initialValue)

  const debouncedSearch = debounce((value: string) => {
    router.get(
      route(route().current()!),
      { search: value },
      { preserveState: true }
    )
  }, 300)

  function updateSearch(value: string) {
    search.value = value
    debouncedSearch(value)
  }

  return {
    search,
    updateSearch,
  }
}
```

Usage:

```vue
<script setup lang="ts">
import { useSearch } from '@/composables/useSearch'

const { search, updateSearch } = useSearch(props.filters.search)
</script>
```

---

## Form Input Components

Wrap inputs for consistency:

```vue
<script setup lang="ts">
interface Props {
  modelValue: string
  label?: string
  error?: string
  type?: string
}

const props = withDefaults(defineProps<Props>(), {
  type: 'text',
})

const emit = defineEmits<{
  'update:modelValue': [value: string]
}>()
</script>

<template>
  <div class="form-group">
    <label v-if="label">{{ label }}</label>
    <input
      :type="type"
      :value="modelValue"
      :class="{ 'input-error': error }"
      @input="emit('update:modelValue', ($event.target as HTMLInputElement).value)"
    />
    <span v-if="error" class="error">{{ error }}</span>
  </div>
</template>
```

---

## v-model Support

Implement v-model pattern:

```vue
<script setup lang="ts">
const props = defineProps<{
  modelValue: boolean
}>()

const emit = defineEmits<{
  'update:modelValue': [value: boolean]
}>()

const checked = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value),
})
</script>

<template>
  <input v-model="checked" type="checkbox" />
</template>
```

---

## Async Components

Lazy load heavy components:

```vue
<script setup lang="ts">
import { defineAsyncComponent } from 'vue'

const HeavyChart = defineAsyncComponent(() =>
  import('@/Components/Charts/HeavyChart.vue')
)
</script>

<template>
  <Suspense>
    <HeavyChart :data="chartData" />
    <template #fallback>
      <div>Loading chart...</div>
    </template>
  </Suspense>
</template>
```

---

## Golden Rules

- Components don't fetch data.
- Type all props and events.
- Use composables for shared logic.
- Keep components focused.
- Prefer composition over inheritance.
