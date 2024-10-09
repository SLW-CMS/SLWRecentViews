<?php

function rv_get_logs() {
    $file = plugin_dir_path(__FILE__) . '../logs/actions.log';
    $logs = array_slice(array_reverse(file($file)), 0, 20); // Son 20 işlem

    foreach ($logs as $log) {
        echo "<tr><td>$log</td></tr>";
    }

    wp_die(); // AJAX işlemi tamamlanınca durdurmak için
}

// Ziyaretçi işlemleri için tabloyu shortcode olarak ekleme
function rv_display_visitor_logs() {
    echo '<table id="visitor-logs-table">';
    echo '<tbody>';
    // Tablo boş olarak yüklenecek, AJAX ile güncellenecek
    echo '</tbody>';
    echo '</table>';
}
add_shortcode('rv_logs', 'rv_display_visitor_logs');
