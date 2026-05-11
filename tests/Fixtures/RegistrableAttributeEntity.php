<?php

declare(strict_types=1);

namespace Softspring\DoctrineChangeLogBundle\Tests\Fixtures;

use Softspring\DoctrineChangeLogBundle\Mapping as ChangeLog;

#[ChangeLog\Registrable([])]
class RegistrableAttributeEntity
{
    #[ChangeLog\Ignored([])]
    protected string $ignored = '';

    protected string $tracked = '';
}
