<?php

namespace Jtl\Connector\IntegrationTests\Integration;

use Jtl\Connector\IntegrationTests\ConnectorTestCase;
use DateTime;
use jtl\Connector\Model\CustomerOrder;
use jtl\Connector\Model\CustomerOrderBillingAddress;
use jtl\Connector\Model\CustomerOrderItem;
use jtl\Connector\Model\CustomerOrderItemVariation;
use jtl\Connector\Model\CustomerOrderPaymentInfo;
use jtl\Connector\Model\CustomerOrderShippingAddress;
use jtl\Connector\Model\Identity;
use jtl\Connector\Model\CustomerOrderAttr;

abstract class CustomerOrderTest extends ConnectorTestCase
{
    public function testCustomerOrderBasicPush()
    {
        $customerOrder = new CustomerOrder();
        $customerOrder->setCustomerId(new Identity('', 1))
            ->setId(new Identity('', 1))
            ->setCarrierName('')
            ->setCreationDate(new DateTime())
            ->setCurrencyIso('')
            ->setEstimatedDeliveryDate(new DateTime())
            ->setLanguageISO('ger')
            ->setNote('')
            ->setOrderNumber('')
            ->setPaymentDate(new DateTime)
            ->setPaymentModuleCode('')
            ->setPaymentStatus('')
            ->setPui('')
            ->setShippingDate(new DateTime())
            ->setShippingInfo('')
            ->setShippingMethodId(new Identity('', 1))
            ->setShippingMethodName('')
            ->setStatus('')
            ->setTotalSum(0.0)
            ->setTotalSumGross(0.0);
        
        $this->pushCoreModels([$customerOrder], true);
    }
    
    public function testCustomerOrderBillingAddressPush()
    {
        $customerOrder = new CustomerOrder();
        
        $billingAddress = new CustomerOrderBillingAddress();
        $billingAddress->setCustomerId(new Identity('', 1))
            ->setId(new Identity('', 1))
            ->setCity('')
            ->setCompany('')
            ->setCountryIso('')
            ->setDeliveryInstruction('')
            ->setEMail('')
            ->setExtraAddressLine('')
            ->setFax('')
            ->setFirstName('')
            ->setLastName('')
            ->setMobile('')
            ->setPhone('')
            ->setSalutation('')
            ->setState('')
            ->setStreet('')
            ->setTitle('')
            ->setVatNumber('')
            ->setZipCode('');
        $customerOrder->setBillingAddress($billingAddress);
        
        $this->pushCoreModels([$customerOrder], true);
    }
    
    public function testCustomerOrderPaymentInfoPush()
    {
        $customerOrder = new CustomerOrder();
        
        $paymentInfo = new CustomerOrderPaymentInfo();
        $paymentInfo->setCustomerOrderId(new Identity('', 1))
            ->setId(new Identity('', 1))
            ->setAccountHolder('')
            ->setAccountNumber('')
            ->setBankCode('')
            ->setBankName('')
            ->setBic('')
            ->setCreditCardExpiration('')
            ->setCreditCardHolder('')
            ->setCreditCardNumber('')
            ->setCreditCardType('')
            ->setCreditCardVerificationNumber('')
            ->setIban('');
        $customerOrder->setPaymentInfo($paymentInfo);
        
        $this->pushCoreModels([$customerOrder], true);
    }
    
    public function testCustomerOrderShippingAddressPush()
    {
        $customerOrder = new CustomerOrder();
        
        $shippingAddress = new CustomerOrderShippingAddress();
        $shippingAddress->setCustomerId(new Identity('', 1))
            ->setId(new Identity('', 1))
            ->setCity('')
            ->setCompany('')
            ->setCountryIso('')
            ->setDeliveryInstruction('')
            ->setEMail('')
            ->setExtraAddressLine('')
            ->setFax('')
            ->setFirstName('')
            ->setLastName('')
            ->setMobile('')
            ->setPhone('')
            ->setSalutation('')
            ->setState('')
            ->setStreet('')
            ->setTitle('')
            ->setZipCode('');
        $customerOrder->setShippingAddress($shippingAddress);
        
        $this->pushCoreModels([$customerOrder], true);
    }
    
    public function testCustomerOrderAttributesPush()
    {
        $customerOrder = new CustomerOrder();
        
        $attribute = new CustomerOrderAttr();
        $attribute->setCustomerOrderId(new Identity('', 1))
            ->setId(new Identity('', 1))
            ->setKey('')
            ->setValue('');
        $customerOrder->addAttribute($attribute);
        
        $this->pushCoreModels([$customerOrder], true);
    }
    
    public function testCustomerOrderItemsPush()
    {
        $customerOrder = new CustomerOrder();
        
        $item = new CustomerOrderItem();
        $item->setConfigItemId(new Identity('', 1))
            ->setCustomerOrderId(new Identity('', 1))
            ->setId(new Identity('', 1))
            ->setProductId(new Identity('', 1))
            ->setName('')
            ->setPrice(0.0)
            ->setPriceGross(0.0)
            ->setQuantity(0.0)
            ->setSku('')
            ->setType('')
            ->setNote('')
            ->setUnique('')
            ->setVat(0.0);
        
        $itemVariation = new CustomerOrderItemVariation();
        $itemVariation->setCustomerOrderItemId(new Identity('', 1))
            ->setId(new Identity('', 1))
            ->setProductVariationId(new Identity('', 1))
            ->setProductVariationValueId(new Identity('', 1))
            ->setFreeField('')
            ->setProductVariationName('')
            ->setSurcharge(0.0)
            ->setValueName('');
        
        $item->addVariation($itemVariation);
        
        $customerOrder->addItem($item);
        
        $this->pushCoreModels([$customerOrder], true);
    }
}
