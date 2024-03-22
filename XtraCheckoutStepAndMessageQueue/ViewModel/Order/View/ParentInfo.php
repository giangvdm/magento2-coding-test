<?php

namespace Acme\Checkout\ViewModel\Order\View;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;

class ParentInfo implements ArgumentInterface
{
    /**
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $quoteRepository;

    /**
     * @param CartRepositoryInterface $quoteRepository
     */
    public function __construct(CartRepositoryInterface $quoteRepository)
    {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param mixed $quoteId
     * @return CartInterface|null
     */
    public function getQuote(mixed $quoteId): ?CartInterface
    {
        try {
            return $this->quoteRepository->get($quoteId);
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }
}
