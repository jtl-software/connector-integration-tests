<?php
namespace Jtl\Connector\IntegrationTests\Integration;

use jtl\Connector\Exception\LinkerException;
use Jtl\Connector\IntegrationTests\ConnectorTestCase;
use DateTime;
use jtl\Connector\Model\CategoryI18n;
use jtl\Connector\Model\CustomerGroupPackagingQuantity;
use jtl\Connector\Model\FileUpload;
use jtl\Connector\Model\FileUploadI18n;
use jtl\Connector\Model\Identity;
use jtl\Connector\Model\Manufacturer;
use jtl\Connector\Model\ManufacturerI18n;
use jtl\Connector\Model\Product;
use jtl\Connector\Model\ProductAttr;
use jtl\Connector\Model\ProductAttrI18n;
use jtl\Connector\Model\Product2Category;
use jtl\Connector\Model\ProductConfigGroup;
use jtl\Connector\Model\ProductI18n;
use jtl\Connector\Model\ProductInvisibility;
use jtl\Connector\Model\ProductMediaFile;
use jtl\Connector\Model\ProductMediaFileAttr;
use jtl\Connector\Model\ProductMediaFileAttrI18n;
use jtl\Connector\Model\ProductMediaFileI18n;
use jtl\Connector\Model\ProductPartsList;
use jtl\Connector\Model\ProductPrice;
use jtl\Connector\Model\ProductPriceItem;
use jtl\Connector\Model\ProductSpecialPrice;
use jtl\Connector\Model\ProductSpecialPriceItem;
use jtl\Connector\Model\ProductSpecific;
use jtl\Connector\Model\ProductStockLevel;
use jtl\Connector\Model\ProductVariation;
use jtl\Connector\Model\ProductVariationI18n;
use jtl\Connector\Model\ProductVariationValue;
use jtl\Connector\Model\ProductVariationValueI18n;
use jtl\Connector\Model\ProductWarehouseInfo;
use jtl\Connector\Model\Specific;
use jtl\Connector\Model\SpecificI18n;
use jtl\Connector\Model\SpecificValue;
use jtl\Connector\Model\SpecificValueI18n;

abstract class ProductTest extends ConnectorTestCase
{
    /**
     * @param Product $product
     * @throws LinkerException
     * @throws \ReflectionException
     */
    protected function push(Product $product)
    {
        $productI18n = (new ProductI18n())
            ->setProductId(new Identity('', $this->hostId))
            ->setDeliveryStatus('Done')
            ->setDescription('Beschreibung')
            ->setLanguageISO('ger')
            ->setMeasurementUnitName('kg')
            ->setMetaDescription('metaDescription')
            ->setMetaKeywords('metaKeywords')
            ->setName('testartikel')
            ->setShortDescription('Kurze Beschreibung')
            ->setTitleTag('Titel Tag')
            ->setUnitName('Test')
            ->setUrlPath('test-url');
        $product->setI18ns([$productI18n]);
        
        $price = (new ProductPrice())
            ->setCustomerGroupId(new Identity('', 1))
            ->setCustomerId(new Identity('', 1))
            ->setId(new Identity('', 1))
            ->setProductId(new Identity('', $this->hostId));
        
        $priceItem = (new ProductPriceItem())
            ->setProductPriceId(new Identity('', 1))
            ->setNetPrice(32.51)
            ->setQuantity(33);
        
        $price->setItems([$priceItem]);
        $product->setPrices([$price]);
        
        if ($product->getMinimumOrderQuantity() == 0) {
            $product->setMinimumOrderQuantity(1);
        }
        if ($product->getCreationDate() == null) {
            $product->setCreationDate(new DateTime('2019-08-21T00:00:00+0200'));
        }
        
        $product->setId(new Identity('', $this->hostId));
        $endpointId = $this->pushCoreModels([$product], true)[0]->getId()->getEndpoint();
        
        $this->assertNotEmpty($endpointId);
        $result = $this->pullCoreModels('Product', 1, $endpointId);
        $this->assertCoreModel($product, $result);
        $this->deleteModel('Product', $endpointId, $this->hostId);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductBasicPush()
    {
        $product = (new Product())
            ->setBasePriceUnitId(new Identity('', 1))
            ->setStockLevel(new ProductStockLevel())
            ->addPrice(new ProductPrice())
            ->setAsin('B07K199T2Y')
            ->setAvailableFrom(new DateTime('2019-08-21T00:00:00+0200'))
            ->setBasePriceDivisor(33.56)
            ->setBasePriceFactor(33.56)
            ->setBasePriceQuantity(33.56)
            ->setBasePriceUnitCode('Test')
            ->setBasePriceUnitName('Test')
            ->setConsiderBasePrice(true)
            ->setConsiderStock(true)
            ->setConsiderVariationStock(true)
            ->setCreationDate(new DateTime('2019-08-21T00:00:00+0200'))
            ->setEan('1234567890123')
            ->setEpid('Test')
            ->setHazardIdNumber('Test')
            ->setHeight(33.56)
            ->setIsActive(false)
            ->setIsBatch(true)
            ->setIsBestBefore(true)
            ->setIsbn('978-1692748777')
            ->setIsDivisible(true)
            ->setIsMasterProduct(false)
            ->setIsNewProduct(true)
            ->setIsSerialNumber(true)
            ->setIsTopProduct(true)
            ->setKeywords('Test')
            ->setLength(33.56)
            ->setManufacturerNumber('Test')
            ->setMeasurementQuantity(33.56)
            ->setMeasurementUnitCode('Test')
            ->setMinBestBeforeDate(new DateTime())
            ->setMinimumOrderQuantity(1)
            ->setMinimumQuantity(64)
            ->setModified(new DateTime())
            ->setNewReleaseDate(new DateTime())
            ->setNextAvailableInflowDate(new DateTime())
            ->setNextAvailableInflowQuantity(33.56)
            ->setNote('Test')
            ->setOriginCountry('Test')
            ->setPackagingQuantity(33.56)
            ->setPermitNegativeStock(true)
            ->setProductWeight(33.56)
            ->setPurchasePrice(33.56)
            ->setRecommendedRetailPrice(33.56)
            ->setSerialNumber('Test')
            ->setShippingWeight(33.56)
            ->setSku('Test')
            ->setSort(64)
            ->setSupplierDeliveryTime(64)
            ->setSupplierStockLevel(33.56)
            ->setTaric('Test')
            ->setUnNumber('Test')
            ->setUpc('123456789012')
            ->setVat(19)
            ->setWidth(33.56);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductManufacturerPush()
    {
        $manufacturer = (new Manufacturer())
            ->setName('Test')
            ->setSort(64)
            ->setUrlPath('Test')
            ->setWebsiteUrl('Test');
        
        $i18n = (new ManufacturerI18n())
            ->setManufacturerId(new Identity('', $this->hostId))
            ->setLanguageISO('ger');
        
        $manufacturer->addI18n($i18n);
        $manufacturerId = $this->pushCoreModels([$manufacturer], false)[0]->getId();
        
        $product = (new Product())
            ->setStockLevel(new ProductStockLevel())
            ->addPrice(new ProductPrice())
            ->setManufacturerId($manufacturerId);
        
        $this->push($product);
        $this->deleteModel('manufacturer', $manufacturerId->getEndpoint(), 1);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductStockLevelPush()
    {
        $product = (new Product())
            ->setStockLevel(new ProductStockLevel())
            ->addPrice(new ProductPrice());
        
        $stockLevel = (new ProductStockLevel())
            ->setProductId(new Identity('', $this->hostId))
            ->setStockLevel(33);
        
        $product->setStockLevel($stockLevel);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductAttributePush()
    {
        $product = (new Product())
            ->setStockLevel(new ProductStockLevel())
            ->addPrice(new ProductPrice());
        
        $attribute = (new ProductAttr())
            ->setId(new Identity('', 1))
            ->setProductId(new Identity('', $this->hostId))
            ->setIsCustomProperty(true)
            ->setIsTranslated(true);
        
        $attributeI18n = (new ProductAttrI18n())
            ->setProductAttrId(new Identity('', 1))
            ->setLanguageISO('ger')
            ->setName('Test')
            ->setValue('Test');
        
        $attribute->setI18ns([$attributeI18n]);
        $product->setAttributes([$attribute]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductToCategoryPush()
    {
        $product = (new Product())
            ->setStockLevel(new ProductStockLevel())
            ->addPrice(new ProductPrice());
        
        $productsToCategories = (new Product2Category())
            ->setCategoryId(new Identity('', 1))
            ->setId(new Identity('', 1))
            ->setProductId(new Identity('', $this->hostId));
        
        $product->setCategories([$productsToCategories]);
        
        $category = new \jtl\Connector\Model\Category();
        $i18n = (new CategoryI18n())
            ->setLanguageISO('ger')
            ->setName('test')
            ->setUrlPath('test')
            ->setCategoryId(new Identity('', 1));
        $category->addI18n($i18n)
            ->setId(new Identity('', 1))
            ->setIsActive(true)
            ->setLevel(5)
            ->setSort(3);
        
        $categoryPush = $this->pushCoreModels([$category], false);
        $this->push($product);
        $this->deleteModel('category', $categoryPush[0]->getId()->getEndpoint(), 1);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductConfigGroupPush()
    {
        $product = (new Product())
            ->setStockLevel(new ProductStockLevel())
            ->addPrice(new ProductPrice());
        
        $configGroup = (new ProductConfigGroup())
            ->setConfigGroupId(new Identity('', 1))
            ->setProductId(new Identity('', $this->hostId))
            ->setSort(64);
        
        $product->setConfigGroups([$configGroup]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductCustomerGroupPackagingQuantityPush()
    {
        $product = (new Product())
            ->setStockLevel(new ProductStockLevel())
            ->addPrice(new ProductPrice());
        
        $packagingQuantity = (new CustomerGroupPackagingQuantity())
            ->setCustomerGroupId(new Identity('', 1))
            ->setProductId(new Identity('', $this->hostId))
            ->setMinimumOrderQuantity(1)
            ->setPackagingQuantity(33.56);
        
        $product->setCustomerGroupPackagingQuantities([$packagingQuantity]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductFileUploadPush()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        
        $product = (new Product())
            ->setStockLevel(new ProductStockLevel())
            ->addPrice(new ProductPrice());
        
        $fileUpload = (new FileUpload())
            ->setId(new Identity('', 1))
            ->setProductId(new Identity('', $this->hostId))
            ->setFileType('Test')
            ->setIsRequired(true);
        
        $fileUploadI18n = (new FileUploadI18n())
            ->setDescription('Test')
            ->setFileUploadId(64)
            ->setLanguageISO('ger')
            ->setName('Test');
        
        $fileUpload->setI18ns([$fileUploadI18n]);
        $product->setFileDownloads([$fileUpload]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductInvisibilityPush()
    {
        $product = (new Product())
            ->setStockLevel(new ProductStockLevel())
            ->addPrice(new ProductPrice());
        
        $invisibility = (new ProductInvisibility())
            ->setCustomerGroupId(new Identity('', 1))
            ->setProductId(new Identity('', $this->hostId));
        
        $product->setInvisibilities([$invisibility]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductMediaFilePush()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        
        $product = (new Product())
            ->setStockLevel(new ProductStockLevel())
            ->addPrice(new ProductPrice());
        
        $mediaFile = (new ProductMediaFile())
            ->setId(new Identity('', 1))
            ->setProductId(new Identity('', $this->hostId))
            ->setMediaFileCategory('Test')
            ->setPath('Test')
            ->setSort(64)
            ->setType('Test')
            ->setUrl('Test');
        
        $mediaFileAttribute = (new ProductMediaFileAttr())
            ->setProductMediaFileId(new Identity('', 1));
        
        $mediaFileAttributeI18n = (new ProductMediaFileAttrI18n())
            ->setLanguageISO('ger')
            ->setName('Test')
            ->setValue('Test');
        
        $mediaFileAttribute->setI18ns([$mediaFileAttributeI18n]);
        $mediaFile->setAttributes([$mediaFileAttribute]);
        
        $mediaFileI18n = (new ProductMediaFileI18n())
            ->setProductMediaFileId(new Identity('', 1))
            ->setDescription('Test')
            ->setLanguageISO('ger')
            ->setName('Test');
        
        $mediaFile->setI18ns([$mediaFileI18n]);
        $product->setMediaFiles([$mediaFile]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductPartsListPush()
    {
        $product = (new Product())
            ->setStockLevel(new ProductStockLevel())
            ->addPrice(new ProductPrice());
        
        $partsList = (new ProductPartsList())
            ->setId(new Identity('', 1))
            ->setProductId(new Identity('', $this->hostId))
            ->setQuantity(33.56);
        
        $product->setPartsLists([$partsList]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductSpecialPricePush()
    {
        $product = (new Product())
            ->setStockLevel(new ProductStockLevel())
            ->addPrice(new ProductPrice());
        
        $specialPrice = (new ProductSpecialPrice())
            ->setId(new Identity('', 1))
            ->setProductId(new Identity('', $this->hostId))
            ->setActiveFromDate(new DateTime('now', new \DateTimeZone('+0200')))
            ->setActiveUntilDate(new DateTime('now', new \DateTimeZone('+0200')))
            ->setConsiderDateLimit(true)
            ->setConsiderStockLimit(true)
            ->setIsActive(true)
            ->setStockLimit(64);
        
        $specialPriceItem = (new ProductSpecialPriceItem())
            ->setCustomerGroupId(new Identity('', 1))
            ->setProductSpecialPriceId(new Identity('', 1))
            ->setPriceNet(33.56);
        
        $specialPrice->setItems([$specialPriceItem]);
        $product->setSpecialPrices([$specialPrice]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductSpecificPush()
    {
        $specific = new Specific();
        
        $i18n = (new SpecificI18n())
            ->setSpecificId(new Identity('', 1))
            ->setLanguageISO('ger')
            ->setName('Specific Name');
        
        $specific->setI18ns([$i18n])
            ->setType('string')
            ->setId(new Identity('', $this->hostId));
        
        $value = (new SpecificValue())
            ->setSpecificId(new Identity('', $this->hostId))
            ->setSort(64);
        
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
        
        $specificId = $this->pushCoreModels([$specific], false)[0]->getId();
        
        $product = (new Product())
            ->setStockLevel(new ProductStockLevel())
            ->addPrice(new ProductPrice());
        
        $productSpecific = (new ProductSpecific())
            ->setId(new Identity('', $this->hostId))
            ->setProductId(new Identity('', $this->hostId))
            ->setSpecificValueId($specificId);
        
        $product->setSpecifics([$productSpecific]);
        
        $this->push($product);
        
        $this->deleteModel('specific', $specificId->getEndpoint(), 1);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductVarCombinationPush()
    {
        $parent = (new Product())
            ->setId(new Identity('', $this->hostId))
            ->setStockLevel(new ProductStockLevel());
        
        $productI18n = (new ProductI18n())
            ->setProductId(new Identity('', $this->hostId))
            ->setDeliveryStatus('Done')
            ->setDescription('Beschreibung')
            ->setLanguageISO('ger')
            ->setMeasurementUnitName('')
            ->setMetaDescription('metaDescription')
            ->setMetaKeywords('metaKeywords')
            ->setName('testartikel')
            ->setShortDescription('Kurze Beschreibung')
            ->setTitleTag('Titel Tag')
            ->setUnitName('Test')
            ->setUrlPath('test-url');
        
        $price = (new ProductPrice())
            ->setCustomerGroupId(new Identity('', 1))
            ->setCustomerId(new Identity('', 1))
            ->setId(new Identity('', 1))
            ->setProductId(new Identity('', $this->hostId));
        
        $priceItem = (new ProductPriceItem())
            ->setProductPriceId(new Identity('', 1))
            ->setNetPrice(44.52)
            ->setQuantity(23);
        
        $price->setItems([$priceItem]);
        $parent->setPrices([$price]);
        
        $parent->setMinimumOrderQuantity(1)
            ->setCreationDate(new DateTime('2019-08-21T00:00:00+0200'));
        $parent->setIsMasterProduct(true);
        
        $variation = (new ProductVariation())
            ->setId(new Identity('', 1))
            ->setProductId(new Identity('', $this->hostId))
            ->setSort(64)
            ->setType('SELECTBOX');
        
        $variationI18n = (new ProductVariationI18n())
            ->setProductVariationId(new Identity('', 1))
            ->setLanguageISO('ger')
            ->setName('Produktvariation');
        
        $variation->setI18ns([$variationI18n]);
        
        $variationValue = (new ProductVariationValue())
            ->setId(new Identity('', 1))
            ->setProductVariationId(new Identity('', 1))
            ->setEan('1234567890123')
            ->setExtraWeight(3)
            ->setSku('TEST')
            ->setSort(4)
            ->setStockLevel(22);
        
        $variationValueI18n = (new ProductVariationValueI18n())
            ->setProductVariationValueId(new Identity('', 1))
            ->setLanguageISO('ger')
            ->setName('Wert der Produktvariation');
        
        $variationValue->setI18ns([$variationValueI18n]);
        $variation->setValues([$variationValue]);
        $parent->setVariations([$variation]);
        $parent->setI18ns([$productI18n]);
        
        $child = (new Product())
            ->setId(new Identity('', 44))
            ->setStockLevel(new ProductStockLevel())
            ->setPrices([$price])
            ->setMinimumOrderQuantity(1)
            ->setCreationDate(new DateTime('2019-08-21T00:00:00+0200'))
            ->setIsMasterProduct(false)
            ->setEan('1234567890123')
            ->setSku('TEST')
            ->setSort(4)
            ->setMasterProductId(new Identity('', $this->hostId))
            ->setVariations([$variation])
            ->setI18ns([$productI18n]);
        
        $result = $this->pushCoreModels([$parent, $child], true);
        $parentEndpointId = $result[0]->getId()->getEndpoint();
        $childEndpointId = $result[1]->getId()->getEndpoint();
        
        $this->assertNotEmpty($parentEndpointId);
        $this->assertNotEmpty($childEndpointId);
        
        $parentResult = $this->pullCoreModels('Product', 1, $parentEndpointId);
        $childResult = $this->pullCoreModels('Product', 1, $childEndpointId);
        
        $this->assertCoreModel($parent, $parentResult);
        $this->assertCoreModel($childResult, $childResult);
        
        $this->deleteModel('Product', $parentEndpointId, $this->hostId);
        $this->deleteModel('Product', $childEndpointId, 2);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductVariationPush()
    {
        $product = (new Product())
            ->setStockLevel(new ProductStockLevel())
            ->addPrice(new ProductPrice());
        
        $variation = (new ProductVariation())
            ->setId(new Identity('', 1))
            ->setProductId(new Identity('', $this->hostId))
            ->setSort(64)
            ->setType('SELECTBOX');
        
        $variationI18n = (new ProductVariationI18n())
            ->setProductVariationId(new Identity('', 1))
            ->setLanguageISO('de')
            ->setName('Produktvariation');
        
        $variation->setI18ns([$variationI18n]);
        
        $variationValue = (new ProductVariationValue())
            ->setId(new Identity('', 1))
            ->setProductVariationId(new Identity('', 1))
            ->setEan('1234567890123')
            ->setExtraWeight(33.56)
            ->setSku('Test')
            ->setSort(64)
            ->setStockLevel(22);
        
        $variationValueI18n = (new ProductVariationValueI18n())
            ->setProductVariationValueId(new Identity('', 1))
            ->setLanguageISO('de')
            ->setName('Wert der Produktvariation');
        
        $variationValue->setI18ns([$variationValueI18n]);
        $variation->setValues([$variationValue]);
        $product->setVariations([$variation]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductWarehousePush()
    {
        $product = (new Product())
            ->setStockLevel(new ProductStockLevel())
            ->addPrice(new ProductPrice());
        
        $warehouseInfo = (new ProductWarehouseInfo())
            ->setProductId(new Identity('', $this->hostId))
            ->setwarehouseId(new Identity('', 1))
            ->setInflowQuantity(66.0)
            ->setstockLevel(5.0);
        
        $product->setWarehouseInfo([$warehouseInfo]);
        
        $this->push($product);
    }
}
