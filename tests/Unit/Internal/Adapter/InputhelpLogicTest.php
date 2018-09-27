<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Adapter;

use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\InputhelpLogic;

class InputhelpLogicTest extends \OxidTestCase
{

    /**
     * @return array
     */
    public function provider()
    {
        return array(
            ['FIRST_NAME', 1, false, ['sIdent' => 'FIRST_NAME', 'sTranslation' => 'First name']],
            ['FIRST_NAME', 0, false, ['sIdent' => 'FIRST_NAME', 'sTranslation' => 'Vorname']],
            ['GENERAL_SAVE', 1, true, ['sIdent' => 'GENERAL_SAVE', 'sTranslation' => 'Save']],
            ['GENERAL_SAVE', 0, true, ['sIdent' => 'GENERAL_SAVE', 'sTranslation' => 'Speichern']],
            ['VAT', 1, false, ['sIdent' => 'VAT', 'sTranslation' => 'VAT']]
        );
    }

    /**
     * @param $sIndent
     * @param $iLang
     * @param $blAdmin
     * @dataProvider provider
     */
    public function testGetInputhelpParameters($sIndent, $iLang, $blAdmin, $expected)
    {
        $this->setLanguage($iLang);
        $this->setAdminMode($blAdmin);
        $inputhelpLogic = new InputhelpLogic();
        $this->assertEquals($expected, $inputhelpLogic->getInputhelpParameters(['ident' => $sIndent]));
    }

}
