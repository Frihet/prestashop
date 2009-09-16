
//JS Object : update the compare by ajax actions
var ajaxCompare = {
	
	//override every button in the page in relation to the compare
	overrideButtonsInThePage : function(){
		//for every 'add' buttons...
		$('.ajax_add_to_compare_button').unbind('click').click(function(){
			var idProduct =  $(this).attr('rel').replace('ajax_id_product_', '');
			ajaxCompare.add(idProduct, null, false, this);
			return false;
		});
		//for product page 'add' button...
		$('body#product p#add_to_compare input').unbind('click').click(function(){
			ajaxCompare.add( $('#product_page_product_id').val(), $('#idCombination').val(), true, null, $('#quantity_wanted').val(), null);
			return false;
		});
	
		//for 'delete' buttons in the compare block...
		$('.ajax_compare_block_remove_link').unbind('click').click(function(){
			// Customized product management
			var customizationId = 0;
			var productId = 0;
			var productAttributeId = 0;
			if ($($(this).parent().parent()).attr('name') == 'customization')
				// Reverse two levels: a >> div >> li
				var customizableProductDiv = $($(this).parent().parent()).find("div[@id^=deleteCustomizableProduct_]");
			else
				var customizableProductDiv = $($(this).parent()).find("div[@id^=deleteCustomizableProduct_]");
			if (customizableProductDiv && $(customizableProductDiv).length)
			{
				$(customizableProductDiv).each(function(){
					var ids = $(this).attr('id').split('_');
					if (typeof(ids[1]) != 'undefined')
					{
						customizationId = parseInt(ids[1]);
						productId = parseInt(ids[2]);
						if (typeof(ids[3]) != 'undefined')
							productAttributeId = parseInt(ids[3]);
						return false;
					}
				});
			}

			// Common product management
			if (!customizationId)
			{
				//retrieve idProduct and idCombination from the displayed product in the block compare
				var firstCut = $(this).parent().parent().attr('id').replace('compare_block_product_', '');
				firstCut = firstCut.replace('deleteCustomizableProduct_', '');
				ids = firstCut.split('_');		
				productId = parseInt(ids[0]);
				if (typeof(ids[1]) != 'undefined')
					productAttributeId = parseInt(ids[1]);
			}

			// Removing product from the compare
			ajaxCompare.remove(productId, productAttributeId, customizationId);
			return false;
		});
	},
	
	// try to expand the compare
	expand : function(){
		if ($('#compare_block #compare_block_list').hasClass('collapsed'))
		{
			$('#compare_block #compare_block_summary').slideUp(200, function(){
				$(this).addClass('collapsed').removeClass('expanded');
				$('#compare_block #compare_block_list').slideDown({
					duration: 600,
					complete: function(){$(this).addClass('expanded').removeClass('collapsed');}
				});
			});
			// toogle the button expand/collapse button
			$('#compare_block h4 span#block_compare_expand').fadeOut('slow', function(){
				$('#compare_block h4 span#block_compare_collapse').fadeIn('fast');
			});
			
			// save the expand statut in the user cookie
			$.ajax({
				type: 'GET',
				url: baseDir + 'modules/blockcompare/blockcompare-set-collapse.php',
				async: true,
				data: 'ajax_blockcompare_display=expand' + '&rand=' + new Date().getTime()
			});
			
			
		}
	},
	
	// compare to fix display when using back and previous browsers buttons
	refresh : function(){
		//send the ajax request to the server
		$.ajax({
			type: 'GET',
			url: baseDir + 'compare.php',
			async: true,
			cache: false,
			dataType : "json",
			data: 'ajax=true&token=' + static_token,
			success: function(jsonData)
			{
				ajaxCompare.updateCompare(jsonData)
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				//alert("TECHNICAL ERROR: unable to refresh the compare.\n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
			}
		});
	},
	
	// try to collapse the compare
	collapse : function(){
		
		if ($('#compare_block #compare_block_list').hasClass('expanded'))
		{
			$('#compare_block #compare_block_list').slideUp('slow', function(){
				$(this).addClass('collapsed').removeClass('expanded');
				$('#compare_block #compare_block_summary').slideDown(700, function(){
					$(this).addClass('expanded').removeClass('collapsed');
				});
			});
			$('#compare_block h4 span#block_compare_collapse').fadeOut('slow', function(){
				$('#compare_block h4 span#block_compare_expand').fadeIn('fast');
			});
			
			// save the expand statut in the user cookie
			$.ajax({
				type: 'GET',
				url: baseDir + 'modules/blockcompare/blockcompare-set-collapse.php',
				async: true,
				data: 'ajax_blockcompare_display=collapse' + '&rand=' + new Date().getTime()
			});
		}
	},
	
	// add a product in the compare via ajax
	add : function(idProduct, idCombination, addedFromProductPage, callerElement, quantity, whishlist){
		if (addedFromProductPage && !checkCustomizations())
		{
			alert(fieldRequired);
			return ;
		}
		
		//disabled the button when adding to do not double add if user double click
		if (addedFromProductPage)
		{
			$('body#product p#add_to_compare input').attr('disabled', 'disabled').removeClass('exclusive').addClass('exclusive_disabled');
			$('.filled').removeClass('filled');
		}
		else
			$('.ajax_add_to_compare_button').attr('disabled', 'disabled');
		
		//send the ajax request to the server
		$.ajax({
			type: 'GET',
			url: baseDir + 'compare.php',
			async: true,
			cache: false,
			dataType : "json",
			data: 'add&ajax=true&qty=' + ( (quantity && quantity != null) ? quantity : '1') + '&id_product=' + idProduct + '&token=' + static_token + ( (parseInt(idCombination) && idCombination != null) ? '&ipa=' + parseInt(idCombination): ''),
			success: function(jsonData)
			{
				// add appliance to whishlist module
				if (whishlist && !jsonData.errors) 
					WishlistAddProductCompare(whishlist[0], idProduct, idCombination, whishlist[1]);
				//apply 'transfert' effect
				var elementToTransfert = null;
				if (callerElement && callerElement != null)
					$(callerElement).parents().each( function() {
						if ($(this).is('.ajax_block_product')) elementToTransfert = $(this);
					});
				else
					elementToTransfert = $(addedFromProductPage ? 'div#image-block' : ('.ajax_block_product_id_' + idProduct) );
				elementToTransfert.TransferTo({
							to: $('#compare_block').get(0),
							className:'transferProduct',
							duration: 800,
							complete: function () {
								ajaxCompare.updateCompare(jsonData);
								//reactive the button when adding has finished
								if (addedFromProductPage)
									$('body#product p#add_to_compare input').removeAttr('disabled').addClass('exclusive').removeClass('exclusive_disabled');
								else
									$('.ajax_add_to_compare_button').removeAttr('disabled');
							}
				});
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("TECHNICAL ERROR: unable to add the product.\n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
				//reactive the button when adding has finished
				if (addedFromProductPage)
					$('body#product p#add_to_compare input').removeAttr('disabled').addClass('exclusive').removeClass('exclusive_disabled');
				else
					$('.ajax_add_to_compare_button').removeAttr('disabled');
			}
		});
	},
	
	//remove a product from the compare via ajax
	remove : function(idProduct, idCombination, customizationId){
		//send the ajax request to the server
		$.ajax({
			type: 'GET',
			url: baseDir + 'compare.php',
			async: true,
			cache: false,
			dataType : "json",
			data: 'delete' + '&id_product=' + idProduct + '&ipa=' + ((idCombination != null && parseInt(idCombination)) ? idCombination : '') + ((customizationId && customizationId != null) ? '&id_customization=' + customizationId : '') + '&token=' + static_token + '&ajax=true',
			success: function(jsonData)	{ ajaxCompare.updateCompare(jsonData) },
			error: function() {alert('ERROR: unable to delete the product');}
		});
	},
	
	//hide the products displayed in the page but no more in the json data
	hideOldProducts : function(jsonData) {
		//delete an eventually removed product of the displayed compare (only if compare is not empty!)
		if($('#compare_block #compare_block_list dl.products').length > 0)
		{
			var removedProductId = null;
			var removedProductData = null;
			var removedProductDomId = null;
			//look for a product to delete...
			$('#compare_block_list dl.products dt').each(function(){
				//retrieve idProduct and idCombination from the displayed product in the block compare
				var domIdProduct = $(this).attr('id');
				var firstCut =  domIdProduct.replace('compare_block_product_', '');
				var ids = firstCut.split('_');

				//try to know if the current product is still in the new list
				var stayInTheCompare = false;
				for (aProduct in jsonData.products)
				{
					//we've called the variable aProduct because IE6 bug if this variable is called product
					//if product has attributes
					if (jsonData.products[aProduct]['id'] == ids[0] && (!ids[1] || jsonData.products[aProduct]['idCombination'] == ids[1]))
					{
						stayInTheCompare = true;
						// update the product customization display (when the product is still in the compare)
						ajaxCompare.hideOldProductCustomizations(jsonData.products[aProduct], domIdProduct);
					}
				}
				//remove product if it's no more in the compare
				if(!stayInTheCompare)
				{
					removedProductId = $(this).attr('id');
					//return false; // Regarding that the customer can only remove products one by one, we break the loop
				}
			});
			
			//if there is a removed product, delete it from the displayed block compare
			if (removedProductId != null)
			{
				var firstCut =  removedProductId.replace('compare_block_product_', '');
				var ids = firstCut.split('_');

				$('#'+removedProductId).addClass('strike').fadeTo('slow', 0, function(){
					$(this).slideUp('slow', function(){
						$(this).remove();
						//if the compare is now empty, show the 'no product in the compare' message
						if($('#compare_block dl.products dt').length == 0)
						{
							$('p#compare_block_no_products:hidden').slideDown('fast');
							$('div#compare_block dl.products').remove();
						}
					});
				});
				$('dd#compare_block_combination_of_' + ids[0] + (ids[1] ? '_'+ids[1] : '') ).fadeTo('fast', 0, function(){
					$(this).slideUp('fast', function(){
						$(this).remove();
					});
				});
			}
			
		}
	},

	hideOldProductCustomizations : function (product, domIdProduct)
	{
		var customizationList = $('#compare_block #compare_block_list ul#customization_' + product['id'] + '_' + product['idCombination']);
		if(customizationList.length > 0)
		{
			$(customizationList).find("li").each(function(){
				$(this).find("div").each(function() {
					var customizationDiv = $(this).attr('id');
					var tmp = customizationDiv.replace('deleteCustomizableProduct_', '');
					var ids = tmp.split('_');
					if ((parseInt(product.idCombination) == parseInt(ids[2])) && !ajaxCompare.doesCustomizationStillExist(product, ids[0]))
						$('#' + customizationDiv).parent().addClass('strike').fadeTo('slow', 0, function(){
							$(this).slideUp('slow');
							$(this).remove();
						});;
				});
			});
		}
		var removeLinks = $('#compare_block_product_' + domIdProduct).find('a.ajax_compare_block_remove_link');
		if (!product.hasCustomizedDatas && !removeLinks.length)
			$('#' + domIdProduct + ' span.remove_link').html('<a class="ajax_compare_block_remove_link" href="' + baseDir + 'compare.php?delete&amp;id_product=' + product['id'] + '&amp;ipa=' + product['idCombination'] + '&amp;token=' + static_token + '" title="' + removingLinkText + '"> </a>');
	},

	doesCustomizationStillExist : function (product, customizationId)
	{
		var exists = false;

		$(product.customizedDatas).each(function() {
			if (this.customizationId == customizationId)
			{
				exists = true;
				// This return does not mean that we found nothing but simply break the loop
				return false;
			}
		});
		return (exists);
	},

	//refresh display of vouchers (needed for vouchers in % of the total)
	refreshVouchers : function (jsonData) {
		$(jsonData.discounts).each(function(){
			//fix ie6 bug (one more item 'undefined' in IE6)
			if (this.id != undefined && $('#bloc_compare_voucher_' + this.id).length == 1)
			{
					$('#bloc_compare_voucher_' + this.id + ' td.price').text(this.price);
			}
		});
		if (jsonData.discounts.length == 0)
			$('#vouchers').remove();
	},

	// Update product quantity
	updateProductQuantity : function (product, quantity) {
		$('dt#compare_block_product_' + product.id + (product.idCombination ? '_' + product.idCombination : '') + ' .quantity').fadeTo('fast', 0, function() {
			$(this).text(quantity);
			$(this).fadeTo('fast', 1, function(){
				$(this).fadeTo('fast', 0, function(){
					$(this).fadeTo('fast', 1, function(){
						$(this).fadeTo('fast', 0, function(){
							$(this).fadeTo('fast', 1);
						});
					});
				});
			});
		});
	},


	//display the products witch are in json data but not already displayed
	displayNewProducts : function(jsonData) {

		//add every new products or update displaying of every updated products
		$(jsonData.products).each(function(){
			//fix ie6 bug (one more item 'undefined' in IE6)
			if (this.id != undefined)
			{
				//create a container for listing the products and hide the 'no product in the compare' message (only if the compare was empty)
				if ($('div#compare_block dl.products').length == 0)
					$('p#compare_block_no_products:visible').fadeTo('fast', 0, function(){
						$(this).slideUp('fast').fadeTo(0, 1);
					}).before('<dl class="products"></dl>');
			
				//if product is not in the displayed compare, add a new product's line
				var domIdProduct = this.id + (this.idCombination ? '_' + this.idCombination : '');
				var domIdProductAttribute = this.id + '_' + (this.idCombination ? this.idCombination : '0');
				if($('#compare_block dt#compare_block_product_'+ domIdProduct ).length == 0)
				{
					var productId = parseInt(this.id);
					var productAttributeId = (this.hasAttributes ? parseInt(this.attributes) : 0);
					var content =  '<dt class="hidden" id="compare_block_product_' + domIdProduct + '">';
						 content += '<span class="quantity-formated"><span class="quantity">' + this.quantity + '</span>x</span>';
						  content += '<a href="' + this.link + '" title="' + this.name + '">' + this.name + '</a>';
						  content += '<span class="remove_link"><a class="ajax_compare_block_remove_link" href="' + baseDir + 'compare.php?delete&amp;id_product=' + productId + '&amp;token=' + static_token + (this.hasAttributes ? '&amp;ipa=' + parseInt(this.attributes) : '') + '"> </a></span>';
						  content += '<span class="price">' + this.priceByLine + '</span>';
						  content += '</dt>';
					if (this.hasAttributes)
						  content += '<dd id="compare_block_combination_of_' + domIdProduct + '" class="hidden"><a href="' + this.link + '" title="' + this.name + '">' + this.attributes + '</a>';
					if (this.hasCustomizedDatas)
						content += ajaxCompare.displayNewCustomizedDatas(this);
					if (this.hasAttributes) content += '</dd>';

					$('#compare_block dl.products').append(content);
				}
				//else update the product's line
				else{
					var jsonProduct = this;
					if($('dt#compare_block_product_' + domIdProduct + ' .quantity').text() != jsonProduct.quantity)
					{
						// Usual product
						$('dt#compare_block_product_' + domIdProduct + ' .price').text(jsonProduct.priceByLine);
						ajaxCompare.updateProductQuantity(jsonProduct, jsonProduct.quantity);

						// Customized product
						if (jsonProduct.hasCustomizedDatas)
						{
							customizationFormatedDatas = ajaxCompare.displayNewCustomizedDatas(jsonProduct);
							if (!$('#compare_block ul#customization_' + domIdProductAttribute).length)
							{
								if (jsonProduct.hasAttributes)
									$('#compare_block dd#compare_block_combination_of_' + domIdProduct).append(customizationFormatedDatas);
								else
									$('#compare_block dl.products').append(customizationFormatedDatas);
							}
							else
								$('#compare_block ul#customization_' + domIdProductAttribute).append(customizationFormatedDatas);
						}
					}
				}
				$('#compare_block dl.products .hidden').slideDown('slow').removeClass('hidden');

			var removeLinks = $('#compare_block_product_' + domIdProduct).find('a.ajax_compare_block_remove_link');
			if (this.hasCustomizedDatas && removeLinks.length)
				$(removeLinks).each(function() {
					$(this).remove();
				});
			}
		});
	},

	displayNewCustomizedDatas : function(product)
	{
		var content = '';
		var productId = parseInt(product.id);
		var productAttributeId = typeof(product.idCombination) == 'undefined' ? 0 : parseInt(product.idCombination);
		var hasAlreadyCustomizations = $('#compare_block ul#customization_' + productId + '_' + productAttributeId).length;

		if (!hasAlreadyCustomizations)
		{
			if (!product.hasAttributes) content += '<dd id="compare_block_combination_of_' + productId + '" class="hidden">';
			content += '<ul class="compare_block_customizations" id="customization_' + productId + '_' + productAttributeId + '">';
		}

		$(product.customizedDatas).each(function(){
			var done = 0;
			customizationId = parseInt(this.customizationId);
			productAttributeId = typeof(product.idCombination) == 'undefined' ? 0 : parseInt(product.idCombination);
			// If the customization is already displayed on the compare, no update's needed
			if($('#compare_block').find("div[@id^=deleteCustomizableProduct_" + customizationId + "_]").length)
				return ('');
			content += '<li name="customization"><div class="deleteCustomizableProduct" id="deleteCustomizableProduct_' + customizationId + '_' + productId + '_' + (productAttributeId ?  productAttributeId : '0') + '"><a class="ajax_compare_block_remove_link" href="' + baseDir + 'compare.php?delete&amp;id_product=' + productId + '&amp;ipa=' + productAttributeId + '&amp;id_customization=' + customizationId + '&amp;token=' + static_token + '"> </a></div><span class="quantity-formated"><span class="quantity">' + parseInt(this.quantity) + '</span>x</span>';

			// Give to the customized product the first textfield value as name
			$(this.datas).each(function(){
				if (this['type'] == CUSTOMIZE_TEXTFIELD)
				{
					$(this.datas).each(function(){
						if (this['index'] == 0)
						{
							content += this.truncatedValue;
							done = 1;
							return false;
						}
					})
				}
			});

			// If the customized product did not have any textfield, it will have the customizationId as name
			if (!done)
				content += customizationIdMessage + customizationId;
			if (!hasAlreadyCustomizations) content += '</li>';

			// Field cleaning
			if (customizationId)
			{
				$('#uploadable_files li div.customizationUploadBrowse img').remove();
				$('#text_fields li input').attr('value', '');
			}
		});

		if (!hasAlreadyCustomizations)
		{
			content += '</ul>';
			if (!product.hasAttributes) content += '</dd>';
		}
		return (content);
	},


	//genarally update the display of the compare
	updateCompare : function(jsonData) {
		//user errors display
		if (jsonData.hasError)
		{
			var errors = '';
			for(error in jsonData.errors)
				//IE6 bug fix
				if(error != 'indexOf')
					errors += jsonData.errors[error] + "\n";
			alert(errors);
		}
		ajaxCompare.expand();
		ajaxCompare.updateCompareEverywhere(jsonData);
		ajaxCompare.hideOldProducts(jsonData);
		ajaxCompare.displayNewProducts(jsonData);
		ajaxCompare.refreshVouchers(jsonData);
		
		//update 'first' and 'last' item classes
		$('#compare_block dl.products dt').removeClass('first_item').removeClass('last_item').removeClass('item');
		$('#compare_block dl.products dt:first').addClass('first_item');
		$('#compare_block dl.products dt:not(:first,:last)').addClass('item');
		$('#compare_block dl.products dt:last').addClass('last_item');
		
		//reset the onlick events in relation to the compare block (it allow to bind the onclick event to the new 'delete' buttons added)
		ajaxCompare.overrideButtonsInThePage();
	},
	
	//update general compare informations everywere in the page
	updateCompareEverywhere : function(jsonData) {
		$('.ajax_compare_total').text(jsonData.productTotal);
		$('.ajax_compare_shipping_cost').text(jsonData.shippingCost);
		$('.compare_block_wrapping_cost').text(jsonData.wrappingCost);
		$('.ajax_block_compare_total').text(jsonData.total);
		if(jsonData.nbTotalProducts > 0)
		{
			$('.ajax_compare_no_product:visible').hide();
			$('.ajax_compare_quantity').text(jsonData.nbTotalProducts);

			$('.ajax_compare_quantity:hidden').fadeIn('slow');
			$('.ajax_compare_total:hidden').fadeIn('slow');
			
			if(jsonData.nbTotalProducts > 1)
			{
				$('.ajax_compare_product_txt:visible').hide();
				$('.ajax_compare_product_txt_s:hidden').show();
			}
			else
			{
				$('.ajax_compare_product_txt:hidden').fadeIn('slow');
				$('.ajax_compare_product_txt_s:visible').fadeOut('slow');
			}
		}
		else
		{
			$('.ajax_compare_quantity:visible, .ajax_compare_product_txt_s:visible, .ajax_compare_product_txt:visible, .ajax_compare_total:visible').fadeOut('slow', function(){
				$('.ajax_compare_no_product:hidden').fadeIn('slow');
			});
		}
	}
}

//when document is loaded...
$(document).ready(function(){

	// expand/collapse management
	$('#block_compare_collapse').click(function(){
			ajaxCompare.collapse();
	});
	$('#block_compare_expand').click(function(){
			ajaxCompare.expand();
	});
	ajaxCompare.overrideButtonsInThePage();
	ajaxCompare.refresh();
});
