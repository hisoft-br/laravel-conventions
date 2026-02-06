# Hisoft Laravel Conventions (Inertia)

When working with this Inertia project, follow the engineering conventions defined in the files below.

## Reference Files

### Base Conventions (upstream)

- `.ai/upstream/conventions.md` - Architecture and layer responsibilities
- `.ai/upstream/testing.md` - Testing strategy with Pest
- `.ai/upstream/exceptions.md` - Domain exception handling
- `.ai/upstream/phpdocs.md` - PHPDoc documentation conventions
- `.ai/upstream/static-analysis.md` - PHPStan/Larastan level 5

### Inertia Conventions (upstream)

- `.ai/upstream/pages.md` - Page component structure and naming
- `.ai/upstream/forms.md` - Form handling with useForm
- `.ai/upstream/props.md` - Data sharing and HandleInertiaRequests
- `.ai/upstream/components.md` - Reusable component conventions

### Project Rules (priority)

- `.ai/local/overrides.md` - Project-specific rules

## Priority

1. Rules in `.ai/local/*` override `.ai/upstream/*`
2. Always consult relevant files before generating code

## Core Principles

### Backend (PHP)

- Controllers are thin — all logic in Actions
- Actions never return HTTP responses
- Actions throw domain exceptions
- Requests validate and expose typed getters

### Frontend (Vue/React)

- Pages receive typed props from controllers
- Forms use useForm for validation and submission
- Components are reusable and well-typed

## Before Generating Code

1. ✅ Identify the layer (backend PHP or frontend)
2. ✅ Read relevant convention files from `.ai/upstream/`
3. ✅ Check for overrides in `.ai/local/overrides.md`
4. ✅ Apply architectural patterns (Controller → Action → Request)
5. ✅ Ensure PHPStan level 5 compliance for PHP
6. ✅ Add proper PHPDocs for PHP code
7. ✅ Use proper TypeScript typing for frontend code

**Never skip consulting the convention files.** They contain the detailed patterns, examples, and rules that must be followed.
