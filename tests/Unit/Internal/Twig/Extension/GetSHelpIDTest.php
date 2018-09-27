<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extension;

use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\InputhelpLogic;
use OxidEsales\EshopCommunity\Internal\Twig\Extensions\InputhelpExtension;

class GetSHelpIDTest extends \OxidTestCase
{

    /**
     * @var InputhelpExtension
     */
    protected $extension;

    protected function setUp()
    {
        parent::setUp();
        $oxinputhelpLogic = new InputhelpLogic();
        $this->extension = new InputhelpExtension($oxinputhelpLogic);
    }

    /**
     * @return array
     */
    public function provider()
    {
        return array(
            ['FIRST_NAME', 1, false],
            ['FIRST_NAME', 0, false],
            ['GENERAL_SAVE', 1, true],
            ['VAT', 1, false],
            ['GENERAL_SAVE', 0, true]
        );
    }

    /**
     * @param $sIndent
     * @param $iLang
     * @param $blAdmin
     * @dataProvider provider
     */
    public function testGetSHelpId($sIndent, $iLang, $blAdmin)
    {
        $this->setLanguage($iLang);
        $this->setAdminMode($blAdmin);
        $this->assertEquals($sIndent, $this->extension->getSHelpId($sIndent));
    }

}
