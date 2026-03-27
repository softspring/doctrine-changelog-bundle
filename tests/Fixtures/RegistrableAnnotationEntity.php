<?php

namespace Softspring\DoctrineChangeLogBundle\Tests\Fixtures;

use Softspring\DoctrineChangeLogBundle\Mapping as ChangeLog;

/**
 * @ChangeLog\Registrable()
 */
class RegistrableAnnotationEntity
{
    /**
     * @ChangeLog\Ignored()
     */
    protected string $ignored = '';

    protected string $tracked = '';
}
