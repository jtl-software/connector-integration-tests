<?php

namespace Jtl\Connector\IntegrationTests\Integration;

use Jtl\Connector\IntegrationTests\ConnectorTestCase;
use DateTime;
use jtl\Connector\Model\DeliveryNote;
use jtl\Connector\Model\DeliveryNoteItem;
use jtl\Connector\Model\DeliveryNoteItemInfo;
use jtl\Connector\Model\DeliveryNoteTrackingList;
use jtl\Connector\Model\Identity;

abstract class DeliveryNoteTest extends ConnectorTestCase
{
    public function testDeliveryNoteBasicPush()
    {
        $deliveryNote = new DeliveryNote();
        
        $deliveryNote->setCustomerOrderId(new Identity('', 1))
            ->setId(new Identity('', 1))
            ->setCreationDate(new DateTime())
            ->setIsFulfillment(true)
            ->setNote('');
        
        $this->pushCoreModels([$deliveryNote], true);
    }
    
    public function testDeliveryNoteItemsPush()
    {
        $deliveryNote = new DeliveryNote();
        
        $item = new DeliveryNoteItem();
        $item->setCustomerOrderItemId(new Identity('', 1))
            ->setDeliveryNoteId(new Identity('', 1))
            ->setProductId(new Identity('', 1))
            ->setId(new Identity('', 1))
            ->setQuantity(0.0);
        
        $info = new DeliveryNoteItemInfo();
        $info->setBatch('')
            ->setBestBefore(new DateTime())
            ->setQuantity(0.0)
            ->setWarehouseId(0);
        
        $item->addInfo($info);
        $deliveryNote->addItem($item);
        
        $this->pushCoreModels([$deliveryNote], true);
    }
    
    public function testDeliveryNoteTrackingListsPush()
    {
        $deliveryNote = new DeliveryNote();
        
        $trackingList = new DeliveryNoteTrackingList();
        $trackingList->setName('')
            ->addCode('');
        $deliveryNote->addTrackingList($trackingList);
        
        $this->pushCoreModels([$deliveryNote], true);
    }
}
