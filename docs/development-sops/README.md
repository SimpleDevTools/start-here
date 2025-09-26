# Development Standard Operating Procedures

This directory contains documented procedures for common development tasks in the application. These SOPs ensure consistency and efficiency across the development team.

## Available SOPs

### Data Models
- [Create Model](./create-eloquent-model.md) - Creating Eloquent models with migrations, factories, and tests

### UI/UX Components
- [Add Sidebar Menu Item](./add-sidebar-menu-item.md) - Adding navigation items to the application sidebar
- [Create Livewire Page with Breadcrumbs](./create-livewire-page-with-breadcrumbs.md) - Creating Livewire pages with breadcrumb navigation

## SOP Format

Each SOP follows a standard format:
1. **Purpose** - Clear description of what the SOP covers
2. **Prerequisites** - Required knowledge or setup
3. **Step-by-Step Process** - Detailed instructions with code examples
4. **File Reference** - Quick lookup of relevant files
5. **Testing Checklist** - Verification steps
6. **Common Issues & Solutions** - Troubleshooting guide
7. **Best Practices** - Recommended approaches

## Contributing

When creating a new SOP:
1. Use the existing format for consistency
2. Include actual code examples from the project
3. Test the procedure before documenting
4. Update this README with the new SOP link
5. Update `/CLAUDE.md` with the SOP reference

## Quick Reference

| Task | SOP Document | Complexity |
|------|--------------|------------|
| Create new model | [create-eloquent-model.md](./create-eloquent-model.md) | Medium |
| Add sidebar menu item | [add-sidebar-menu-item.md](./add-sidebar-menu-item.md) | Low |
| Create page with breadcrumbs | [create-livewire-page-with-breadcrumbs.md](./create-livewire-page-with-breadcrumbs.md) | Medium |

## Maintenance

SOPs should be reviewed and updated when:
- The underlying technology changes
- Project structure is modified
- Better practices are discovered
- Common issues arise that aren't documented