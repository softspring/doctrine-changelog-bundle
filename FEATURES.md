# Doctrine Changelog Component Features

Functional definition for `softspring/doctrine-changelog-bundle`.

## Purpose

- Capture Doctrine entity insertions, updates, and deletions during `flush()`.
- Enrich those changes with request, user, and action metadata.
- Persist or forward the collected changelog entries through pluggable storage drivers.

## Main Features

- Observes Doctrine Unit of Work changes and emits one changelog event per entity insertion, update, or deletion.
- Tracks only entities marked as registrable and lets specific fields be ignored.
- Provides an in-memory `ChangesStack` that can be consumed by the bundled persistence listener or by application-specific listeners.
- Supports metadata collectors for HTTP request, authenticated user, and action name.
- Ships storage drivers for Doctrine persistence and Google BigQuery.

## Integration And Extension

- Can work as a full changelog persistence layer or only as an internal event source.
- Supports custom storage drivers implementing `StorageDriverInterface`.
- Supports custom collectors, subscribers, and listeners around the change events.
- Supports both PHP attributes and Doctrine annotations for registrable and ignored mappings in this line.

## Expected Capabilities

- Must work with the supported dependency matrix of this line, including Symfony `6.4`, `7.x`, and `8.x`.
- Must keep both regular and lowest dependency validation workflows working (`composer test` and `composer test-bc`).
- Must keep changelog event generation, collector wiring, and storage driver behavior stable across minor releases in the same line.
