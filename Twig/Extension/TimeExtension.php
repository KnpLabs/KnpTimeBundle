<?php

namespace Knp\Bundle\TimeBundle\Twig\Extension;

use Knp\Bundle\TimeBundle\Templating\Helper\TimeHelper;

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
            new \Twig_SimpleFunction(
                    'time_diff', 
                    array($this, 'diff'), 
                    array('is_safe' => array('html'))
                ),
        );
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                    'ago', 
                    array($this, 'diff'), 
                    array('is_safe' => array('html'))
                ),
            new \Twig_SimpleFilter(
                'diffmax',
                array($this, 'diffmax'),
                array('is_safe' => array('html'))
            ),
        );
    }
    
    /**
     * @param $date \DateTime first argument, the value we want to display
     * @param null $maxDiff
     * @param string $dateFormat
     * @param string $maxDiffUnit
     *
     * @return \DateTime
     */
    public function diffmax($date, $maxDiff = null, $dateFormat = 'd/m/Y', $maxDiffUnit = 'day')
    {
        $this->helper->setMaxDiff($maxDiff);
        $this->helper->setMaxDiffUnit($maxDiffUnit);
        $this->helper->setDateFormat($dateFormat);
        return $date;
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
