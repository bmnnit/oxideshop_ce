<?php
/**
 * Created by PhpStorm.
 * User: jskoczek
 * Date: 13/09/18
 * Time: 16:55
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extesions;

use OxidEsales\EshopCommunity\Internal\Twig\Extensions\OxidExtension;
use OxidEsales\EshopCommunity\Core\Registry;

class OxidExtensionTest extends \PHPUnit\Framework\TestCase
{

    private function getMethod(OxidExtension $oxidExtension, $name) {
        $class = new \ReflectionClass($oxidExtension);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    /**
     * @param $params
     * @param $expected
     *
     * @dataProvider getOxpriceProvider
     */
    public function testOxprice($params, $expected)
    {
        $config = Registry::getConfig();
        $oxidExtension = new OxidExtension($config);
        $oxprice = $this->getMethod($oxidExtension, 'oxprice');
        $price = $oxprice->invokeArgs($oxidExtension, [$params]);
        $this->assertEquals($expected, $price);
    }

    public function getOxpriceProvider()
    {
        return [
            [
                ['price' => 100],
                '100,00 €'
            ],
            [
                ['price' => null],
                ''
            ]
        ];
    }

    /**
     * @param $inputPrice
     * @param $params
     *
     * @dataProvider getCalculateOxPriceProvider
     */
    public function testCalculateOxPrice($inputPrice, $params, $expected)
    {
        $config = Registry::getConfig();
        $oxidExtension = new OxidExtension($config);
        $calculateOxPrice = $this->getMethod($oxidExtension, 'calculateOxPrice');
        $calculatedOxPrice = $calculateOxPrice->invokeArgs($oxidExtension, [$inputPrice, $params]);
        $this->assertEquals($expected, $calculatedOxPrice);
    }

    /**
     * @return array
     */
    public function getCalculateOxPriceProvider()
    {
        $incorrectPriceObj = new \OxidEsales\Eshop\Core\Price();
        $incorrectPriceObj->setPrice(false);
        $correctPriceObj = new \OxidEsales\Eshop\Core\Price();
        $correctPriceObj->setPrice(120);
        return [
            [
                1, null, '1,00 €'
            ],
            [
                'incorrect', null, '0,00 €'
            ],
            [
                $incorrectPriceObj, null, '0,00 €'
            ],
            [
                $incorrectPriceObj, null, '0,00 €'
            ],
            [
                $correctPriceObj, null, '120,00 €'
            ]
        ];
    }

    /**
     * @param $currency
     * @param $price
     * @param $expected
     *
     * @dataProvider getFormattedOxPriceProvider
     */
    public function testGetFormattedOxPrice($currency, $price, $expected)
    {
        $config = Registry::getConfig();
        $oxidExtension = new OxidExtension($config);
        $getFormattedOxPrice = $this->getMethod($oxidExtension,'getFormattedOxPrice');
        $formattedPrice = $getFormattedOxPrice->invokeArgs($oxidExtension, [$currency, $price]);
        $this->assertEquals($expected, $formattedPrice);
    }

    /**
     * @return array
     */
    public function getFormattedOxPriceProvider()
    {
        $price = 10000;
        return [
            [
                '', $price, '10.000,00'
            ],
            [
                '', -100, ''
            ],
            [
                $this->getCurrencyWithSepparator(['dec' => '-']), $price, '10.000-00'
            ],
            [
                $this->getCurrencyWithSepparator(['thousand' => '-']), $price, '10-000,00'
            ],
            [
                $this->getCurrencyWithSepparator(['sign' => '$']), $price, '10.000,00 $'
            ],
            [
                $this->getCurrencyWithSepparator(['decimal' => 4]), $price, '10.000,0000'
            ],
            [
                $this->getCurrencyWithSepparator(['sign' => '$', 'side' => 'Front']), $price, '$10.000,00'
            ],
            [
                $this->getCurrencyWithSepparator(['sign' => '$', 'side' => 'incorrect']), $price, '10.000,00 $'
            ]
        ];
    }

    /**
     * @param $currency_array
     * @return \stdClass
     */
    private function getCurrencyWithSepparator($currency_array)
    {
        $currency = new \stdClass();

        foreach($currency_array as $key => $value) {
            $currency->$key = $value;
        }
        return $currency;
    }

}
