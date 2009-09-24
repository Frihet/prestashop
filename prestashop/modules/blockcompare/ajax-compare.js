
//JS Object : update the compare by ajax actions
var ajaxCompare = {
	
	//override every button in the page in relation to the compare
	overrideButtonsInThePage : function(){
		//for every 'add' buttons...
		$('.ajax_add_to_compare_cart_button').unbind('click').click(function(){
			var idProduct =  $(this).attr('rel').replace('ajax_id_product_', '');
			ajaxCompare.add(idProduct, false, this);
			return false;
		});
		//for product page 'add' button...
		$('body#product .ajax_add_to_compare_cart_button').unbind('click').click(function(){
			ajaxCompare.add($('#product_page_product_id').val(), true, null);
			return false;
		});
	
		//for 'delete' buttons in the compare block...
		$('.ajax_compare_block_remove_link').unbind('click').click(function(){
			// Removing product from the compare
			ajaxCompare.remove(
                                parseInt($(this).parent().parent().attr('id').replace('compare_block_product_', '')));
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
			url: baseDir + 'modules/blockcompare/compare.php',
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
	add : function(idProduct, addedFromProductPage, callerElement){
		//send the ajax request to the server
		$.ajax({
			type: 'GET',
			url: baseDir + 'modules/blockcompare/compare.php',
			async: true,
			cache: false,
			dataType : "json",
			data: 'add&ajax=true&id_product=' + idProduct + '&token=' + static_token,
			success: function(jsonData)
			{
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
							}
				});
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("TECHNICAL ERROR: unable to add the product.\n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
			}
		});
	},
	
	//remove a product from the compare via ajax
	remove : function(idProduct){
		//send the ajax request to the server
		$.ajax({
			type: 'GET',
			url: baseDir + 'modules/blockcompare/compare.php',
			async: true,
			cache: false,
			dataType : "json",
			data: 'delete' + '&id_product=' + idProduct + '&token=' + static_token + '&ajax=true',
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
				//retrieve idProduct from the displayed product in the block compare
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


	//display the products witch are in json data but not already displayed
	displayNewProducts : function(jsonData) {
		//add every new products
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
				if($('#compare_block dt#compare_block_product_'+ domIdProduct ).length == 0)
				{
					var productId = parseInt(this.id);
					var content =  '<dt class="hidden" id="compare_block_product_' + domIdProduct + '">';
						  content += '<span class="compare_block_product_description"><a href="' + this.link + '" title="' + this.name + '">' + this.name + '</a></span>';
						  content += '<span class="remove_link"><a class="ajax_compare_block_remove_link" href="' + baseDir + 'compare.php?delete&amp;id_product=' + productId + '&amp;token=' + static_token + '"> </a></span>';
						  content += '</dt>';

					$('#compare_block dl.products').append(content);
				}
				$('#compare_block dl.products .hidden').slideDown('slow').removeClass('hidden');
			}
		});
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
		ajaxCompare.hideOldProducts(jsonData);
		ajaxCompare.displayNewProducts(jsonData);
		
		//update 'first' and 'last' item classes
		$('#compare_block dl.products dt').removeClass('first_item').removeClass('last_item').removeClass('item');
		$('#compare_block dl.products dt:first').addClass('first_item');
		$('#compare_block dl.products dt:not(:first,:last)').addClass('item');
		$('#compare_block dl.products dt:last').addClass('last_item');
		
		//reset the onlick events in relation to the compare block (it allow to bind the onclick event to the new 'delete' buttons added)
		ajaxCompare.overrideButtonsInThePage();
	},
	
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
