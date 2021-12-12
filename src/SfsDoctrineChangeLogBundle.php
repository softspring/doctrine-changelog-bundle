<?php

namespace Softspring\DoctrineChangeLogBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SfsDoctrineChangeLogBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}