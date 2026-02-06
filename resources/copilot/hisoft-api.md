# Hisoft Laravel Conventions (API)

When working with PHP code in this project, follow the engineering conventions defined in the files below.

## Reference Files

### Base Conventions (upstream)

- `.ai/upstream/conventions.md` - Architecture and layer responsibilities
- `.ai/upstream/testing.md` - Testing strategy with Pest
- `.ai/upstream/exceptions.md` - Domain exception handling
- `.ai/upstream/phpdocs.md` - PHPDoc documentation conventions
- `.ai/upstream/static-analysis.md` - PHPStan/Larastan level 5

### API Conventions (upstream)

- `.ai/upstream/crud.md` - CRUD implementation guidelines
- `.ai/upstream/resources.md` - Laravel Resources and API response patterns

### Project Rules (priority)

- `.ai/local/overrides.md` - Project-specific rules

## Priority

1. Rules in `.ai/local/*` override `.ai/upstream/*`
2. Always consult relevant files before generating code

## Core Principles

- Controllers are thin — all logic in Actions
- Actions never return HTTP responses
- Actions throw domain exceptions
- Requests validate and expose typed getters
- API responses use Resources

## Before Generating Code

1. ✅ Read relevant convention files from `.ai/upstream/`
2. ✅ Check for overrides in `.ai/local/overrides.md`
3. ✅ Apply architectural patterns (Controller → Action → Request)
4. ✅ Ensure PHPStan level 5 compliance
5. ✅ Add proper PHPDocs following conventions

**Never skip consulting the convention files.** They contain the detailed patterns, examples, and rules that must be followed.
