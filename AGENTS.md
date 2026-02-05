# Hisoft Conventions - Agent Rules

These rules define the agent's global behavior in this project.

## Priority
1. `.ai/local/*` (project rules)
2. `.ai/upstream/*` (package rules)

## Required
- Always consult relevant files in `.ai/` before generating code.
- If there is a conflict, follow the most specific, higher-priority rule.
- Do not suggest patterns that conflict with the defined conventions.

## Scope
- These rules are global and complement per-file rules (e.g., `.mdc`).
