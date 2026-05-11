<?php

declare(strict_types=1);

namespace Softspring\DoctrineChangeLogBundle\Mapping;

use Attribute;

/**
 * @Annotation
 *
 * @Target("PROPERTY")
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
/* final */ class Ignored
{
    public function __construct(
        public readonly array $values = [],
    ) {
    }
}
