<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Console;

use Doctrine\Common\Collections\ArrayCollection;
use OxidEsales\EshopCommunity\Internal\Console\CommandsProvider\CommandsProvidableInterface;

/**
 * Builds commands collection.
 * @internal
 */
class CommandsCollectionBuilder
{
    /**
     * @var array
     */
    private $commandsProviders;

    /**
     * @param CommandsProvidableInterface ...$commandsProviders
     */
    public function __construct(CommandsProvidableInterface ...$commandsProviders)
    {
        $this->commandsProviders = $commandsProviders;
    }

    /**
     * @return ArrayCollection
     */
    public function build()
    {
        $collection = new ArrayCollection();
        array_map(function ($commandsProvider) use ($collection) {
            /** @var CommandsProvidableInterface $commandsProvider */
            foreach ($commandsProvider->getCommands() as $command) {
                $collection->add($command);
            }
        }, $this->commandsProviders);

        return $collection;
    }
}
