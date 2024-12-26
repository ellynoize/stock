<?php

namespace App\Service;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use RetailCrm\Api\Factory\SimpleClientFactory;
use RetailCrm\Api\Model\Filter\Orders\OrderFilter;
use RetailCrm\Api\Model\Request\Orders\OrdersRequest;

class SyncService
{

    private $entityManager;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }
    public function getCrmOrders()
    {
        try {
            $client = SimpleClientFactory::createClient('https://ellynoize.retailcrm.pro', 'apiKey');

            $requestOrder = new OrdersRequest();

            $requestOrder->filter = new OrderFilter();
            $requestOrder->filter->extendedStatus = 'new';
            $orders = $client->orders->list($requestOrder)->orders;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return false;
        }

        return $orders;
    }
    public function addOrders(array $orders)
    {

        foreach ($orders as $order) {
            try {
                $dbOrder = $this->entityManager->getRepository(Order::class)->find($order['id']);

                if (!$dbOrder) {
                    $newOrder = new Order();
                    $newOrder->setId($order['id']);

                    $this->entityManager->persist($newOrder);
                    $this->entityManager->flush();
                }
            } catch (Exception $e) {
                $this->logger->error($e->getMessage());
                return false;
            }
        }
    }
}