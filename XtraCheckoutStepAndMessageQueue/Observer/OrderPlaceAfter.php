<?php

namespace Acme\Checkout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Quote\Api\CartRepositoryInterface;

class OrderPlaceAfter implements ObserverInterface
{
    /**
     * @var Curl
     */
    private Curl $curl;

    /**
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $quoteRepository;

    /**
     * @param Curl $curl
     * @param CartRepositoryInterface $quoteRepository
     */
    public function __construct(Curl $curl, CartRepositoryInterface $quoteRepository)
    {
        $this->curl = $curl;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        try {
            $quote = $this->quoteRepository->get($order->getId());
            // Call to API
            $this->curl->post(
                "https://plv2rrrtyb.execute-api.ap-southeast-1.amazonaws.com/Development/parent",
                [
                    'identification' => $quote->getParentId(),
                    'name' => $quote->getParentName(),
                    'phone' => $quote->getParentPhone(),
                    'age' => $quote->getParentAge()
                ]
            );
        } catch (NoSuchEntityException $e) {

        }
    }
}
