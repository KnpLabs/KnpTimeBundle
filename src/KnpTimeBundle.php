<?php

namespace Knp\Bundle\TimeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

final class KnpTimeBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
