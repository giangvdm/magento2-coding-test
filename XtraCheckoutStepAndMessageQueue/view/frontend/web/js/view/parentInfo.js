define([
    'jquery',
    'ko',
    'uiComponent',
    'underscore',
    'Magento_Checkout/js/model/step-navigator'
], function ($, ko, Component, _, stepNavigator) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Acme_Checkout/parentInfo'
        },

        // add here your logic to display step,
        isVisible: ko.observable(true),

        /**
         * @returns {*}
         */
        initialize: function () {
            this._super();

            // register your step
            stepNavigator.registerStep(
                // step code will be used as step content id in the component template
                'parent_info',
                // step alias
                null,
                // step title value
                'Confirm Parent Info',
                // observable property with logic when display step or hide step
                this.isVisible,

                _.bind(this.navigate, this),

                /**
                 * sort order value
                 * 'sort order value' < 10: step displays before shipping step;
                 * 10 < 'sort order value' < 20 : step displays between shipping and payment step
                 * 'sort order value' > 20 : step displays after payment step
                 */
                30
            );

            return this;
        },

        /**
         * The navigate() method is responsible for navigation between checkout steps
         * during checkout. You can add custom logic, for example some conditions
         * for switching to your custom step
         * When the user navigates to the custom step via url anchor or back button we_must show step manually here
         */
        navigate: function () {
            this.isVisible(true);
        },

        back: function() {
            stepNavigator.navigateTo('shipping');
        },

        backToBilling: function() {
            $('.payment-method._active .primary button.checkout').removeClass("disabled");
            stepNavigator.navigateTo('payment');
        }
    });
});
