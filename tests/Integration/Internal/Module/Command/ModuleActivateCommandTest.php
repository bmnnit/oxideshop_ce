<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Integration\Internal\Module\Command;

use OxidEsales\Eshop\Core\Module\Module;
use OxidEsales\Eshop\Core\Module\ModuleList;
use OxidEsales\EshopCommunity\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Module\Command\ModuleActivateCommand;
use OxidEsales\EshopCommunity\Tests\Integration\Internal\Console\ConsoleTrait;
use OxidEsales\EshopCommunity\Tests\Integration\Internal\ContainerTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Filesystem\Filesystem;

class ModuleActivateCommandTest extends TestCase
{
    use ContainerTrait;
    use ConsoleTrait;

    public function testModuleActivation()
    {
        $this->prepareTestData();

        $moduleId = 'testmodule';
        $consoleOutput = $this->execute(
            $this->getApplication(),
            $this->get('console.commands_collection_builder'),
            new ArrayInput(['command' => 'oe:module:activate', 'module-id' => $moduleId])
        );

        $this->assertSame(sprintf(ModuleActivateCommand::MESSAGE_MODULE_ACTIVATED, $moduleId) . PHP_EOL, $consoleOutput);

        $module = oxNew(Module::class);
        $module->load($moduleId);
        $this->assertTrue($module->isActive());

        $this->cleanupTestData();
    }

    /**
     * @depends testModuleActivation
     */
    public function testWhenModuleAlreadyActive()
    {
        $this->prepareTestData();

        $moduleId = 'testmodule';
        $consoleOutput = $this->execute(
            $this->getApplication(),
            $this->get('console.commands_collection_builder'),
            new ArrayInput(['command' => 'oe:module:activate', 'module-id' => $moduleId])
        );

        $this->assertSame(sprintf(ModuleActivateCommand::MESSAGE_MODULE_ALREADY_ACTIVE, $moduleId) . PHP_EOL, $consoleOutput);

        $this->cleanupTestData();
    }

    public function testNonExistingModuleActivation()
    {
        $moduleId = 'test';
        $consoleOutput = $this->execute(
            $this->getApplication(),
            $this->get('console.commands_collection_builder'),
            new ArrayInput(['command' => 'oe:module:activate', 'module-id' => $moduleId])
        );

        $this->assertSame(sprintf(ModuleActivateCommand::MESSAGE_MODULE_NOT_FOUND, $moduleId) . PHP_EOL, $consoleOutput);
    }

    protected function cleanupTestData()
    {
        $fileSystem = new Filesystem();
        $fileSystem->remove(Registry::getConfig()->getModulesDir() . '/testmodule');
        $moduleList = oxNew(ModuleList::class);
        $moduleList->cleanup();
    }

    protected function prepareTestData()
    {
        $fileSystem = new Filesystem();
        $fileSystem->mirror(__DIR__ . '/Fixtures', Registry::getConfig()->getConfigParam('sShopDir'));
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
