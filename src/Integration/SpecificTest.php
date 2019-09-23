<?php

namespace Jtl\Connector\IntegrationTests\Integration;

use Jtl\Connector\IntegrationTests\ConnectorTestCase;
use jtl\Connector\Model\Identity;
use jtl\Connector\Model\Specific;
use jtl\Connector\Model\SpecificI18n;
use jtl\Connector\Model\SpecificValue;
use jtl\Connector\Model\SpecificValueI18n;

abstract class SpecificTest extends ConnectorTestCase
{
    public function push(Specific $specific)
    {
        $i18n = (new SpecificI18n())
            ->setSpecificId(new Identity('', 1))
            ->setLanguageISO('ger')
            ->setName('Specific Name');
        
        $specific->setI18ns([$i18n])
            ->setType('string')
            ->setId(new Identity('', $this->hostId));
        
        $endpointId = $this->pushCoreModels([$specific], true)[0]->getId()->getEndpoint();
        $this->assertNotEmpty($endpointId);
        $result = $this->pullCoreModels('Specific', 1, $endpointId);
        $this->assertCoreModel($specific, $result);
        $this->deleteModel('Specific', $endpointId, $this->hostId);
    }
    
    public function testSpecificBasicPush()
    {
        $specific = (new Specific())
            ->setIsGlobal(true)
            ->setSort(0);
        
        $this->push($specific);
    }
    
    public function testSpecificValuesPush()
    {
        $specific = new Specific();
        
        $value = (new SpecificValue())
            ->setSpecificId(new Identity('', $this->hostId))
            ->setSort(0);
        
        $specificValueI18n = (new SpecificValueI18n())
            ->setSpecificValueId(new Identity('', 1))
            ->setDescription('Specific Beschreibung')
            ->setLanguageISO('ger')
            ->setMetaDescription('Meta Beschreibung')
            ->setMetaKeywords('Meta Keywords')
            ->setTitleTag('Title Tag')
            ->setUrlPath('URL Pfad')
            ->setValue('Value Value');
        
        $value->addI18n($specificValueI18n);
        $specific->addValue($value);
        
        $this->push($specific);
    }
}
