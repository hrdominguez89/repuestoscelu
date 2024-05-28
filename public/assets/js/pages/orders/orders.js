"use strict";

const orderStatusPending = 1;
const orderStatusConfirmed = 2;
const orderStatusCanceled = 3;
const shippingTypeAddress = 1;
const shippingTypePickup = 2;

$(document).ready(() => {
    listenOrderConfirm();
});

const listenOrderConfirm = () => {
    let orderConfirmSelect = $('#order_confirm_status');
    if (orderConfirmSelect.length) {
        if(orderConfirmSelect.val()== orderStatusConfirmed){
            enableShippingType(true);
        }
        orderConfirmSelect.on('change', (event) => {
            if (event.target.value == orderStatusConfirmed) {
                enableShippingType(true);
            } else {
                enableShippingType(false);
            }
        });
    }
};

const enableShippingType = (status) => {
    if (status) {
        $('#shippingTypeDiv').show();
        $('#order_confirm_shipping_type').removeAttr('disabled');
        $('#order_confirm_shipping_type').attr('required', 'required');
        let shippingTypeSelect = $('#order_confirm_shipping_type');
        if (shippingTypeSelect.length) {

            if(shippingTypeSelect.val()){
                enableTrackingData(true);
            }
            shippingTypeSelect.on('change', (event) => {
                if (event.target.value == shippingTypeAddress) {
                    enableTrackingData(true);
                } else {
                    enableTrackingData(false);
                }
            });
        }
    } else {
        $('#shippingTypeDiv').hide();
        $('#order_confirm_shipping_type').val(null);
        $('#order_confirm_shipping_type').removeAttr('required');
        $('#order_confirm_shipping_type').attr('disabled', 'disabled');
        enableTrackingData(false);
    }
}

const enableTrackingData = (status) => {
    if (status) {
        $('#trackingDataDiv').show();
        $('#order_confirm_tracking_name').removeAttr('disabled');
        $('#order_confirm_tracking_name').attr('required', 'required');
        
        $('#order_confirm_tracking_number').removeAttr('disabled');
        $('#order_confirm_tracking_number').attr('required', 'required');
    } else {
        $('#trackingDataDiv').hide();
        $('#order_confirm_tracking_name').val(null);
        $('#order_confirm_tracking_number').val(null);

        $('#order_confirm_tracking_name').removeAttr('required');
        $('#order_confirm_tracking_name').attr('disabled', 'disabled');
        
        $('#order_confirm_tracking_number').removeAttr('required');
        $('#order_confirm_tracking_number').attr('disabled', 'disabled');
    }
}
