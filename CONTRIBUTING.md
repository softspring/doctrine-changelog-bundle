# Contributing

Use the standard package commands before sending changes:

```bash
composer fix
composer test
composer test-bc
```

Keep the existing `EventSubscriberInterface` contract when maintaining event subscribers.
Their public `getSubscribedEvents()` method is used by service tags and application integrations.
Rector excludes `EventSubscriberToAttributeRector` to preserve this contract; other Symfony rules remain enabled.

[Report issues](https://github.com/softspring/doctrine-changelog-bundle/issues) and [send Pull Requests](https://github.com/softspring/doctrine-changelog-bundle/pulls)
