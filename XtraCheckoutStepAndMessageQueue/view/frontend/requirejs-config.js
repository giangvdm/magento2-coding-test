var config = {
    map: {
        '*': {
            "Magento_Checkout/js/action/place-order" : 'Acme_Checkout/js/action/place-order',
            "Magento_Checkout/js/view/payment/default" : "Acme_Checkout/js/view/payment/default"
        }
    },
    config: {
        mixins: {
            'Acme_Checkout/js/action/place-order': {
                'Acme_Checkout/js/action/place-order-mixin': true
            }
        }
    }
};
