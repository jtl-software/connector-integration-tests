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
        $i18n->setLanguageISO('ger');
        $i18n->setName('test');
        $i18n->setUrlPath('test');
        $category->addI18n($i18n);
        $category->setIsActive(true);
        $category->setLevel(5);
        $category->setSort(3);
        $i18n->setCategoryId(new Identity('', 1));
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
        $i18n->setCategoryId(new Identity('', 1));
        $i18n->setLanguageISO('ger');
        $i18n->setName('test');
        $i18n->setUrlPath('test');
        $category->addI18n($i18n);
        $attribute = new CategoryAttr();
        $attribute->setCategoryId(new Identity('', 1));
        $attribute->setId(new Identity('', 1));
        $attribute->setIsCustomProperty(true);
        $attribute->setIsTranslated(true);
        $i18n = new CategoryAttrI18n();
        $i18n->setCategoryAttrId(new Identity('', 1));
        $i18n->setLanguageISO('ger');
        $i18n->setName('test');
        $i18n->setValue('test');
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
        $i18n->setCategoryId(new Identity('', 1));
        $i18n->setLanguageISO('ger');
        $i18n->setName('test');
        $i18n->setUrlPath('test');
        $category->addI18n($i18n);
        $customerGroup = new CategoryCustomerGroup();
        $customerGroup->setCategoryId(new Identity('', 1));
        $customerGroup->setCustomerGroupId(new Identity('', 1));
        $customerGroup->setDiscount(3.44);
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
        $i18n->setCategoryId(new Identity('', 1));
        $i18n->setDescription('test');
        $i18n->setLanguageISO('ger');
        $i18n->setMetaDescription('test');
        $i18n->setMetaKeywords('test');
        $i18n->setName('test');
        $i18n->setTitleTag('test');
        $i18n->setUrlPath('test');
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
        $i18n->setCategoryId(new Identity('', 1));
        $i18n->setLanguageISO('ger');
        $i18n->setName('test');
        $i18n->setUrlPath('test');
        $category->addI18n($i18n);
        $invisibility = new CategoryInvisibility();
        $invisibility->setCategoryId(new Identity('', 1));
        $invisibility->setCustomerGroupId(new Identity('', 1));
        $category->addInvisibility($invisibility);
        $this->push($category);
    }
}
