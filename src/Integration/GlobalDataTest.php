<?php

namespace Jtl\Connector\IntegrationTests\Integration;

use Jtl\Connector\IntegrationTests\ConnectorTestCase;
use jtl\Connector\Model\GlobalData;

abstract class GlobalDataTest extends ConnectorTestCase
{
    public function testShopHasExactlyOneDefaultCurrency()
    {
        /** @var GlobalData $globalData */
        $globalData = $this->pullCoreModels('GlobalData', 1) [0];
        
        $defaultCurrencies = 0;
        
        foreach ($globalData->getCurrencies() as $currency) {
            if ($currency->getIsDefault()) {
                $defaultCurrencies++;
            }
        }
        
        $this->assertEquals(1, $defaultCurrencies);
    }
    
    public function testShopHasExactlyOneDefaultLanguage()
    {
        /** @var GlobalData $globalData */
        $globalData = $this->pullCoreModels('GlobalData', 1) [0];
        
        $defaultLanguages = 0;
        
        foreach ($globalData->getLanguages() as $language) {
            if ($language->getIsDefault()) {
                $defaultLanguages++;
            }
        }
        
        $this->assertEquals(1, $defaultLanguages);
    }
    
    public function testShopHasAtLeastOneShippingMethod()
    {
        /** @var GlobalData $globalData */
        $globalData = $this->pullCoreModels('GlobalData', 1) [0];
        
        $this->assertNotEmpty($globalData->getShippingMethods());
    }
    
    public function testShopHasAtLeastOneTaxRate()
    {
        /** @var GlobalData $globalData */
        $globalData = $this->pullCoreModels('GlobalData', 1) [0];
        
        $this->assertNotEmpty($globalData->getTaxRates());
    }
}
