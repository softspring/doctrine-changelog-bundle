<?php

namespace Softspring\DoctrineChangeLogBundle\Mapping;

use Attribute;
use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 *
 * @Target("PROPERTY")
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
/*final*/ class Ignored extends Annotation
{
}
