$(function() {
    var currentInput = null;

    $('#studentPreferences input').on('input', function() {
	currentInput = $(this);
        var value = $(this).val();

        if (value !== '' && value.length >= 3) {
            $.ajax({
    			url: siteUrl + 'search/byNetId',
    			type: 'POST',
    			context: document.body,
    			dataType: 'json',
    			data: {
                    value : value
                },
    			success: function(result) {
                    $('#netIDProvider ul').empty();
                    if (result.response === 'success') {
                        var netids = result.netids;

                        if (netids.length > 0) {
                            for (var i = 0; i < result.netids.length; i++) {
                                $('#netIDProvider ul').append('<li class="list-group-item">' + result.netids[i] + '</li>');
                            	$('#netIDProvider ul li').on('click', function() {
					if (currentInput != null) {
						currentInput.val($(this).html());
						$('#netIDProvider ul').html('No results.');
					}
				});
			    }
                        }
                        else {
                            $('#netIDProvider ul').html('No results.');
                        }
                    }
                }
    		});
        }
        else {
            $('#netIDProvider ul').html('No suggestions.');
        }
    });
});
