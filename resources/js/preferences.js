$(function() {
    $('#studentPreferences input.name').on('input', function() {
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
                                $('#netIDProvider ul').append('<li>' + result.netids[i] + '</li>');
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
