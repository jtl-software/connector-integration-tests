<?php
namespace Jtl\Connector\IntegrationTests\Integration;

use jtl\Connector\Exception\LinkerException;
use Jtl\Connector\IntegrationTests\ConnectorTestCase;
use jtl\Connector\Model\Identity;
use jtl\Connector\Model\Manufacturer;
use jtl\Connector\Model\ManufacturerI18n;

abstract class ManufacturerTest extends ConnectorTestCase
{
    /**
     * @param Manufacturer $manufacturer
     * @throws LinkerException
     * @throws \ReflectionException
     */
    protected function push(Manufacturer $manufacturer)
    {
        $manufacturer->setId(new Identity('', $this->hostId));
        $endpointId = $this->pushCoreModels([$manufacturer], true)[0]->getId()->getEndpoint();
        $this->assertNotEmpty($endpointId);
        $result = $this->pullCoreModels('Manufacturer', 1, $endpointId);
        $this->assertCoreModel($manufacturer, $result);
        $this->deleteModel('Manufacturer', $endpointId, $this->hostId);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testManufacturerBasicPush()
    {
        $manufacturer = new Manufacturer();
        $manufacturer->setName('Test');
        $manufacturer->setSort(0);
        $manufacturer->setUrlPath('');
        $manufacturer->setWebsiteUrl('');
        $i18n = new ManufacturerI18n();
        $i18n->setManufacturerId(new Identity('', $this->hostId));
        $i18n->setLanguageISO('ger');
        $manufacturer->addI18n($i18n);
        
        $this->push($manufacturer);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testManufacturerI18nPush()
    {
        $manufacturer = new Manufacturer();
        $manufacturer->setName('Test');
        $i18n = new ManufacturerI18n();
        $i18n->setManufacturerId(new Identity('', $this->hostId));
        $i18n->setDescription('');
        $i18n->setLanguageISO('ger');
        $i18n->setMetaDescription('metaDescription');
        $i18n->setMetaKeywords('metaKeywords');
        $i18n->setTitleTag('titleTag');
        $manufacturer->addI18n($i18n);
    
        $this->push($manufacturer);
    }
}
