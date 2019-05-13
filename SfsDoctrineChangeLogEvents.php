<?php

namespace Softspring\DoctrineChangeLogBundle;

class SfsDoctrineChangeLogEvents
{
    /**
     * @Event("Softspring\DoctrineChangeLogBundle\Event\InsertionChangeEvent")
     */
    const INSERTION = 'doctrine.changelog.insertion';

    /**
     * @Event("Softspring\DoctrineChangeLogBundle\Event\UpdateChangeEvent")
     */
    const UPDATE = 'doctrine.changelog.update';

    /**
     * @Event("Softspring\DoctrineChangeLogBundle\Event\DeletionChangeEvent")
     */
    const DELETION = 'doctrine.changelog.deletion';
}