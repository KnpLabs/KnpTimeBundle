<?php

namespace Knp\Bundle\TimeBundle\Twig\Extension;

use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
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
     *
     * @return array An array of global functions
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction(
                    'time_diff',
                    array($this, 'diff'),
                    array('is_safe' => array('html'))
                ),
        );
    }

    public function getFilters()
    {
        return array(
            new TwigFilter(
                    'ago',
                    array($this, 'diff'),
                    array('is_safe' => array('html'))
                ),
        );
    }

    public function diff($since = null, $to = null)
    {
        return $this->formatter->formatDiff(
            $this->formatter->getDatetimeObject($since),
            $this->formatter->getDatetimeObject($to)
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'time';
    }
}
