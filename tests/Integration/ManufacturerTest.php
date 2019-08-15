<?php

namespace ConnectorIntegrationTests\Integration;

use ConnectorIntegrationTests\ConnectorTestCase;
use jtl\Connector\Model\Identity;
use jtl\Connector\Model\Manufacturer;
use jtl\Connector\Model\ManufacturerI18n;

class ManufacturerTest extends ConnectorTestCase
{
    public function testManufacturerBasicPush()
    {
        $manufacturer = new Manufacturer();
        $manufacturer->setId(new Identity('', 1));
        $manufacturer->setName('Test');
        $manufacturer->setSort(0);
        $manufacturer->setUrlPath('');
        $manufacturer->setWebsiteUrl('');
        
        $this->pushCoreModels([$manufacturer], true);
    }
    
    public function testManufacturerI18nPush()
    {
        $manufacturer = new Manufacturer();
        $manufacturer->setName('Test');
            $i18n = new ManufacturerI18n();
            $i18n->setManufacturerId(new Identity('', 1));
            $i18n->setDescription('');
            $i18n->setLanguageISO('ger');
            $i18n->setMetaDescription('');
            $i18n->setMetaKeywords('');
            $i18n->setTitleTag('');
        $manufacturer->addI18n($i18n);
        
        $this->pushCoreModels([$manufacturer], true);
    }
    
    public function getIgnoreArray()
    {
        // TODO: Implement getIgnoreArray() method.
    }
}
