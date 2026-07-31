# Organization Business

## Responsibility

Manage organizations within the Trust AWAKEN Digital Trust Platform.

## Features

- Create Organization
- Update Organization
- Delete Organization
- Branding
- Logo
- Departments
- Billing
- Security Policies
- Audit

## Future

- Multi-campus
- White Label
- Custom Domains
- Organization Settings

# Organization Module

## Purpose

The Organization module is the root aggregate of the Trust AWAKEN Digital Trust Platform.

Every tenant, user, document, workshop, certificate, and verification belongs to an Organization.

---

## Responsibilities

The Organization module is responsible for:

- Organization lifecycle
- Organization identity
- Organization ownership
- Organization activation
- Organization suspension
- Organization archival

---

## This module DOES NOT manage

- Billing
- Branding
- Security
- Templates
- Documents
- Users
- Notifications

Those belong to their own modules.

---

## Dependencies

Depends on:

- Shared

Used by:

- Identity
- Billing
- Documents
- Verification
- Reporting
- Notifications

---

## Aggregate

Organization

Supporting Entities

- OrganizationBranding
- OrganizationSettings
- OrganizationDomains
- OrganizationSecurity
- OrganizationSubscription
- OrganizationContacts

---

## Development Order

1. Enums
2. Value Objects
3. Migration
4. Model
5. Repository
6. Actions
7. API
8. Tests

---

## Status

Sprint 3

In Development