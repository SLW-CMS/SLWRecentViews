<?php
/*
Plugin Name: SLW Recent Views
Description: Ziyaretçilerin yaptığı işlemleri kaydeder ve ekrana gösterir.
Version: 1.0
Author: [Ali Çömez / Slaweally]
Author URL: https://rootali.net/
*/

if (!defined('ABSPATH')) {
    exit;
}

// PHP oturumu başlat
function rv_start_session() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'rv_start_session');

// Ziyaretçi işlemlerini loglama
include_once plugin_dir_path(__FILE__) . 'includes/logger.php';

// Ekrana logları yazdırma
include_once plugin_dir_path(__FILE__) . 'includes/display.php';

// CSS ve JS yükleme
function rv_enqueue_scripts() {
    wp_enqueue_style('rv-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    wp_enqueue_script('rv-ajax', plugin_dir_url(__FILE__) . 'assets/js/ajax-updates.js', array('jquery'), null, true);
    wp_localize_script('rv-ajax', 'rvAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'rv_enqueue_scripts');

// Ziyaretçi IP'si ve işlemleri kaydetme
add_action('wp', 'rv_log_visitor_activity');

// AJAX ile canlı güncellemeler
add_action('wp_ajax_nopriv_rv_get_logs', 'rv_get_logs');
add_action('wp_ajax_rv_get_logs', 'rv_get_logs');

// AJAX ile şehir bilgisini almak
add_action('wp_ajax_nopriv_rv_set_city', 'rv_set_city');
add_action('wp_ajax_rv_set_city', 'rv_set_city');

function rv_set_city() {
    if (isset($_POST['city'])) {
        // Şehir bilgisini PHP oturumuna kaydet
        $_SESSION['visitor_city'] = sanitize_text_field($_POST['city']);
    }
    wp_die(); // AJAX isteğini sonlandır
}

// Yönetici menüsüne Recent Views sayfası ekleme
function rv_add_admin_menu() {
    add_menu_page(
        'Recent Views',        // Sayfa başlığı
        'Recent Views',        // Menü adı
        'manage_options',      // Yetki seviyesi
        'rv-recent-views',     // Slug
        'rv_info_page',        // Sayfa içeriğini gösterecek fonksiyon
        'dashicons-visibility',// Menü ikonu (WordPress Dashicons kullanılarak)
        6                      // Menüdeki sıralama
    );
}
add_action('admin_menu', 'rv_add_admin_menu');

// Admin panelde eklenti bilgilerini gösteren sayfa
function rv_info_page() {
    ?>
        <div class="wrap">
    <h1>SLW Recent Views Eklentisi</h1>
    <p><strong>SLW Recent Views</strong> eklentisi, sitenize gelen ziyaretçilerin yaptığı işlemleri detaylı bir şekilde kaydederek, web sitenizin arka planında neler olduğunu anlamanıza yardımcı olur. Eklenti, kullanıcı dostu bir arayüzle birlikte gelir ve herhangi bir yapılandırmaya gerek duymadan otomatik olarak çalışır.</p>
    
    <h2>Özellikler</h2>
    <ul>
        <li><strong>Ziyaretçi Kaydı:</strong> Ziyaretçilerin IP adresi, şehir bilgisi, ve hangi sayfayı ziyaret ettikleri kaydedilir.</li>
        <li><strong>Şehir ve Ziyaretçi Bilgileri:</strong> Rastgele şehir isimleri ve mizahi ziyaretçi mesajlarıyla kullanıcı deneyimi eğlenceli hale getirilir.</li>
        <li><strong>Yapılan İşlemler:</strong> Ziyaretçilerin sayfada yaptıkları işlemler anlık olarak kaydedilir.</li>
        <li><strong>Otomatik Log Yönetimi:</strong> Eklenti, log dosyalarını otomatik olarak yönetir. Eski kayıtlar silinerek en güncel 100 işlem saklanır.</li>
        <li><strong>Mobil Uyum:</strong> Hem masaüstü hem de mobil cihazlarda sorunsuz çalışır. Mobil uyumluluk sayesinde küçük ekranlarda da şık bir görünüm sağlar.</li>
        <li><strong>Kullanıcı Bilgileri Hover İle Görüntüleme:</strong> Ziyaretçilerin IP adresi ve sayfa bilgileri gibi detaylar, kullanıcı isminin üzerine gelindiğinde baloncuk şeklinde görüntülenir.</li>
        <li><strong>Mizahi Mesajlar:</strong> Ziyaretçiler için rastgele mizahi mesajlar üretilir, bu da onları eğlenceli bir şekilde karşılar.</li>
    </ul>

    <h2>Kullanım</h2>
    <p>Eklenti kurulumdan sonra otomatik olarak çalışmaya başlar. Herhangi bir ek ayara ihtiyaç duymazsınız. WordPress admin paneldeki "Recent Views" menüsüne tıklayarak ziyaretçi hareketlerini görebilirsiniz.</p>
    
    <h2>Güncelleme & Geliştirme</h2>
    <p>Eklenti, sitenizin performansını artırmak için düzenli olarak güncellenir. Ziyaretçi verileri 50 kayıttan 100 kayda çıkartılmıştır ve ziyaretçiler arasında tekrarlayan kayıtlar filtrelenmiştir.</p>
    
    <p><strong>SLW Recent Views</strong> eklentisi ile sitenize gelen ziyaretçilerin hareketlerini kolayca izleyebilir, bu verileri daha iyi bir kullanıcı deneyimi sunmak için kullanabilirsiniz!</p>
    
    <p>Geliştirici: <a href="https://rootali.net/" target="_blank">Ali Çömez</a></p>
</div>
    <?php
}

// Oturumda şehir bilgisi var mı kontrolü
function rv_get_visitor_city() {
    return isset($_SESSION['visitor_city']) ? $_SESSION['visitor_city'] : 'Bilinmeyen Şehir';
}
