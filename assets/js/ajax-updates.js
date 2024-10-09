jQuery(document).ready(function($) {
    function loadLogs() {
        $.ajax({
            url: rvAjax.ajaxurl,
            type: 'post',
            data: {
                action: 'rv_get_logs'
            },
            success: function(response) {
                $('#visitor-logs-table tbody').html(response);
            }
        });
    }

    // Her 5 saniyede bir logları güncelle
    setInterval(loadLogs, 5000);
    loadLogs();  // Sayfa yüklendiğinde hemen çağır
});
