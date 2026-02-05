# Laravel Conventions

Engineering conventions for Laravel projects, designed to ensure consistency, scalability, testability, and effective AI-assisted development.

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

## IDE Integration

### Cursor

If you use Cursor IDE, install the integration rule:

**For API projects:**

```bash
php artisan hisoft:cursor --api
```

**For Inertia projects:**

```bash
php artisan hisoft:cursor --inertia
```

This creates `.cursor/rules/hisoft.mdc` which automatically loads the conventions when working with relevant files.

The publish tags also install `AGENTS.md` in the project root, providing global rules that complement the `.mdc` file.

- **API**: Rules are loaded when editing PHP files (`**/*.php`)
- **Inertia**: Rules are loaded when editing PHP or frontend files (`**/*.{php,vue,ts,tsx,js,jsx}`)

Options:
- `--api` - Install rules for API projects (PHP only)
- `--inertia` - Install rules for Inertia projects (PHP + Vue/React)
- `--force` - Overwrite existing file without confirmation

If no option is provided, the command will prompt you to choose the project type.

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
