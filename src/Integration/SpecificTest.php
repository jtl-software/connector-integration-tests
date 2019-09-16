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
        $i18n = new SpecificI18n();
        $i18n->setSpecificId(new Identity('', 1));
        $i18n->setLanguageISO('ger');
        $i18n->setName('Specific Name');
        $specific->setI18ns([$i18n]);
        $specific->setType('string');
        $specific->setId(new Identity('', $this->hostId));
        
        $endpointId = $this->pushCoreModels([$specific], true)[0]->getId()->getEndpoint();
        $this->assertNotEmpty($endpointId);
        $result = $this->pullCoreModels('Specific', 1, $endpointId);
        $this->assertCoreModel($specific, $result);
        $this->deleteModel('Specific', $endpointId, $this->hostId);
    }
    public function testSpecificBasicPush()
    {
        $specific = new Specific();
        $specific->setIsGlobal(true);
        $specific->setSort(0);
        
        $this->push($specific);
    }
    
    public function testSpecificValuesPush()
    {
        $specific = new Specific();
        $value = new SpecificValue();
        $value->setSpecificId(new Identity('', $this->hostId));
        $value->setSort(0);
        $specificValueI18n = new SpecificValueI18n();
        $specificValueI18n->setSpecificValueId(new Identity('', 1));
        $specificValueI18n->setDescription('Specific Beschreibung');
        $specificValueI18n->setLanguageISO('ger');
        $specificValueI18n->setMetaDescription('Meta Beschreibung');
        $specificValueI18n->setMetaKeywords('Meta Keywords');
        $specificValueI18n->setTitleTag('Title Tag');
        $specificValueI18n->setUrlPath('URL Pfad');
        $specificValueI18n->setValue('Value Value');
        $value->addI18n($specificValueI18n);
        $specific->addValue($value);
        
        $this->push($specific);
    }
}
