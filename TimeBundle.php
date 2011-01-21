<?php

namespace Bundle\TimeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle as BaseBundle;

class TimeBundle extends BaseBundle
{
    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    public function getPath()
    {
        return __DIR__;
    }
}
