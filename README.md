# Laravel Conventions

Engineering conventions for Laravel projects, designed to enforce a professional architecture, faster refactors, and AI-assisted development that stays on-rails.

## What This Enables

- Consistent structure across teams and projects
- Refactors that are predictable, safe, and fast
- Clean separation of concerns (Actions, Requests, Resources)
- Strong testing posture with clear boundaries
- AI outputs aligned with your standards, not random guesses

## Refactor Impact (Example)

Before: mixed responsibilities in controllers, implicit rules, inconsistent naming.  
After: Actions own business logic, Requests handle validation, Resources define API shape, and naming stays in English across the stack.

## Installation

```bash
composer require hisoft/laravel-conventions --dev
```

## Usage

This package supports two types of Laravel projects:

### API Projects

For backend/REST API projects:

```bash
php artisan vendor:publish --tag=hisoft-api
```

Publishes:
- Shared conventions (architecture, exceptions, testing, PHPDocs, static analysis)
- API-specific conventions (CRUD, Resources)

### Inertia Projects

For modern monolith projects with Vue/React:

```bash
php artisan vendor:publish --tag=hisoft-inertia
```

Publishes:
- Shared conventions (architecture, exceptions, testing, PHPDocs, static analysis)
- Inertia-specific conventions (Pages, Forms, Props, Components)

## Published Structure

```
AGENTS.md          # Global agent rules (published to project root)
.ai/
  upstream/          # Package conventions (do not edit)
    conventions.md
    exceptions.md
    testing.md
    phpdocs.md
    static-analysis.md
    # API: crud.md, resources.md
    # Inertia: pages.md, forms.md, props.md, components.md
  local/             # Project-specific overrides (editable)
    overrides.md
```

## AI Tools Integration

### Cursor IDE

Install Cursor rules to automatically load conventions when working with project files:

**For API projects:**

```bash
php artisan hisoft:cursor --api
```

**For Inertia projects:**

```bash
php artisan hisoft:cursor --inertia
```

This creates `.cursor/rules/hisoft.mdc` which automatically loads the conventions when working with relevant files.

- **API**: Rules are loaded when editing PHP files (`**/*.php`)
- **Inertia**: Rules are loaded when editing PHP or frontend files (`**/*.{php,vue,ts,tsx,js,jsx}`)

### GitHub Copilot

Install Copilot instructions to guide AI-assisted development:

**For API projects:**

```bash
php artisan hisoft:copilot --api
```

**For Inertia projects:**

```bash
php artisan hisoft:copilot --inertia
```

This creates `.github/copilot-instructions.md` which GitHub Copilot automatically reads to understand project conventions.

### Shared Options

Both commands support the following options:
- `--api` - Install for API projects (PHP only)
- `--inertia` - Install for Inertia projects (PHP + Vue/React)
- `--force` - Overwrite existing file without confirmation

If no option is provided, the command will prompt you to choose the project type.

### Global Agent Rules

The publish tags also install `AGENTS.md` in the project root, providing global rules that work across all AI tools.

### Other Tools

The conventions in `.ai/` are compatible with any AI-assisted development tool. Reference the files in your tool's context as needed.

## Priority Order

When conventions conflict:
1. `.ai/local/*` (project-specific rules)
2. `.ai/upstream/*` (package conventions)

## Conventions Overview

### Shared (both API and Inertia)

| File | Description |
|------|-------------|
| `conventions.md` | Architectural principles and layer responsibilities |
| `exceptions.md` | Domain exception handling patterns |
| `testing.md` | Testing strategy with Pest |
| `phpdocs.md` | PHPDoc conventions for code documentation |
| `static-analysis.md` | PHPStan/Larastan level 5 conventions |

### API-specific

| File | Description |
|------|-------------|
| `crud.md` | CRUD implementation guidelines |
| `resources.md` | Laravel Resources and API response patterns |

### Inertia-specific

| File | Description |
|------|-------------|
| `pages.md` | Page component structure and naming |
| `forms.md` | Form handling with useForm |
| `props.md` | Data sharing and HandleInertiaRequests |
| `components.md` | Reusable component conventions |

## Development

Run tests:

```bash
docker compose run --rm test
```

Run static analysis:

```bash
composer analyse
```

## License

MIT
