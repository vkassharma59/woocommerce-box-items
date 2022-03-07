jQuery( document ).ready( function( $ ) {

	let totalBoxWeight = ( $('#pewc_box_weight').val() != '') ? $('#pewc_box_weight').val() : 0;
	let boxWeightUnit = ( $('#pewc_box_weight_unit').val() != '') ? $('#pewc_box_weight_unit').val() : ''; 

	if( totalBoxWeight > 0 ) {
		// Append box limit error box
		$('.pewc-group-content-wrapper').prepend('<div id="cc-box-weight-cal"><span id="cc-box-weight">'+totalBoxWeight+' '+boxWeightUnit+'</span> / <span id="cc-total-item-weight">0 '+boxWeightUnit+'</span></div>');
		$('.pewc-group-content-wrapper').prepend('<div id="box-limit-error">Box weight limit exceeed!</div>');
		$('.pewc-group-content-wrapper').find('#box-limit-error').hide();	

		$('form.cart .pewc-checkboxes-images-wrapper.child-product-wrapper').each(function() {
			let that = $(this);
			$(that).find('.pewc-checkbox-form-field').each(function() {
				$(this).on('change', function() {
					let checkBox = $(this);
					let totalBoxItemsWeight = 0;

					let selectedProductId = $(checkBox).val();
					let selectedBoxItemsWeight = $('#pewc_item_'+selectedProductId+'_weight').val();
					let selectedBoxItemQty = $(checkBox).parents('.pewc-checkbox-image-wrapper').find('.pewc-child-quantity-field').val();
					let selectedBoxTotalWeight = parseFloat(selectedBoxItemsWeight) * parseFloat(selectedBoxItemQty);

					// Itrate all checkbox
					$(that).find('.pewc-checkbox-form-field:checkbox').each(function() {
						if ( $(this).is(':checked') ) {
							let selectProductId = $(this).val();
							let boxItemsWeight = $('#pewc_item_'+selectProductId+'_weight').val();
							let boxItemQty = $(this).parents('.pewc-checkbox-image-wrapper').find('.pewc-child-quantity-field').val();
							let itemTotalWeight = parseFloat(boxItemsWeight) * parseFloat(boxItemQty);
							totalBoxItemsWeight = parseFloat(totalBoxItemsWeight) + parseFloat(itemTotalWeight);
						}
					});

					if( totalBoxItemsWeight > totalBoxWeight ) {
						totalBoxItemsWeight = parseFloat(totalBoxItemsWeight) - parseFloat(selectedBoxTotalWeight);
						let totalWeightRemaining = parseFloat(totalBoxWeight) - parseFloat(totalBoxItemsWeight);
						let remainingQty = Math.floor( parseFloat(totalWeightRemaining) / parseFloat(selectedBoxItemsWeight) );
						if( remainingQty > 0 ) {
							$(checkBox).parents('.pewc-checkbox-image-wrapper').find('.pewc-child-quantity-field').val(remainingQty);
						} else {
							$(checkBox).prop('checked', false);
							$(checkBox).parents('.pewc-checkbox-image-wrapper').find('.pewc-child-quantity-field').val(0);
						}

						$(that).find('.pewc-checkbox-form-field:checkbox').each(function() {
							if ( $(this).is(':checked') ) {
								// do nothing
							} else {
								// show error message
								$('.pewc-group-content-wrapper').find('#box-limit-error').show();
								$(this).attr('disabled', 'disabled');
								$(this).parents('.pewc-checkbox-image-wrapper').find('.pewc-child-quantity-field').attr('disabled', 'disabled');
							}
						});

					} else {

						$(that).find('.pewc-checkbox-form-field:checkbox').each(function() {
							$(this).removeAttr('disabled');
							$(this).parents('.pewc-checkbox-image-wrapper').find('.pewc-child-quantity-field').removeAttr('disabled');						
						});

						// hide error message
						$('.pewc-group-content-wrapper').find('#box-limit-error').hide();
					}

					$('#cc-total-item-weight').text(totalBoxItemsWeight.toFixed(2)+' '+boxWeightUnit);
					// console.log( 'totalBoxItemsWeight ==> ', totalBoxItemsWeight);
					// console.log( 'selectedProductId ==> ', selectedProductId);
					// console.log( 'selectedBoxItemsWeight ==> ', selectedBoxItemsWeight);
					// console.log( 'selectedBoxItemQty ==> ', selectedBoxItemQty);
					// console.log( 'selectedBoxTotalWeight ==> ', selectedBoxTotalWeight);
				});
			});

			$(that).find('.pewc-child-quantity-field').each(function() {
				$(this).on('keyup', function() {
					if( $(this).val() > 0  ) {
						$(this).parents('.pewc-checkbox-image-wrapper').find('.pewc-checkbox-form-field').trigger('change');
					}
				})
			});
		
			$(that).find('.pewc-child-quantity-field').each(function() {
				$(this).on('change', function() {
					$(this).parents('.pewc-checkbox-image-wrapper').find('.pewc-checkbox-form-field').trigger('change');
				})
			});
		});
	}
});
