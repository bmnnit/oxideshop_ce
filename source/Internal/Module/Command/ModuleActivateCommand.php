<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Module\Command;

use OxidEsales\Eshop\Core\Module\Module;
use OxidEsales\Eshop\Core\Module\ModuleInstaller;
use OxidEsales\Eshop\Core\Module\ModuleList;
use OxidEsales\Eshop\Core\Registry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 */
class ModuleActivateCommand extends Command
{
    const MESSAGE_MODULE_ALREADY_ACTIVE = 'Module - "%s" already active.';

    const MESSAGE_MODULE_ACTIVATED = 'Module - "%s" was activated.';

    const MESSAGE_MODULE_NOT_FOUND = 'Module - "%s" not found.';

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('oe:module:activate')
            ->setDescription('Activates a module')
            ->addArgument('module-id', InputArgument::REQUIRED, 'Module ID')
            ->setHelp('Command activates module by defined module ID.');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $moduleId = $input->getArgument('module-id');
        /** @var ModuleInstaller $moduleInstaller */
        $moduleInstaller = Registry::get(ModuleInstaller::class);
        $moduleList = oxNew(ModuleList::class);
        $moduleList->getModulesFromDir(Registry::getConfig()->getModulesDir());
        $modules = $moduleList->getList();
        /** @var Module $module */
        if (isset($modules[$moduleId])) {
            $module = $modules[$moduleId];
            if ($module->isActive()) {
                $output->writeLn('<info>'.sprintf(static::MESSAGE_MODULE_ALREADY_ACTIVE, $moduleId).'</info>');
            } else {
                $moduleInstaller->activate($module);
                $output->writeLn('<info>'.sprintf(static::MESSAGE_MODULE_ACTIVATED, $moduleId).'</info>');
            }
        } else {
            $output->writeLn('<error>'.sprintf(static::MESSAGE_MODULE_NOT_FOUND, $moduleId).'</error>');
        }
    }
}
