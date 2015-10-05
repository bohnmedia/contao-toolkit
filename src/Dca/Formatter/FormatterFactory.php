<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Contao\Toolkit\Dca\Formatter;

use Netzmacht\Contao\Toolkit\Dca\Definition;
use Netzmacht\Contao\Toolkit\Dca\Formatter\Value\FilterFormatter;
use Netzmacht\Contao\Toolkit\Dca\Formatter\Value\FormatterChain;
use Netzmacht\Contao\Toolkit\Dca\Formatter\Value\ValueFormatter;
use Netzmacht\Contao\Toolkit\Event\CreateFormatterEvent;
use Netzmacht\Contao\Toolkit\ServiceContainer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * FormatterFactory creates the formatters for the data container definitions.
 *
 * @package Netzmacht\Contao\Toolkit\Dca\Formatter
 */
class FormatterFactory
{
    /**
     * Event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Service container.
     *
     * @var ServiceContainer
     */
    private $serviceContainer;

    /**
     * FormatterFactory constructor.
     *
     * @param ServiceContainer         $serviceContainer Event dispatcher.
     * @param EventDispatcherInterface $eventDispatcher  Service container.
     */
    public function __construct(ServiceContainer $serviceContainer, EventDispatcherInterface $eventDispatcher)
    {
        $this->serviceContainer = $serviceContainer;
        $this->eventDispatcher  = $eventDispatcher;
    }

    /**
     * Get the default formatter.
     *
     * @return ValueFormatter
     */
    public function getDefaultValueFormatter()
    {
        return $this->serviceContainer->getService('toolkit.dca-formatter.default');
    }

    /**
     * Create a formatter for a definition.
     *
     * @param Definition $definition Data container definition.
     *
     * @return ValueFormatter
     */
    public function createFormatterFor(Definition $definition)
    {
        if ($this->serviceContainer->hasService('toolkit.dca-formatter.' . $definition->getName())) {
            return $this->serviceContainer->getService('toolkit.dca-formatter.' . $definition->getName());
        }

        $event = new CreateFormatterEvent($definition);
        $this->eventDispatcher->dispatch($event::NAME, $event);

        $preFilter  = $this->serviceContainer->getService('toolkit.dca-formatter.pre-filter');
        $postFilter = $this->serviceContainer->getService('toolkit.dca-formatter.post-filter');
        $formatter  = $this->getDefaultValueFormatter();

        if ($event->getFormatters()) {
            $local     = new FormatterChain($event->getFormatters());
            $formatter = new FormatterChain([$local, $formatter]);
        }

        $chain = new FilterFormatter([$preFilter, $formatter, $postFilter]);

        return new Formatter($definition, $chain);
    }
}