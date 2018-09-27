<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extension;

use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\InputhelpLogic;
use OxidEsales\EshopCommunity\Internal\Twig\Extensions\InputhelpExtension;

class TestGetSHelpText extends \OxidTestCase
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
            ['FIRST_NAME', 'First name', 1, false],
            ['FIRST_NAME', 'Vorname', 0, false],
            ['GENERAL_SAVE', 'Save', 1, true],
            ['VAT', 'VAT', 1, false],
            ['GENERAL_SAVE', 'Speichern', 0, true]
        );
    }

    /**
     * @param $sIndent
     * @param $sTranslation
     * @param $iLang
     * @param $blAdmin
     *
     * @dataProvider provider
     */
    public function testGetSHelpText($sIndent, $sTranslation, $iLang, $blAdmin)
    {
        $this->setLanguage($iLang);
        $this->setAdminMode($blAdmin);
        $this->assertEquals($sTranslation, $this->extension->getSHelpText($sIndent));
    }

}
