<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Cli;

use DevNet\Cli\Parsing\CommandParser;
use DevNet\System\Event\EventHandler;
use DevNet\System\Event\EventArgs;

class CommandLine
{
    private string $Name;
    private string $Description;
    private array $Arguments = [];
    private array $Options = [];
    private EventHandler $Event;

    public function __construct()
    {
        $this->Event = new EventHandler();
    }

    public function setName(string $name)
    {
        $this->Name = $name;
    }

    public function setDescription(string $description)
    {
        $this->Description = $description;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function getDescription(): string
    {
        return $this->Description;
    }

    public function addArgument(string $name)
    {
        $this->Arguments[] = $name;
    }

    public function addOption(string $name)
    {
        $this->Options[] = $name;
    }

    public function getArguments(): array
    {
        return $this->Arguments;
    }

    public function getOptions(): array
    {
        return $this->Options;
    }

    public function onExecute(object $handler, ?string $action = null)
    {
        $this->Event->add($handler, $action);
    }

    public function Execute(array $args)
    {
        $parser = new CommandParser();

        foreach ($this->Arguments as $argument) {
            $parser->addArgument($argument);
        }

        foreach ($this->Options as $option) {
            $parser->addOption($option);
        }

        $parameters = $parser->parse($args);
        $eventArgs = new CommandEventArgs($parameters, $args);

        $this->Event->__invoke($this, $eventArgs);
    }
}
