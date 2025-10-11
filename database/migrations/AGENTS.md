# Migration Agent Notes

- Do **not** reference application models inside migrations. Use column helpers like `foreignId('project_id')` instead of `foreignIdFor(Model::class)`.
- Avoid adding foreign key constraints or cascading rules; define only the schema and supporting indexes.
- Keep business logic out of migrations. Focus solely on column definitions and database structure.
- For example, for `project_id` columns, define `foreignId('project_id')->index()` without constraints.
