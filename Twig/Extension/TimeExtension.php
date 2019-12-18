<?php

namespace Knp\Bundle\TimeBundle\Twig\Extension;

use Knp\Bundle\TimeBundle\Templating\Helper\TimeHelper;
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
    protected $helper;

    public function __construct(TimeHelper $helper)
    {
        $this->helper = $helper;
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
        return $this->helper->diff($since, $to);
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
