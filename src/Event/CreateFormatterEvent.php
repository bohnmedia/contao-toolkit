<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Contao\Toolkit\Event;

use Netzmacht\Contao\Toolkit\Dca\Definition;
use Netzmacht\Contao\Toolkit\Dca\Formatter\Value\ValueFormatter;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CreateFormatterEvent.
 *
 * @package Netzmacht\Contao\Toolkit\Event
 */
class CreateFormatterEvent extends Event
{
    const NAME = 'toolkit.dca.create-formatter';

    /**
     * Data container definition.
     *
     * @var Definition
     */
    private $definition;

    /**
     * Created formatters.
     *
     * @var ValueFormatter[]
     */
    private $formatters = array();

    /**
     * CreateFormatterEvent constructor.
     *
     * @param Definition $definition Data container definition.
     */
    public function __construct(Definition $definition)
    {
        $this->definition = $definition;
    }

    /**
     * Get definition.
     *
     * @return Definition
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Add a formatter.
     *
     * @param ValueFormatter $formatter Formatter.
     *
     * @return $this
     */
    public function addFormatter(ValueFormatter $formatter)
    {
        $this->formatters[] = $formatter;

        return $this;
    }

    /**
     * Add a set of formatters.
     *
     * @param ValueFormatter[]|array $formatters Set of formatters.
     *
     * @return $this
     */
    public function addFormatters(array $formatters)
    {
        foreach ($formatters as $formatter) {
            $this->addFormatter($formatter);
        }

        return $this;
    }

    /**
     * Get formatters.
     *
     * @return ValueFormatter[]
     */
    public function getFormatters()
    {
        return $this->formatters;
    }
}
