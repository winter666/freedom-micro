<?php


namespace Freedom\Modules\Command;


use Freedom\Modules\Command\Defaults\MigrationUpCommand;

abstract class CommandRegister
{
    private const COMMAND_MIGRATION = 'migrate';
    private const DEFAULT_COMMANDS = [
        self::COMMAND_MIGRATION => MigrationUpCommand::class,
    ];

    private array $handlers = [];

    protected function register(string $name, string $class) {
        $this->handlers[$name] = $class;
    }

    public function getRegistered(): array {
        return array_merge($this->handlers, self::DEFAULT_COMMANDS);
    }
}
