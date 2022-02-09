<?php

namespace Softspring\DoctrineChangeLogBundle;

class SfsDoctrineChangeLogEvents
{
    /**
     * @Event("Softspring\DoctrineChangeLogBundle\Event\InsertionChangeEvent")
     */
    public const INSERTION = 'doctrine.changelog.insertion';

    /**
     * @Event("Softspring\DoctrineChangeLogBundle\Event\UpdateChangeEvent")
     */
    public const UPDATE = 'doctrine.changelog.update';

    /**
     * @Event("Softspring\DoctrineChangeLogBundle\Event\DeletionChangeEvent")
     */
    public const DELETION = 'doctrine.changelog.deletion';
}
