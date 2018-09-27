<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopCommunity\Internal\Twig\Extensions;

use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\InputhelpLogic;
use OxidEsales\EshopCommunity\Internal\Twig\TwigEngine;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class OxidExtension
 */
class InputhelpExtension extends AbstractExtension
{

    /** @var TwigEngine */
    private $inputhelpLogic;

    public function __construct(InputhelpLogic $inputhelpLogic)
    {
        $this->inputhelpLogic = $inputhelpLogic;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('getSHelpId', [$this, 'getSHelpId']),
            new TwigFunction('getSHelpText', [$this, 'getSHelpText'])
        ];
    }

    public function getSHelpId($sIdent)
    {
        $getInputhelpParameters = $this->inputhelpLogic->getInputhelpParameters(['ident' => $sIdent]);
        return $getInputhelpParameters['sIdent'];
    }

    public function getSHelpText($sIdent)
    {
        $getInputhelpParameters = $this->inputhelpLogic->getInputhelpParameters(['ident' => $sIdent]);
        return $getInputhelpParameters['sTranslation'];
    }

}