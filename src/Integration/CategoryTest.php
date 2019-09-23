<?php

namespace Jtl\Connector\IntegrationTests\Integration;

use jtl\Connector\Exception\LinkerException;
use Jtl\Connector\IntegrationTests\ConnectorTestCase;
use jtl\Connector\Model\Category;
use jtl\Connector\Model\CategoryAttr;
use jtl\Connector\Model\CategoryAttrI18n;
use jtl\Connector\Model\CategoryI18n;
use jtl\Connector\Model\CategoryInvisibility;
use jtl\Connector\Model\Identity;
use jtl\Connector\Model\CategoryCustomerGroup;

abstract class CategoryTest extends ConnectorTestCase
{
    /**
     * @param Category $category
     * @throws \ReflectionException
     * @throws LinkerException
     */
    protected function push(Category $category)
    {
        $category->setId(new Identity('', $this->hostId));
        $endpointId = $this->pushCoreModels([$category], true)[0]->getId()->getEndpoint();
        $this->assertNotEmpty($endpointId);
        $result = $this->pullCoreModels('Category', 1, $endpointId);
        $this->assertCoreModel($category, $result);
        $this->deleteModel('Category', $endpointId, $this->hostId);
    }
    
    /**
     * @throws \ReflectionException
     * @throws LinkerException
     */
    public function testCategoryBasicPush()
    {
        $category = new Category();
        $i18n = new CategoryI18n();
        $i18n->setLanguageISO('ger')
            ->setName('test')
            ->setUrlPath('test')
            ->setCategoryId(new Identity('', 1));
        $category->addI18n($i18n)
            ->setIsActive(true)
            ->setLevel(5)
            ->setSort(3);
        $this->push($category);
    }
    
    /**
     * @throws \ReflectionException
     * @throws LinkerException
     */
    public function testCategoryAttributesPush()
    {
        $category = new Category();
        $i18n = new CategoryI18n();
        $i18n->setCategoryId(new Identity('', 1))
            ->setLanguageISO('ger')
            ->setName('test')
            ->setUrlPath('test');
        $category->addI18n($i18n);
        
        $attribute = new CategoryAttr();
        $attribute->setCategoryId(new Identity('', 1))
            ->setId(new Identity('', 1))
            ->setIsCustomProperty(true)
            ->setIsTranslated(true);
        
        $i18n = new CategoryAttrI18n();
        $i18n->setCategoryAttrId(new Identity('', 1))
            ->setLanguageISO('ger')
            ->setName('test')
            ->setValue('test');
        $attribute->addI18n($i18n);
        
        $category->addAttribute($attribute);
        $this->push($category);
    }
    
    /**
     * @throws \ReflectionException
     * @throws LinkerException
     */
    public function testCategoryCustomGroupsPush()
    {
        $category = new Category();
        
        $i18n = new CategoryI18n();
        $i18n->setCategoryId(new Identity('', 1))
            ->setLanguageISO('ger')
            ->setName('test')
            ->setUrlPath('test');
        $category->addI18n($i18n);
        
        $customerGroup = new CategoryCustomerGroup();
        $customerGroup->setCategoryId(new Identity('', 1))
            ->setCustomerGroupId(new Identity('', 1))
            ->setDiscount(3.44);
        $category->addCustomerGroup($customerGroup);
        
        $this->push($category);
    }
    
    /**
     * @throws \ReflectionException
     * @throws LinkerException
     */
    public function testCategoryI18nsPush()
    {
        $category = new Category();
        
        $i18n = new CategoryI18n();
        $i18n->setCategoryId(new Identity('', 1))
            ->setDescription('test')
            ->setLanguageISO('ger')
            ->setMetaDescription('test')
            ->setMetaKeywords('test')
            ->setName('test')
            ->setTitleTag('test')
            ->setUrlPath('test');
        $category->addI18n($i18n);
        
        $this->push($category);
    }
    
    /**
     * @throws \ReflectionException
     * @throws LinkerException
     */
    public function testCategoryInvisibilitiesPush()
    {
        $category = new Category();
        
        $i18n = new CategoryI18n();
        $i18n->setCategoryId(new Identity('', 1))
            ->setLanguageISO('ger')
            ->setName('test')
            ->setUrlPath('test');
        $category->addI18n($i18n);
        
        $invisibility = new CategoryInvisibility();
        $invisibility->setCategoryId(new Identity('', 1))
            ->setCustomerGroupId(new Identity('', 1));
        $category->addInvisibility($invisibility);
        
        $this->push($category);
    }
}
