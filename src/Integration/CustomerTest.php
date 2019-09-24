<?php
namespace Jtl\Connector\IntegrationTests\Integration;

use Jtl\Connector\IntegrationTests\ConnectorTestCase;
use DateTime;
use jtl\Connector\Model\Customer;
use jtl\Connector\Model\Identity;
use jtl\Connector\Model\CustomerAttr;


abstract class CustomerTest extends ConnectorTestCase
{
    public function testCustomerBasicPush()
    {
        $customer = (new Customer())
            ->setCustomerGroupId(new Identity('', 1))
            ->setId(new Identity('', 1))
            ->setAccountCredit(0.0)
            ->setBirthday(new DateTime())
            ->setCity('')
            ->setCompany('')
            ->setCountryIso('')
            ->setCreationDate(new DateTime())
            ->setCustomerNumber('')
            ->setDeliveryInstruction('')
            ->setDiscount(0.0)
            ->setEMail('')
            ->setExtraAddressLine('')
            ->setFax('')
            ->setFirstName('')
            ->setHasCustomerAccount(true)
            ->setHasNewsletterSubscription(true)
            ->setIsActive(true)
            ->setLanguageISO('ger')
            ->setLastName('')
            ->setMobile('')
            ->setNote('')
            ->setOrigin('')
            ->setPhone('')
            ->setSalutation('')
            ->setState('')
            ->setStreet('')
            ->setTitle('')
            ->setVatNumber('')
            ->setWebsiteUrl('')
            ->setZipCode('');
        
        $this->pushCoreModels([$customer], true);
    }
    
    public function testCustomerAttributePush()
    {
        $customer = new Customer();
        $attribute = (new CustomerAttr())
            ->setCustomerId(new Identity('', 1))
            ->setKey('')
            ->setValue('');
        $customer->addAttribute($attribute);
        
        $this->pushCoreModels([$customer], true);
    }
}
