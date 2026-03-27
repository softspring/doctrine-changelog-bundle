<?php

namespace Softspring\DoctrineChangeLogBundle\Mapping;

use Attribute;

/**
 * @Annotation
 *
 * @Target("CLASS")
 */
#[Attribute(Attribute::TARGET_CLASS)]
/* final */ class Registrable
{
    public function __construct(
        public readonly array $values = [],
    ) {
    }
}
