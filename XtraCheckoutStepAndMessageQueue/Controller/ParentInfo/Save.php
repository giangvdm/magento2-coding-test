<?php

namespace Acme\Checkout\Controller\ParentInfo;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;

class Save implements ActionInterface
{
    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    protected QuoteIdMaskFactory $quoteIdMaskFactory;

    protected CartRepositoryInterface $quoteRepository;

    /**
     * @param RequestInterface $request
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     * @param CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        RequestInterface $request,
        QuoteIdMaskFactory $quoteIdMaskFactory,
        CartRepositoryInterface $quoteRepository
    ) {
        $this->request = $request;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $post = $this->request->getPostValue();

        if ($post) {
            $cartId = $post['cartId'];
            $parentInfo = $post['parent_info'];
            $parentId = $parentInfo['id'];
            $parentName = $parentInfo['name'];
            $parentPhone = $parentInfo['phone'];
            $parentAge = $parentInfo['age'];
            $isLoggedIn = $post['is_customer'];

            if ($isLoggedIn === 'false') {
                $cartId = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id')->getQuoteId();
            }

            $quote = $this->quoteRepository->getActive($cartId);
            if (!$quote->getItemsCount()) {
                throw new NoSuchEntityException(__('Cart %1 doesn\'t contain products', $cartId));
            }

            $quote->setData('parent_id', $parentId);
            $quote->setData('parent_name', $parentName);
            $quote->setData('parent_phone', $parentPhone);
            $quote->setData('parent_age', $parentAge);
            $this->quoteRepository->save($quote);
        }

        return json_encode(['success' => true]);
    }
}
