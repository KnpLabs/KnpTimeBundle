<?php

namespace Knp\Bundle\TimeBundle\Twig\Extension;

use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * @author Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class TimeExtension extends AbstractExtension
{
    protected $formatter;

    public function __construct(DateTimeFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Returns a list of global functions to add to the existing list.
     */
    public function getFunctions(): array
    {
        return array(
            new TwigFunction(
                    'time_diff',
                    array($this, 'diff'),
                    array('is_safe' => array('html'))
                ),
        );
    }

    public function getFilters(): array
    {
        return array(
            new TwigFilter(
                    'ago',
                    array($this, 'diff'),
                    array('is_safe' => array('html'))
                ),
        );
    }

    public function diff($since = null, $to = null, $locale = null): string
    {
        return $this->formatter->formatDiff(
            $this->formatter->getDatetimeObject($since),
            $this->formatter->getDatetimeObject($to),
            $locale
        );
    }

    /**
     * Returns the name of the extension.
     */
    public function getName(): string
    {
        return 'time';
    }
}
