/**
* Copyright © 2015 Magento. All rights reserved.
* See COPYING.txt for license details.
*/
define([
	'uiComponent',
	'Magento_Customer/js/customer-data',
	'jquery',
	'ko',
	'Magento_Ui/js/modal/modal'
], function (Component, customerData, $, ko) {
	'use strict';
	var sidebarCart = $('[data-block="ajax_minicart"]');
	var addToCartCalls = 0;
	var sidebarInitialized = false;

	function initSidebar() {

		sidebarCart.trigger('contentUpdated');
		if (sidebarInitialized) {
			return false;
		}
		sidebarInitialized = true;

	}

	return Component.extend({
		ajaxcart: ko.observable({}),

		addedItem: ko.observable({}),
		cartSidebar: ko.observable({summary_count: false}),

		initSidebar: initSidebar,
		initialize: function () {
			var self = this;
			this._super();
			this.addedItem({success:false});
			this.cartSidebar = customerData.get('cart');
			window.addedItem = self.addedItem;
			window.ajaxcart = self.ajaxcart;
			window.cartSidebar = self.cartSidebar;
			initSidebar();
			this.cartSidebar.subscribe(function () {
				addToCartCalls--;
				sidebarInitialized = false;				
				initSidebar();
			}, this);
			$('[data-block="minicart"]').on('contentLoading', function(event) {
				addToCartCalls++;				
			});
		}

	});
});
