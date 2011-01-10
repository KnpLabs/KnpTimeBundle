<?php

namespace Bundle\TimeBundle\Twig\Extension;

use Bundle\TimeBundle\Templating\Helper\TimeHelper;
use DateTime;

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
class TimeExtension extends \Twig_Extension
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
            'ago'  => new \Twig_Function_Method($this, 'ago')
        );
    }

    public function ago(DateTime $since = null, DateTime $to = null)
    {
        return $this->helper->ago($since, $to);
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
