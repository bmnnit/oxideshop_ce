<?php

namespace OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic;

class InputhelpLogic
{

    public function getInputhelpParameters($params)
    {
        $sIdent = $params['ident'];
        $myConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
        $blAdmin = $myConfig->isAdmin();
        $oLang = \OxidEsales\Eshop\Core\Registry::getLang();
        $iLang = $oLang->getTplLanguage();

        try {
            $sTranslation = $oLang->translateString($sIdent, $iLang, $blAdmin);
        } catch(\OxidEsales\Eshop\Core\Exception\LanguageException $oEx) {
            // is thrown in debug mode and has to be caught here, as smarty hangs otherwise!
        }

        $return = ['sIdent' => $sIdent, 'sTranslation' => $sTranslation];
        return $return;
    }

}