<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Integration\Internal\Application;

use OxidEsales\EshopCommunity\Internal\Application\ContainerBuilder;
use OxidEsales\EshopCommunity\Internal\Console\ExecutorInterface;
use OxidEsales\Facts\Facts;
use PHPUnit\Framework\TestCase;

class ContainerBuilderTest extends TestCase
{
    public function testWhenPeOverwritesMainServices()
    {
        $facts = $this->getMockBuilder(Facts::class)->getMock();
        $facts->method('getProfessionalEditionRootPath')->willReturn(__DIR__ . '/Fixtures/PE');
        $facts->method('isProfessional')->willReturn(true);
        $facts->method('isEnterprise')->willReturn(false);
        $containerBuilder = new ContainerBuilder($facts);

        $executor = $this->getExecutor($containerBuilder);

        $this->assertSame('Service overwriting for PE!', $executor->execute());
    }

    public function testWhenEeOverwritesMainServices()
    {
        $facts = $this->getMockBuilder(Facts::class)->getMock();
        $facts->method('getProfessionalEditionRootPath')->willReturn(__DIR__ . '/Fixtures/PE');
        $facts->method('getEnterpriseEditionRootPath')->willReturn(__DIR__ . '/Fixtures/EE');
        $facts->method('isProfessional')->willReturn(false);
        $facts->method('isEnterprise')->willReturn(true);
        $containerBuilder = new ContainerBuilder($facts);

        $executor = $this->getExecutor($containerBuilder);

        $this->assertSame('Service overwriting for EE!', $executor->execute());
    }

    public function testWhenProjectOverwritesMainServices()
    {
        $facts = $this->getMockBuilder(Facts::class)->getMock();
        $facts->method('isProfessional')->willReturn(false);
        $facts->method('isEnterprise')->willReturn(false);
        $facts->method('getSourcePath')->willReturn(__DIR__ . '/Fixtures/Project');
        $containerBuilder = new ContainerBuilder($facts);

        $executor = $this->getExecutor($containerBuilder);

        $this->assertSame('Service overwriting for Project!', $executor->execute());
    }

    public function testWhenProjectOverwritesEditions()
    {
        $facts = $this->getMockBuilder(Facts::class)->getMock();
        $facts->method('getProfessionalEditionRootPath')->willReturn(__DIR__ . '/Fixtures/PE');
        $facts->method('getEnterpriseEditionRootPath')->willReturn(__DIR__ . '/Fixtures/EE');
        $facts->method('isEnterprise')->willReturn(true);
        $facts->method('getSourcePath')->willReturn(__DIR__ . '/Fixtures/Project');
        $containerBuilder = new ContainerBuilder($facts);

        $executor = $this->getExecutor($containerBuilder);

        $this->assertSame('Service overwriting for Project!', $executor->execute());
    }

    /**
     * @param ContainerBuilder $containerBuilder
     *
     * @return ExecutorInterface
     */
    protected function getExecutor(ContainerBuilder $containerBuilder)
    {
        $container = $containerBuilder->getContainer();
        $container->compile();
        $executor = $container->get(ExecutorInterface::class);
        return $executor;
    }
}
