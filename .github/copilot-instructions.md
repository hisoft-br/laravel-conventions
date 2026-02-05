# Hisoft Laravel Conventions

This project follows strict engineering conventions. **Always consult the relevant convention files before generating any code.**

## Convention Files Structure

All conventions are located in the `.ai/` directory with the following priority:

1. **Project-specific rules** (`.ai/local/*`) - **HIGHEST PRIORITY**
2. **Upstream conventions** (`.ai/upstream/*`) - Base conventions

When there's a conflict, **always prioritize `.ai/local/*` rules**.

## Base Conventions (All Projects)

Before writing PHP code, consult these files:

- `.ai/upstream/conventions.md` - Architecture and layer responsibilities
- `.ai/upstream/testing.md` - Testing strategy with Pest
- `.ai/upstream/exceptions.md` - Domain exception handling
- `.ai/upstream/phpdocs.md` - PHPDoc documentation conventions
- `.ai/upstream/static-analysis.md` - PHPStan/Larastan level 5 requirements

## API Projects

For API projects, also consult:

- `.ai/upstream/crud.md` - CRUD implementation guidelines
- `.ai/upstream/resources.md` - Laravel Resources and API response patterns

## Inertia Projects

For Inertia projects, also consult:

- `.ai/upstream/pages.md` - Page component structure and naming
- `.ai/upstream/forms.md` - Form handling with useForm
- `.ai/upstream/props.md` - Data sharing and HandleInertiaRequests
- `.ai/upstream/components.md` - Reusable component conventions

## Core Architecture Principles

**Controllers:**
- Thin controllers - delegate all logic to Actions
- Never contain business logic
- Handle HTTP concerns only (requests, responses, redirects)

**Actions:**
- Contain all business logic
- Never return HTTP responses (return domain objects)
- Throw domain exceptions on failure
- Are invokable or have an `execute()` method

**Requests:**
- Validate input data
- Expose typed getter methods (e.g., `getName(): string`)
- Never use `validated()` or `input()` directly in controllers

**Exceptions:**
- Use domain-specific exceptions
- Controller catches and transforms to HTTP responses
- Follow conventions in `.ai/upstream/exceptions.md`

## Before Generating Code

1. ✅ Identify project type (API or Inertia)
2. ✅ Read relevant convention files from `.ai/upstream/`
3. ✅ Check for overrides in `.ai/local/overrides.md`
4. ✅ Apply architectural patterns (Controller → Action → Request)
5. ✅ Ensure PHPStan level 5 compliance
6. ✅ Add proper PHPDocs following conventions

## Example: Creating a CRUD Endpoint

1. Read `.ai/upstream/crud.md` for CRUD patterns
2. Read `.ai/upstream/conventions.md` for architecture
3. Check `.ai/local/overrides.md` for project-specific rules
4. Generate code following the established patterns

**Never skip consulting the convention files.** They contain the detailed patterns, examples, and rules that must be followed.
