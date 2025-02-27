jQuery(document).ready(function($) {
    $('.up-cache-flush-button a').on('click', function(e) {
        e.preventDefault();

        var actionUrl = $(this).attr('href');

        $.ajax({
            url: actionUrl,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                } else {
                    alert('Erreur : ' + response.data.message);
                }
            },
            error: function() {
                alert('Une erreur est survenue lors du vidage du cache.');
            }
        });
    });
});
