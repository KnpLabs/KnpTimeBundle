<?php

namespace Knplabs\TimeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle as BaseBundle;

class KnplabsTimeBundle extends BaseBundle
{
    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    public function getPath()
    {
        return strtr(__DIR__, '\\', '/');
    }
}
