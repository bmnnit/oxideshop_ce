<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Integration\Internal\Application\Fixtures\Project;

use OxidEsales\EshopCommunity\Internal\Console\ExecutorInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DummyExecutor implements ExecutorInterface
{
    public function execute(InputInterface $input = null, OutputInterface $output = null)
    {
        return 'Service overwriting for Project!';
    }
}
