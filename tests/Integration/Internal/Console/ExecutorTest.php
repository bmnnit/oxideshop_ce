<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Integration\Internal\Console;

use OxidEsales\EshopCommunity\Internal\Console\CommandsCollectionBuilder;
use OxidEsales\EshopCommunity\Internal\Console\CommandsProvider\CommandsProvidableInterface;
use OxidEsales\EshopCommunity\Tests\Integration\Internal\Console\Fixtures\TestCommand;
use OxidEsales\EshopCommunity\Tests\Integration\Internal\ContainerTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class ExecutorTest extends TestCase
{
    use ConsoleTrait;
    use ContainerTrait;

    public function testIfRegisteredCommandInList()
    {
        $commands = $this->getMockBuilder(CommandsProvidableInterface::class)->getMock();
        $commands->method('getCommands')->willReturn([new TestCommand()]);
        $commandsCollectionBuilder = new CommandsCollectionBuilder($commands);
        $consoleOutput = $this->execute(
            $this->getApplication(),
            $commandsCollectionBuilder,
            new ArrayInput(['command' => 'list'])
        );

        $this->assertRegexp('/oe:tests:test-command/', $consoleOutput);
    }

    public function testCommandExecution()
    {
        $commands = $this->getMockBuilder(CommandsProvidableInterface::class)->getMock();
        $commands->method('getCommands')->willReturn([new TestCommand()]);
        $commandsCollectionBuilder = new CommandsCollectionBuilder($commands);
        $consoleOutput = $this->execute(
            $this->getApplication(),
            $commandsCollectionBuilder,
            new ArrayInput(['command' => 'oe:tests:test-command'])
        );

        $this->assertSame('Command have been executed!'.PHP_EOL, $consoleOutput);
    }

    /**
     * @return Application
     */
    private function getApplication(): Application
    {
        $application = $this->get('symfony.component.console.application');
        $application->setAutoExit(false);

        return $application;
    }
}
