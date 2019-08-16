<?php

namespace ConnectorIntegrationTests\Integration;

use ConnectorIntegrationTests\ConnectorTestCase;
use jtl\Connector\Model\Category;
use jtl\Connector\Model\CategoryAttr;
use jtl\Connector\Model\CategoryAttrI18n;
use jtl\Connector\Model\CategoryI18n;
use jtl\Connector\Model\CategoryInvisibility;
use jtl\Connector\Model\Identity;
use jtl\Connector\Model\CategoryCustomerGroup;

class CategoryTest extends ConnectorTestCase
{
    public function testCategoryBasicPush()
    {
        $category = new Category();
            $i18n = new CategoryI18n();
            $i18n->setCategoryId(new Identity('', 1));
            $i18n->setLanguageISO('ger');
            $i18n->setName('test');
        $category->addI18n($i18n);
        $category->setId(new Identity('', 1));
        $category->setIsActive(true);
        $category->setLevel(0);
        $category->setSort(0);
        
        $endpointId = $this->pushCoreModels([$category], true)[0]->getId()->getEndpoint();
        $result = $this->pullCoreModels('Category', 1, $endpointId);
        $this->assertCoreModel($category, $result);
    }
    
    public function testCategoryAttributesPush()
    {
        $category = new Category();
            $i18n = new CategoryI18n();
            $i18n->setCategoryId(new Identity('', 1));
            $i18n->setLanguageISO('ger');
            $i18n->setName('test');
        $category->addI18n($i18n);
            $attribute = new CategoryAttr();
            $attribute->setCategoryId(new Identity('', 1));
            $attribute->setId(new Identity('', 1));
            $attribute->setIsCustomProperty(true);
            $attribute->setIsTranslated(true);
                $i18n = new CategoryAttrI18n();
                $i18n->setCategoryAttrId(new Identity('', 1));
                $i18n->setLanguageISO('');
                $i18n->setName('');
                $i18n->setValue('');
            $attribute->setI18ns([$i18n]);
        $category->setAttributes([$attribute]);
        
        $endpointId = $this->pushCoreModels([$category], true)[0]->getId()->getEndpoint();
        $result = $this->pullCoreModels('Category', 1, $endpointId);
        $this->assertCoreModel($category, $result);
    }
    
    public function testCategoryCustomGroupsPush()
    {
        $category = new Category();
            $i18n = new CategoryI18n();
            $i18n->setCategoryId(new Identity('', 1));
            $i18n->setLanguageISO('ger');
            $i18n->setName('test');
        $category->addI18n($i18n);
            $customerGroup = new CategoryCustomerGroup();
            $customerGroup->setCategoryId(new Identity('', 1));
            $customerGroup->setCustomerGroupId(new Identity('', 1));
            $customerGroup->setDiscount(0.0);
        $category->setCustomerGroups([$customerGroup]);
    
        $endpointId = $this->pushCoreModels([$category], true)[0]->getId()->getEndpoint();
        $result = $this->pullCoreModels('Category', 1, $endpointId);
        $this->assertCoreModel($category, $result);
    }
    
    public function testCategoryI18nsPush()
    {
        $category = new Category();
            $i18n = new CategoryI18n();
            $i18n->setCategoryId(new Identity('', 1));
            $i18n->setDescription('');
            $i18n->setLanguageISO('ger');
            $i18n->setMetaDescription('');
            $i18n->setMetaKeywords('');
            $i18n->setName('TEST');
            $i18n->setTitleTag('');
            $i18n->setUrlPath('');
        $category->addI18n($i18n);
    
        $endpointId = $this->pushCoreModels([$category], true)[0]->getId()->getEndpoint();
        $result = $this->pullCoreModels('Category', 1, $endpointId);
        $this->assertCoreModel($category, $result);
    }
    
    public function testCategoryInvisibilitiesPush()
    {
        $category = new Category();
            $i18n = new CategoryI18n();
            $i18n->setCategoryId(new Identity('', 1));
            $i18n->setLanguageISO('ger');
            $i18n->setName('test');
        $category->addI18n($i18n);
            $invisibility = new CategoryInvisibility();
            $invisibility->setCategoryId(new Identity('', 1));
            $invisibility->setCustomerGroupId(new Identity('', 1));
        $category->setInvisibilities([$invisibility]);
    
        $endpointId = $this->pushCoreModels([$category], true)[0]->getId()->getEndpoint();
        $result = $this->pullCoreModels('Category', 1, $endpointId);
        $this->assertCoreModel($category, $result);
    }
    
    public function getIgnoreArray()
    {
        // TODO: Implement getIgnoreArray() method.
    }
}