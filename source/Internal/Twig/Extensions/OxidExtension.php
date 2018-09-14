<?php

namespace OxidEsales\EshopCommunity\Internal\Twig\Extensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class OxidExtension
 */
class OxidExtension extends AbstractExtension
{

    /** @var \OxidEsales\Eshop\Core\Config */
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('oxprice', [$this, 'oxprice'])
        ];
    }

    public function oxprice($params)
    {
        $output = '';
        $inputPrice = $params['price'];
        if(!is_null($inputPrice)) {
            $output = $this->calculateOxPrice($inputPrice, $params);
        }
        return $output;
    }

    private function calculateOxPrice($inputPrice, $params)
    {
        $price = ($inputPrice instanceof \OxidEsales\Eshop\Core\Price) ? $inputPrice->getPrice() : floatval($inputPrice);
        $currency = isset($params['currency']) ? $params['currency'] : $this->config->getActShopCurrencyObject();
        $output = '';

        if(is_numeric($price)) {
            $output = $this->getFormattedOxPrice($currency, $price);
        }

        return $output;
    }

    private function getFormattedOxPrice($currency, $price)
    {
        $output = '';
        $decimalSeparator = isset($currency->dec) ? $currency->dec : ',';
        $thousandsSeparator = isset($currency->thousand) ? $currency->thousand : '.';
        $currencySymbol = isset($currency->sign) ? $currency->sign : '';
        $currencySymbolLocation = isset($currency->side) ? $currency->side : '';
        $decimals = isset($currency->decimal) ? (int)$currency->decimal : 2;

        if((float)$price > 0 || $currencySymbol) {
            $price = number_format($price, $decimals, $decimalSeparator, $thousandsSeparator);
            $output = (isset($currencySymbolLocation) && $currencySymbolLocation == 'Front') ? $currencySymbol . $price : $price . ' ' . $currencySymbol;
        }

        $output = trim($output);

        return $output;
    }
}