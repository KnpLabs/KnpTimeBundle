<?php

namespace Knp\Bundle\TimeBundle\Twig\Extension;

use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * @author Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @internal
 */
final class TimeExtension extends AbstractExtension
{
    /**
     * Returns a list of global functions to add to the existing list.
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'time_diff',
                [DateTimeFormatter::class, 'formatDiff'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'age',
                [DateTimeFormatter::class, 'formatAge'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'ago',
                [DateTimeFormatter::class, 'formatDiff'],
                ['is_safe' => ['html']]
            ),
            new TwigFilter(
                'time_diff',
                [DateTimeFormatter::class, 'formatDiff'],
                ['is_safe' => ['html']]
            ),
            new TwigFilter(
                'duration',
                [DateTimeFormatter::class, 'formatDuration'],
                ['is_safe' => ['html']]
            ),
            new TwigFilter(
                'age',
                [DateTimeFormatter::class, 'formatAge'],
                ['is_safe' => ['html']]
            ),
        ];
    }
}
