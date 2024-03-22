define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_CheckoutAgreements/js/model/agreements-assigner',
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/url-builder',
    'mage/url',
    'Magento_Checkout/js/model/error-processor',
    'uiRegistry'
], function (
    $,
    wrapper,
    agreementsAssigner,
    quote,
    customer,
    urlBuilder,
    urlFormatter,
    errorProcessor,
    registry
) {
    'use strict';

    return function (placeOrderAction) {

        return wrapper.wrap(placeOrderAction, function (originalAction, paymentData, messageContainer) {
            agreementsAssigner(paymentData);
            var isCustomer = customer.isLoggedIn();
            var quoteId = quote.getQuoteId();

            var url = urlFormatter.build('checkout/parentinfo/save');

            var parentId =  $('[name="parentInfoForm.id"] input').val(),
                parentName = $('[name="parentInfoForm.name"] input').val(),
                parentPhone = $('[name="parentInfoForm.phone"] input').val(),
                parentAge = $('[name="parentInfoForm.age"] input').val();

            if (parentId) {

                var payload = {
                    'cartId': quoteId,
                    'parent_info': {
                        'id': parentId,
                        'name': parentName,
                        'phone': parentPhone,
                        'age': parentAge
                    },
                    'is_customer': isCustomer
                };

                if (!payload.parent_info) {
                    return true;
                }

                var result = true;

                $.ajax({
                    url: url,
                    data: payload,
                    dataType: 'text',
                    type: 'POST',
                }).done(
                    function (response) {
                        result = true;
                    }
                ).fail(
                    function (response) {
                        result = false;
                        errorProcessor.process(response);
                    }
                );
            }

            return originalAction(paymentData, messageContainer);
        });
    };
});
