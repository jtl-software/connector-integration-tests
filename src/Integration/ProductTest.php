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
        $productI18n->setDeliveryStatus('');
        $productI18n->setDescription('Beschreibung');
        $productI18n->setLanguageISO('de');
        $productI18n->setMeasurementUnitName('');
        $productI18n->setMetaDescription('metaDescription');
        $productI18n->setMetaKeywords('metaKeywords');
        $productI18n->setName('testartikel');
        $productI18n->setShortDescription('Kurze Beschreibung');
        $productI18n->setTitleTag('Titel Tag');
        $productI18n->setUnitName('');
        $productI18n->setUrlPath('');
        $product->setI18ns([$productI18n]);
        
        $price = new ProductPrice();
        $price->setCustomerGroupId(new Identity('', 1));
        $price->setCustomerId(new Identity('', 1));
        $price->setId(new Identity('', 1));
        $price->setProductId(new Identity('', $this->hostId));
        $priceItem = new ProductPriceItem();
        $priceItem->setProductPriceId(new Identity('', 1));
        $priceItem->setNetPrice(0.0);
        $priceItem->setQuantity(0);
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
        $product->setAsin('');
        $product->setAvailableFrom(new DateTime('2019-08-21T00:00:00+0200'));
        $product->setBasePriceDivisor(0.0);
        $product->setBasePriceFactor(0.0);
        $product->setBasePriceQuantity(0.0);
        $product->setBasePriceUnitCode('');
        $product->setBasePriceUnitName('');
        $product->setConsiderBasePrice(false);
        $product->setConsiderStock(false);
        $product->setConsiderVariationStock(false);
        $product->setCreationDate(new DateTime());
        $product->setEan('');
        $product->setEpid('');
        $product->setHazardIdNumber('');
        $product->setHeight(0.0);
        $product->setIsActive(true);
        $product->setIsBatch(false);
        $product->setIsBestBefore(false);
        $product->setIsbn('978-1692748777');
        $product->setIsDivisible(false);
        $product->setIsMasterProduct(false);
        $product->setIsNewProduct(false);
        $product->setIsSerialNumber(false);
        $product->setIsTopProduct(false);
        $product->setKeywords('');
        $product->setLength(0.0);
        $product->setManufacturerNumber('');
        $product->setMeasurementQuantity(0.0);
        $product->setMeasurementUnitCode('');
        $product->setMinBestBeforeDate(new DateTime());
        $product->setMinimumOrderQuantity(1);
        $product->setMinimumQuantity(0);
        $product->setModified(new DateTime());
        $product->setNewReleaseDate(new DateTime());
        $product->setNextAvailableInflowDate(new DateTime());
        $product->setNextAvailableInflowQuantity(0.0);
        $product->setNote('');
        $product->setOriginCountry('');
        $product->setPackagingQuantity(0.0);
        $product->setPermitNegativeStock(false);
        $product->setProductWeight(0.0);
        $product->setPurchasePrice(0.0);
        $product->setRecommendedRetailPrice(0.0);
        $product->setSerialNumber('');
        $product->setShippingWeight(0.0);
        $product->setSku('');
        $product->setSort(0);
        $product->setSupplierDeliveryTime(0);
        $product->setSupplierStockLevel(0.0);
        $product->setTaric('');
        $product->setUnNumber('');
        $product->setUpc('');
        $product->setVat(0.0);
        $product->setWidth(0.0);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductManufacturerPush(){
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $manufacturer = new Manufacturer();
        $manufacturer->setId(new Identity('', 1));
        $manufacturer->setName('Hersteller Krause');
        $manufacturer->setSort(0);
        $manufacturer->setUrlPath('');
        $manufacturer->setWebsiteUrl('');
        $manufacturerI18n = new ManufacturerI18n();
        $manufacturerI18n->setManufacturerId(new Identity('', 1));
        $manufacturerI18n->setDescription('Beschreibung von Bauer Weskamp');
        $manufacturerI18n->setLanguageISO('de');
        $manufacturerI18n->setMetaDescription('');
        $manufacturerI18n->setMetaKeywords('');
        $manufacturerI18n->setTitleTag('');
        $manufacturer->setI18ns([$manufacturerI18n]);
        $product->setManufacturer($manufacturer);
        $manufacturerPush = $this->pushCoreModels([$manufacturer], false);
        $this->push($product);
        $this->deleteModel('manufacturer', $manufacturerPush[0]->getId()->getEndpoint(), 1);
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
        $stockLevel->setStockLevel(0.0);
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
        $attribute->setIsCustomProperty(false);
        $attribute->setIsTranslated(false);
        $attributeI18n = new ProductAttrI18n();
        $attributeI18n->setProductAttrId(new Identity('', 1));
        $attributeI18n->setLanguageISO('');
        $attributeI18n->setName('');
        $attributeI18n->setValue('');
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
    public function testProductChecksumPush(){
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $checksum = new Checksum();
        $checksum->setForeignKey(new Identity('', 1));
        $checksum->setEndpoint('');
        $checksum->setHasChanged(false);
        $checksum->setHost('');
        $checksum->setType(0);
        $product->setChecksums([$checksum]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductConfigGroupPush(){
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $configGroup = new ProductConfigGroup();
        $configGroup->setConfigGroupId(new Identity('', 1));
        $configGroup->setProductId(new Identity('', $this->hostId));
        $configGroup->setSort(0);
        $product->setConfigGroups([$configGroup]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductCustomerGroupPackagingQuantityPush(){
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $packagingQuantity = new CustomerGroupPackagingQuantity();
        $packagingQuantity->setCustomerGroupId(new Identity('', 1));
        $packagingQuantity->setProductId(new Identity('', $this->hostId));
        $packagingQuantity->setMinimumOrderQuantity(1);
        $packagingQuantity->setPackagingQuantity(0.0);
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
        $fileUpload->setFileType('');
        $fileUpload->setIsRequired(false);
        $fileUploadI18n = new FileUploadI18n();
        $fileUploadI18n->setDescription('');
        $fileUploadI18n->setFileUploadId(0);
        $fileUploadI18n->setLanguageISO('');
        $fileUploadI18n->setName('');
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
        $mediaFile->setMediaFileCategory('');
        $mediaFile->setPath('');
        $mediaFile->setSort(0);
        $mediaFile->setType('');
        $mediaFile->setUrl('');
        $mediaFileAttribute = new ProductMediaFileAttr();
        $mediaFileAttribute->setProductMediaFileId(new Identity('', 1));
        $mediaFileAttributeI18n = new ProductMediaFileAttrI18n();
        $mediaFileAttributeI18n->setLanguageISO('');
        $mediaFileAttributeI18n->setName('');
        $mediaFileAttributeI18n->setValue('');
        $mediaFileAttribute->setI18ns([$mediaFileAttributeI18n]);
        $mediaFile->setAttributes([$mediaFileAttribute]);
        $mediaFileI18n = new ProductMediaFileI18n();
        $mediaFileI18n->setProductMediaFileId(new Identity('', 1));
        $mediaFileI18n->setDescription('');
        $mediaFileI18n->setLanguageISO('');
        $mediaFileI18n->setName('');
        $mediaFile->setI18ns([$mediaFileI18n]);
        $product->setMediaFiles([$mediaFile]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductPartsListPush(){
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $partsList= new ProductPartsList();
        $partsList->setId(new Identity('', 1));
        $partsList->setProductId(new Identity('', $this->hostId));
        $partsList->setQuantity(0.0);
        $product->setPartsLists([$partsList]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductSpecialPricePush(){
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $specialPrice = new ProductSpecialPrice();
        $specialPrice->setId(new Identity('', 1));
        $specialPrice->setProductId(new Identity('', $this->hostId));
        $specialPrice->setActiveFromDate(new DateTime());
        $specialPrice->setActiveUntilDate(new DateTime());
        $specialPrice->setConsiderDateLimit(false);
        $specialPrice->setConsiderStockLimit(false);
        $specialPrice->setIsActive(false);
        $specialPrice->setStockLimit(0);
        $specialPriceItem = new ProductSpecialPriceItem();
        $specialPriceItem->setCustomerGroupId(new Identity('', 1));
        $specialPriceItem->setProductSpecialPriceId(new Identity('', 1));
        $specialPriceItem->setPriceNet(0.0);
        $specialPrice->setItems([$specialPriceItem]);
        $product->setSpecialPrices([$specialPrice]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductSpecificPush(){
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $specific = new ProductSpecific();
        $specific->setId(new Identity('', 1));
        $specific->setProductId(new Identity('', $this->hostId));
        $specific->setSpecificValueId(new Identity('', 1));
        $product->setSpecifics([$specific]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductVarCombinationPush(){
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $varCombination = new ProductVarCombination();
        $varCombination->setProductId(new Identity('', $this->hostId));
        $varCombination->setProductVariationId(new Identity('', 1));
        $varCombination->setProductVariationValueId(new Identity('', 1));
        $product->setVarCombinations([$varCombination]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductVariationPush(){
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $variation = new ProductVariation();
        $variation->setId(new Identity('', 1));
        $variation->setProductId(new Identity('', $this->hostId));
        $variation->setSort(0);
        $variation->setType('SELECTBOX');
        $variationI18n = new ProductVariationI18n();
        $variationI18n->setProductVariationId(new Identity('', 1));
        $variationI18n->setLanguageISO('de');
        $variationI18n->setName('Produktvariation');
        $variation->setI18ns([$variationI18n]);
        $variationValue = new ProductVariationValue();
        $variationValue->setId(new Identity('', 1));
        $variationValue->setProductVariationId(new Identity('', 1));
        $variationValue->setEan('');
        $variationValue->setExtraWeight(0.0);
        $variationValue->setSku('');
        $variationValue->setSort(0);
        $variationValue->setStockLevel(22);
        $variationValueI18n = new ProductVariationValueI18n();
        $variationValueI18n->setProductVariationValueId(new Identity('', 1));
        $variationValueI18n->setLanguageISO('de');
        $variationValueI18n->setName('Wert der Produktvariation');
        $variationValue->setI18ns([$variationValueI18n]);
        /*$variationInvisibility = new ProductVariationInvisibility();
        $variationInvisibility->setCustomerGroupId(new Identity('', 1));
        $variationInvisibility->setProductVariationId(new Identity('', 1));
        $variation->setInvisibilities([$variationInvisibility]);
        $variationValueExtraCharge = new ProductVariationValueExtraCharge();
        $variationValueExtraCharge->setCustomerGroupId(new Identity('', 1));
        $variationValueExtraCharge->setProductVariationValueId(new Identity('', 1));
        $variationValueExtraCharge->setExtraChargeNet(1.2);
        $variationValue->setExtraCharges([$variationValueExtraCharge]);
        $variationValueInvisibility = new ProductVariationValueInvisibility();
        $variationValueInvisibility->setCustomerGroupId(new Identity('', 1));
        $variationValueInvisibility->setProductVariationValueId(new Identity('', 1));
        $variationValue->setInvisibilities([$variationValueInvisibility]);*/
        $variation->setValues([$variationValue]);
        $product->setVariations([$variation]);
        
        $this->push($product);
    }
    
    /**
     * @throws LinkerException
     * @throws \ReflectionException
     */
    public function testProductWarehousePush(){
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        $product = new Product();
        $product->setStockLevel(new ProductStockLevel());
        $product->addPrice(new ProductPrice());
        $warehouseInfo = new ProductWarehouseInfo();
        $warehouseInfo->setProductId(new Identity('', $this->hostId));
        $warehouseInfo->setwarehouseId(new Identity('', 1));
        $warehouseInfo->setInflowQuantity(0.0);
        $warehouseInfo->setstockLevel(0.0);
        $product->setWarehouseInfo([$warehouseInfo]);
        
        $this->push($product);
    }
}
