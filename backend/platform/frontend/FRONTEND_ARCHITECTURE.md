# Trust AWAKEN Frontend Architecture v1.0

## Status

Architecture: LOCKED

No folders will be renamed after this document is approved.

---

# src

```
src/
│
├── api/
│   └── client.ts
│
├── assets/
│
├── components/
│   ├── common/
│   ├── dialogs/
│   ├── forms/
│   ├── layouts/
│   └── tables/
│
├── features/
│   ├── dashboard/
│   ├── programs/
│   ├── participants/
│   ├── sessions/
│   ├── attendance/
│   ├── credentials/
│   └── verification/
│
├── routes/
├── theme/
├── types/
│
├── App.tsx
├── main.tsx
└── index.css
```

---

# Feature Structure

Every feature follows the same layout.

Example:

```
features/programs/

api.ts
hooks.ts
mutations.ts
types.ts

ProgramsPage.tsx
ProgramDialog.tsx
ProgramForm.tsx
```

---

# Components

components/common

Reusable UI

Examples

AppToolbar

AppDialog

SearchBar

StatusChip

StatCard

QuickActionCard

AppToast

AppCard

---

components/layouts

DashboardLayout

AuthLayout

---

components/tables

DataTable

---

components/forms

Reusable forms

---

components/dialogs

Reusable dialogs

---

# Rules

No business logic inside components/common.

Business logic belongs inside features.

Only api/client.ts is global.

Everything else belongs inside a feature.

No duplicate components.

No duplicate hooks.

No duplicate APIs.

---

# Development Workflow

1.
Create backend endpoint.

2.
Create feature folder.

3.
Create API.

4.
Create hooks.

5.
Create mutations.

6.
Create page.

7.
Create dialog.

8.
Create form.

9.
Connect.

10.
Done.

---

Architecture Locked

Version 1.0