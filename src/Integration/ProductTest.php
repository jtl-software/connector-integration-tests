<?php
namespace Jtl\Connector\IntegrationTests\Integration;

use jtl\Connector\Exception\LinkerException;
use Jtl\Connector\IntegrationTests\ConnectorTestCase;
use DateTime;
use jtl\Connector\Model\CategoryI18n;
use jtl\Connector\Model\Checksum;
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
use jtl\Connector\Model\ProductVarCombination;
use jtl\Connector\Model\ProductVariation;
use jtl\Connector\Model\ProductVariationI18n;
use jtl\Connector\Model\ProductVariationInvisibility;
use jtl\Connector\Model\ProductVariationValue;
use jtl\Connector\Model\ProductVariationValueExtraCharge;
use jtl\Connector\Model\ProductVariationValueI18n;
use jtl\Connector\Model\ProductVariationValueInvisibility;
use jtl\Connector\Model\ProductWarehouseInfo;
use jtl\Connector\Model\Specific;
use jtl\Connector\Model\SpecificI18n;
use jtl\Connector\Model\SpecificValue;
use jtl\Connector\Model\SpecificValueI18n;
use jtl\Connector\Type\Category;

abstract class ProductTest extends ConnectorTestCase
{
    /**
     * @param Product $product
     * @throws LinkerException
     * @throws \ReflectionException
     */
    protected function push(Product $product)
    {
        $productI18n = new ProductI18n();
        $productI18n->setProductId(new Identity('', $this->hostId));
        $productI18n->setDeliveryStatus('Done');
        $productI18n->setDescription('Beschreibung');
        $productI18n->setLanguageISO('ger');
        $productI18n->setMeasurementUnitName('');
        $productI18n->setMetaDescription('metaDescription');
        $productI18n->setMetaKeywords('metaKeywords');
        $productI18n->setName('testartikel');
        $productI18n->setShortDescription('Kurze Beschreibung');
        $productI18n->setTitleTag('Titel Tag');
        $productI18n->setUnitName('Test');
        $productI18n->setUrlPath('Test URL');
        $product->setI18ns([$productI18n]);
        
        $price = new ProductPrice();
        $price->setCustomerGroupId(new Identity('', 1));
        $price->setCustomerId(new Identity('', 1));
        $price->setId(new Identity('', 1));
        $price->setProductId(new Identity('', $this->hostId));
        $priceItem = new ProductPriceItem();
        $priceItem->setProductPriceId(new Identity('', 1));
        $priceItem->setNetPrice(32.51);
        $priceItem->setQuantity(33);
        $price->setItems([$priceItem]);
        $product->setPrices([$price]);
        
        if ($product->getMinimumOrderQuantity() == 0) {
            $product->setMinimumOrderQuantity(1);
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
        $product = new Product();
        $product->setBasePriceUnitId(new Identity('', 1));
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $product->setAsin('Test Asin');
        $product->setAvailableFrom(new DateTime('2019-08-21T00:00:00+0200'));
        $product->setBasePriceDivisor(33.56);
        $product->setBasePriceFactor(33.56);
        $product->setBasePriceQuantity(33.56);
        $product->setBasePriceUnitCode('Test');
        $product->setBasePriceUnitName('Test');
        $product->setConsiderBasePrice(true);
        $product->setConsiderStock(true);
        $product->setConsiderVariationStock(true);
        $product->setCreationDate(new DateTime('2019-08-21T00:00:00+0200'));
        $product->setEan('1234567890123');
        $product->setEpid('Test');
        $product->setHazardIdNumber('Test');
        $product->setHeight(33.56);
        $product->setIsActive(true);
        $product->setIsBatch(true);
        $product->setIsBestBefore(true);
        $product->setIsbn('Test');
        $product->setIsDivisible(true);
        $product->setIsMasterProduct(true);
        $product->setIsNewProduct(true);
        $product->setIsSerialNumber(true);
        $product->setIsTopProduct(true);
        $product->setKeywords('Test');
        $product->setLength(33.56);
        $product->setManufacturerNumber('Test');
        $product->setMeasurementQuantity(33.56);
        $product->setMeasurementUnitCode('Test');
        $product->setMinBestBeforeDate(new DateTime());
        $product->setMinimumOrderQuantity(1);
        $product->setMinimumQuantity(64);
        $product->setModified(new DateTime());
        $product->setNewReleaseDate(new DateTime());
        $product->setNextAvailableInflowDate(new DateTime());
        $product->setNextAvailableInflowQuantity(33.56);
        $product->setNote('Test');
        $product->setOriginCountry('Test');
        $product->setPackagingQuantity(33.56);
        $product->setPermitNegativeStock(true);
        $product->setProductWeight(33.56);
        $product->setPurchasePrice(33.56);
        $product->setRecommendedRetailPrice(33.56);
        $product->setSerialNumber('Test');
        $product->setShippingWeight(33.56);
        $product->setSku('Test');
        $product->setSort(64);
        $product->setSupplierDeliveryTime(64);
        $product->setSupplierStockLevel(33.56);
        $product->setTaric('Test');
        $product->setUnNumber('Test');
        $product->setUpc('123456789012');
        $product->setVat(19);
        $product->setWidth(33.56);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductManufacturerPush(){
        $manufacturer = new Manufacturer();
        $manufacturer->setName('Test');
        $manufacturer->setSort(64);
        $manufacturer->setUrlPath('Test');
        $manufacturer->setWebsiteUrl('Test');
        $i18n = new ManufacturerI18n();
        $i18n->setManufacturerId(new Identity('', $this->hostId));
        $i18n->setLanguageISO('ger');
        $manufacturer->addI18n($i18n);
        
        $manufacturerId = $this->pushCoreModels([$manufacturer], false)[0]->getId();
        
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $product->setManufacturerId($manufacturerId);
        $this->push($product);
        
        $this->deleteModel('manufacturer', $manufacturerId->getEndpoint(), 1);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductStockLevelPush(){
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $stockLevel = new ProductStockLevel();
        $stockLevel->setProductId(new Identity('', $this->hostId));
        $stockLevel->setStockLevel(33);
        $product->setStockLevel($stockLevel);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductAttributePush(){
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $attribute = new ProductAttr();
        $attribute->setId(new Identity('', 1));
        $attribute->setProductId(new Identity('', $this->hostId));
        $attribute->setIsCustomProperty(true);
        $attribute->setIsTranslated(true);
        $attributeI18n = new ProductAttrI18n();
        $attributeI18n->setProductAttrId(new Identity('', 1));
        $attributeI18n->setLanguageISO('ger');
        $attributeI18n->setName('Test');
        $attributeI18n->setValue('Test');
        $attribute->setI18ns([$attributeI18n]);
        $product->setAttributes([$attribute]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductToCategoryPush(){
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $productsToCategories = new Product2Category();
        $productsToCategories->setCategoryId(new Identity('', 1));
        $productsToCategories->setId(new Identity('', 1));
        $productsToCategories->setProductId(new Identity('', $this->hostId));
        $product->setCategories([$productsToCategories]);
        
        $category = new \jtl\Connector\Model\Category();
        $i18n = new CategoryI18n();
        $i18n->setLanguageISO('ger');
        $i18n->setName('test');
        $i18n->setUrlPath('test');
        $i18n->setCategoryId(new Identity('', 1));
        $category->addI18n($i18n);
        $category->setId(new Identity('', 1));
        $category->setIsActive(true);
        $category->setLevel(5);
        $category->setSort(3);
        
        $categoryPush = $this->pushCoreModels([$category], false);
        $this->push($product);
        $this->deleteModel('category', $categoryPush[0]->getId()->getEndpoint(), 1);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductConfigGroupPush(){
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $configGroup = new ProductConfigGroup();
        $configGroup->setConfigGroupId(new Identity('', 1));
        $configGroup->setProductId(new Identity('', $this->hostId));
        $configGroup->setSort(64);
        $product->setConfigGroups([$configGroup]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductCustomerGroupPackagingQuantityPush(){
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $packagingQuantity = new CustomerGroupPackagingQuantity();
        $packagingQuantity->setCustomerGroupId(new Identity('', 1));
        $packagingQuantity->setProductId(new Identity('', $this->hostId));
        $packagingQuantity->setMinimumOrderQuantity(1);
        $packagingQuantity->setPackagingQuantity(33.56);
        $product->setCustomerGroupPackagingQuantities([$packagingQuantity]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductFileUploadPush(){
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $fileUpload = new FileUpload();
        $fileUpload->setId(new Identity('', 1));
        $fileUpload->setProductId(new Identity('', $this->hostId));
        $fileUpload->setFileType('Test');
        $fileUpload->setIsRequired(true);
        $fileUploadI18n = new FileUploadI18n();
        $fileUploadI18n->setDescription('Test');
        $fileUploadI18n->setFileUploadId(64);
        $fileUploadI18n->setLanguageISO('ger');
        $fileUploadI18n->setName('Test');
        $fileUpload->setI18ns([$fileUploadI18n]);
        $product->setFileDownloads([$fileUpload]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductInvisibilityPush(){
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $invisibility = new ProductInvisibility();
        $invisibility->setCustomerGroupId(new Identity('', 1));
        $invisibility->setProductId(new Identity('', $this->hostId));
        $product->setInvisibilities([$invisibility]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductMediaFilePush(){
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $mediaFile = new ProductMediaFile();
        $mediaFile->setId(new Identity('', 1));
        $mediaFile->setProductId(new Identity('', $this->hostId));
        $mediaFile->setMediaFileCategory('Test');
        $mediaFile->setPath('Test');
        $mediaFile->setSort(64);
        $mediaFile->setType('Test');
        $mediaFile->setUrl('Test');
        $mediaFileAttribute = new ProductMediaFileAttr();
        $mediaFileAttribute->setProductMediaFileId(new Identity('', 1));
        $mediaFileAttributeI18n = new ProductMediaFileAttrI18n();
        $mediaFileAttributeI18n->setLanguageISO('ger');
        $mediaFileAttributeI18n->setName('Test');
        $mediaFileAttributeI18n->setValue('Test');
        $mediaFileAttribute->setI18ns([$mediaFileAttributeI18n]);
        $mediaFile->setAttributes([$mediaFileAttribute]);
        $mediaFileI18n = new ProductMediaFileI18n();
        $mediaFileI18n->setProductMediaFileId(new Identity('', 1));
        $mediaFileI18n->setDescription('Test');
        $mediaFileI18n->setLanguageISO('ger');
        $mediaFileI18n->setName('Test');
        $mediaFile->setI18ns([$mediaFileI18n]);
        $product->setMediaFiles([$mediaFile]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductPartsListPush(){
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $partsList= new ProductPartsList();
        $partsList->setId(new Identity('', 1));
        $partsList->setProductId(new Identity('', $this->hostId));
        $partsList->setQuantity(33.56);
        $product->setPartsLists([$partsList]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductSpecialPricePush(){
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $specialPrice = new ProductSpecialPrice();
        $specialPrice->setId(new Identity('', 1));
        $specialPrice->setProductId(new Identity('', $this->hostId));
        $specialPrice->setActiveFromDate(new DateTime());
        $specialPrice->setActiveUntilDate(new DateTime());
        $specialPrice->setConsiderDateLimit(true);
        $specialPrice->setConsiderStockLimit(true);
        $specialPrice->setIsActive(true);
        $specialPrice->setStockLimit(64);
        $specialPriceItem = new ProductSpecialPriceItem();
        $specialPriceItem->setCustomerGroupId(new Identity('', 1));
        $specialPriceItem->setProductSpecialPriceId(new Identity('', 1));
        $specialPriceItem->setPriceNet(33.56);
        $specialPrice->setItems([$specialPriceItem]);
        $product->setSpecialPrices([$specialPrice]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductSpecificPush(){
        $specific = new Specific();
        $i18n = new SpecificI18n();
        $i18n->setSpecificId(new Identity('', 1));
        $i18n->setLanguageISO('ger');
        $i18n->setName('Specific Name');
        $specific->setI18ns([$i18n]);
        $specific->setType('string');
        $specific->setId(new Identity('', $this->hostId));
        
        $value = new SpecificValue();
        $value->setSpecificId(new Identity('', $this->hostId));
        $value->setSort(64);
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
        
        
        $specificId = $this->pushCoreModels([$specific], false)[0]->getId();
        
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $productSpecific = new ProductSpecific();
        $productSpecific->setId(new Identity('', $this->hostId));
        $productSpecific->setProductId(new Identity('', $this->hostId));
        $productSpecific->setSpecificValueId($specificId);
        $product->setSpecifics([$productSpecific]);
        
        $this->push($product);
        
        $this->deleteModel('specific', $specificId->getEndpoint(), 1);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductVarCombinationPush(){
        $parent = new Product();
        $parent->setId(new Identity('', $this->hostId));
        $parent->setStockLevel(new ProductStockLevel());
        $price = new ProductPrice();
        $price->setCustomerGroupId(new Identity('', 1));
        $price->setCustomerId(new Identity('', 1));
        $price->setId(new Identity('', 1));
        $price->setProductId(new Identity('', $this->hostId));
        $priceItem = new ProductPriceItem();
        $priceItem->setProductPriceId(new Identity('', 1));
        $priceItem->setNetPrice(44.52);
        $priceItem->setQuantity(23);
        $price->setItems([$priceItem]);
        $parent->setPrices([$price]);
        $parent->setMinimumOrderQuantity(1);
        $parent->setIsMasterProduct(true);
        $variation = new ProductVariation();
        $variation->setId(new Identity('', 1));
        $variation->setProductId(new Identity('', $this->hostId));
        $variation->setSort(64);
        $variation->setType('SELECTBOX');
        $variationI18n = new ProductVariationI18n();
        $variationI18n->setProductVariationId(new Identity('', 1));
        $variationI18n->setLanguageISO('ger');
        $variationI18n->setName('Produktvariation');
        $variation->setI18ns([$variationI18n]);
        $variationValue = new ProductVariationValue();
        $variationValue->setId(new Identity('', 1));
        $variationValue->setProductVariationId(new Identity('', 1));
        $variationValue->setEan('1234567890123');
        $variationValue->setExtraWeight(3);
        $variationValue->setSku('TEST');
        $variationValue->setSort(4);
        $variationValue->setStockLevel(22);
        $variationValueI18n = new ProductVariationValueI18n();
        $variationValueI18n->setProductVariationValueId(new Identity('', 1));
        $variationValueI18n->setLanguageISO('ger');
        $variationValueI18n->setName('Wert der Produktvariation');
        $variationValue->setI18ns([$variationValueI18n]);
        $variation->setValues([$variationValue]);
        $parent->setVariations([$variation]);
        
        $child = new Product();
        $child->setId(new Identity('', 2));
        $child->setStockLevel(new ProductStockLevel());
        $child->setPrices([$price]);
        $child->setMinimumOrderQuantity(1);
        $child->setIsMasterProduct(false);
        $child->setMasterProductId(new Identity('', $this->hostId));
        $child->setVariations([$variation]);
        
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
    public function testProductVariationPush(){
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $variation = new ProductVariation();
        $variation->setId(new Identity('', 1));
        $variation->setProductId(new Identity('', $this->hostId));
        $variation->setSort(64);
        $variation->setType('SELECTBOX');
        $variationI18n = new ProductVariationI18n();
        $variationI18n->setProductVariationId(new Identity('', 1));
        $variationI18n->setLanguageISO('ger');
        $variationI18n->setName('Produktvariation');
        $variation->setI18ns([$variationI18n]);
        $variationValue = new ProductVariationValue();
        $variationValue->setId(new Identity('', 1));
        $variationValue->setProductVariationId(new Identity('', 1));
        $variationValue->setEan('1234567890123');
        $variationValue->setExtraWeight(33.56);
        $variationValue->setSku('Test');
        $variationValue->setSort(64);
        $variationValue->setStockLevel(22);
        $variationValueI18n = new ProductVariationValueI18n();
        $variationValueI18n->setProductVariationValueId(new Identity('', 1));
        $variationValueI18n->setLanguageISO('ger');
        $variationValueI18n->setName('Wert der Produktvariation');
        $variationValue->setI18ns([$variationValueI18n]);
        $variation->setValues([$variationValue]);
        $product->setVariations([$variation]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductWarehousePush(){
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $warehouseInfo = new ProductWarehouseInfo();
        $warehouseInfo->setProductId(new Identity('', $this->hostId));
        $warehouseInfo->setwarehouseId(new Identity('', 1));
        $warehouseInfo->setInflowQuantity(66.0);
        $warehouseInfo->setstockLevel(5.0);
        $product->setWarehouseInfo([$warehouseInfo]);
        
        $this->push($product);
    }
}
