function updateTotalCount() {
    var sum = $.map($('#groupRoles input'), function(input) {
        return parseInt($(input).val());
    }).reduce(function(one, other) {
        return one + other;
    });

    $('#counter .value').html(sum);
}

$(function() {
    $('#studentPreferences input').on('input', function() {
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

    $('#groupRoles input').on('input', function() {
        updateTotalCount();
    })

    updateTotalCount();
});
