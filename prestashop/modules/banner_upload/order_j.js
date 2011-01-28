$(document).ready(function()
{	
	$(function()
	{
		$("#content_images_dd ul").sortable({ opacity: 0.6, cursor: 'move', update: function()
		{
			var block = $("#block_name").attr("class");
			var order = $(this).sortable("serialize") + '&action=updateRecordsListings&block='+block; 
			$.post("../modules/banner_upload/order_update.php", order, function(theResponse){
				$("#dd_message").html(theResponse);
			}); 															 
		}								  
		});
	});

});	