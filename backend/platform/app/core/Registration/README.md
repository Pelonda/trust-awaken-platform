# Registration Module

## Purpose

The Registration module is responsible for onboarding new organizations into the Trust AWAKEN platform.

It orchestrates multiple bounded contexts without owning their data.

## Modules Used

- Identity
- Organization

## Responsibilities

- Register owner account
- Register organization
- Link owner to organization
- Assign Owner role
- Authenticate owner
- Dispatch registration events

## Transaction

The entire registration workflow executes inside one database transaction.

If any step fails:

- User creation is rolled back
- Organization creation is rolled back
- No partial data remains

## Output

A fully initialized organization owner ready to use the platform.

## Architecture

Presentation

↓

Registration Action

↓

Registration Service

↓

Identity Repository

↓

Organization Repository

↓

Database Transaction

↓

Events