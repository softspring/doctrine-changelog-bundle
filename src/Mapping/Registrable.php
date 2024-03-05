<?php

namespace Softspring\DoctrineChangeLogBundle\Mapping;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 *
 * @Target("CLASS")
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
/* final */ class Registrable extends Annotation
{
}
