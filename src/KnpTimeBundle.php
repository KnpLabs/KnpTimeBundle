<?php

namespace Knp\Bundle\TimeBundle;

use Knp\Bundle\TimeBundle\DependencyInjection\KnpTimeBundleExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class KnpTimeBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new KnpTimeBundleExtension();
        }

        return $this->extension ?: null;
    }
}
