define([
    'jquery',
    'Magento_Ui/js/form/form'
], function($, Component) {
    'use strict';

    return Component.extend({
        initialize: function () {
            this._super();
            return this;
        },

        /**
         * Form submit handler
         *
         * This method can have any name.
         */
        onSubmit: function() {
            // trigger form validation
            this.source.set('params.invalid', false);
            this.source.trigger('parentInfoForm.data.validate');

            // verify that form data is valid
            if (!this.source.get('params.invalid')) {
                // data is retrieved from data provider by value of the customScope property
                var formData = this.source.get('parentInfoForm');
                // do something with form data
                console.dir(formData);
            }

            // Place order
            $('.payment-method._active .primary button.checkout').trigger('click');
        }
    });
});
