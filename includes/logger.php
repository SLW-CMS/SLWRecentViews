<?php

function rv_log_visitor_activity() {
    // Oturumun başlatıldığından emin olalım
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Ziyaretçinin IP ve görüntülenen sayfa URI'si
    $ip = $_SERVER['REMOTE_ADDR'];
    $page = $_SERVER['REQUEST_URI'];

    // Ziyaretin yapıldığı saat (sadece saat)
    $visit_time = date("H:i:s");

    // Rastgele isim listesi
    $names = [
        'Ahmet', 'Ali', 'Feyza', 'Ayşe', 'Mehmet', 'Zeynep', 'Burak', 'Fatma', 'Can', 'Emine',
        'Hakan', 'Cem', 'Ebru', 'Deniz', 'Gül', 'Serkan', 'Merve', 'Mustafa', 'Hüseyin', 'Sibel',
        'Yasemin', 'Özgür', 'Murat', 'Sevda', 'Gökhan', 'Büşra', 'Selim', 'Derya', 'Aslı', 'Tolga',
        'Hande', 'İsmail', 'Gamze', 'Ferhat', 'Esra', 'Kadir', 'Ece', 'Barış', 'Rabia', 'Onur',
        'Selin', 'Uğur', 'Melis', 'Tuba', 'Okan', 'İpek', 'Levent', 'Furkan', 'Ceyda', 'Bora'
    ];

    // Şehir bilgisi oturumdan alınacak, yoksa rastgele bir şehir seçilecek
    if (isset($_SESSION['visitor_city'])) {
        $random_location = sanitize_text_field($_SESSION['visitor_city']);
    } else {
        $locations = [
            // Türkiye'den şehirler
            'İstanbul', 'Ankara', 'İzmir', 'Antalya', 'Bursa', 'Adana', 'Gaziantep', 'Konya', 'Kayseri', 'Mersin',
            'Diyarbakır', 'Samsun', 'Eskişehir', 'Kocaeli', 'Malatya', 'Denizli', 'Şanlıurfa', 'Balıkesir', 'Kahramanmaraş', 'Van',
            'Aydın', 'Sakarya', 'Tekirdağ', 'Muğla', 'Manisa', 'Hatay', 'Ordu', 'Trabzon', 'Elazığ', 'Sivas',
            'Batman', 'Afyonkarahisar', 'Zonguldak', 'Düzce', 'Kırklareli', 'Çorum', 'Amasya', 'Isparta', 'Edirne', 'Bolu',
            'Kastamonu', 'Rize', 'Tokat', 'Osmaniye', 'Nevşehir', 'Yozgat', 'Uşak', 'Çanakkale', 'Karabük', 'Burdur',
            'Bartın', 'Karaman', 'Ağrı', 'Mardin', 'Şırnak', 'Iğdır', 'Bingöl', 'Kilis', 'Kars', 'Ardahan',
            'Bayburt', 'Hakkari', 'Niğde', 'Sinop', 'Artvin', 'Bilecik', 'Tunceli', 'Gümüşhane', 'Çankırı', 'Aksaray',
            'Erzincan', 'Erzurum', 'Kırıkkale', 'Kırşehir', 'Muğla', 'Bolu', 'Giresun', 'Trabzon', 'Van', 'Adıyaman',

            // Dünyadan şehirler
            'New York', 'Los Angeles', 'Chicago', 'Miami', 'San Francisco', 'Washington', 'Toronto', 'Vancouver', 'Mexico City', 'Buenos Aires',
            'Rio de Janeiro', 'Sao Paulo', 'Lima', 'Bogota', 'Caracas', 'Santiago', 'London', 'Paris', 'Berlin', 'Madrid',
            'Rome', 'Amsterdam', 'Brussels', 'Vienna', 'Zurich', 'Athens', 'Moscow', 'Saint Petersburg', 'Stockholm', 'Oslo',
            'Copenhagen', 'Helsinki', 'Reykjavik', 'Dublin', 'Warsaw', 'Prague', 'Budapest', 'Belgrade', 'Sofia', 'Bucharest',
            'Istanbul', 'Dubai', 'Doha', 'Riyadh', 'Abu Dhabi', 'Kuwait City', 'Jerusalem', 'Cairo', 'Johannesburg', 'Lagos',
            'Nairobi', 'Casablanca', 'Tunis', 'Algiers', 'Addis Ababa', 'Tokyo', 'Seoul', 'Beijing', 'Shanghai', 'Hong Kong',
            'Taipei', 'Bangkok', 'Singapore', 'Kuala Lumpur', 'Jakarta', 'Manila', 'Sydney', 'Melbourne', 'Brisbane', 'Auckland',
            'Delhi', 'Mumbai', 'Bangalore', 'Kolkata', 'Chennai', 'Islamabad', 'Karachi', 'Lahore', 'Dhaka', 'Kathmandu',
            'Tehran', 'Baghdad', 'Damascus', 'Beirut', 'Amman', 'Ankara', 'Izmir', 'Tripoli', 'Hanoi', 'Phnom Penh',
            'Vientiane', 'Yangon', 'Kabul', 'Tashkent', 'Bishkek', 'Dushanbe', 'Astana', 'Nur-Sultan', 'Ulaanbaatar', 'Havana'
        ];
        $random_location = $locations[array_rand($locations)];
    }

    // Rastgele mizahi mesajlar listesi
    $messages = [
    "%s, Kral %s'dan gelip %s konusunu okuyorsun. Hadi bakalımm :) :)",
    "Kimleri görüyoruuum, %s, %s'dan gelip %s konusunu okuyorsun. ne işine yarayacaksa artık :)",
    "Ooo hoşgeldin %s, %s'dan gelip %s bunu mu okuyorsun. bak izliyorum seni :)",
    "%s, Gelmiş hoşgelmiş %s'dan ne getirmiş :) %s yazıyı dikkatli oku.",
    "%s, %s'dan gelip %s hakkında bilgi alıyorsun. Meraklı biri misin yoksa? :)",
    "Şiişşş %s, Gelmişsin %s'dan %s okurken dikkat et, yaramazlık yaparken yakaladım seni",
    "%s sen sen etrafına bakma %s güzel şehir'de %s konuyla alakası ne ?",
    "%s, %s'dan %s bakıyorsun. Kapat o pencereyi yoksa gözlerin yorulacak!",
    "%s, saat %s olmuş hala %s'da gezinip duruyorsun. Zamanın mı bol senin?",
    "%s, %s'dan %s cihazıyla gelip %s okuyor. Teknolojiye ayak uydurmuşsun!",
    "%s, %s'dan bu kadar yolu %s için mi geldin? :)",
    "Hayırdır %s, %s'dan %s konusuna göz dikmişsin :)",
    "Bu kadar meraklı olma %s, %s'dan %s mı öğrenmek istiyorsun?",
    "%s, %s'dan %s konusunu okuyorsun. Hadi bakalım :)",
    "%s, %s'dan %s konusuna dalmışsın. Kaptırma kendini :)",
    "Ooo %s, %s'dan gelip %s okuma işine bakıyorsun. Sana mı kaldı :)",
    "%s, %s'dan %s'ye geldin ama bu kadar kolay olmaz ki :)",
    "%s, %s'dan gelip %s ile uğraşıyorsun. Biraz mola ver :)",
    "Vay vay, %s, %s'dan %s için mi buradasın? Hayırlısı :)",
    "%s, %s'dan %s hakkında derinlemesine düşünüyorsun sanırım :)",
    "Şimdi de %s geldi %s'dan %s'yi okumaya. Meraklı bir kitleyiz :)",
    "%s, %s'dan gelip %s'da ne buldun bakalım :)",
    "%s, %s'dan gelmişsin. %s seni bu kadar mı merak ettiriyor? :)",
    "%s, %s'dan %s ile haşır neşir olmuşsun. Yavaş ol bakalım :)",
    "%s, %s'dan %s'yi inceliyorsun. Yeterince bilgi aldın mı? :)",
    "%s, %s'dan %s ile vakit geçiriyorsun. İyi eğlenceler :)",
    "Vay be %s, %s'dan %s'yi merak ettin demek :)",
    "%s, %s'dan buraya kadar %s için gelmişsin, aferin sana! :)",
    "Dur bakalım %s, %s'dan gelip %s'da ne bulacaksın :)",
    "%s, %s'dan %s'ye daldın gitmişsin. Dikkat et kaybolma :)",
    "%s, %s'dan %s için buradasın, tebrikler cesaretine! :)",
    "%s, %s'dan %s konusunda araştırma yapıyorsun, bilim insanı mısın? :)",
    "Vay %s, %s'dan %s'da takılıyorsun. Bakalım ne bulacaksın :)",
    "Hey %s, %s'dan gelip %s konusunda derinlere dalmışsın :)",
    "%s, %s'dan gelip %s hakkında düşünüyorsun. Yaratıcı birisin :)",
    "%s, %s'dan gelip %s'yi okuyorsun, bakalım ne öğreneceksin :)",
    "Merhaba %s, %s'dan %s'ye göz atıyorsun. Bakalım ne çıkacak :)",
    "%s, %s'dan %s'yi merak ediyorsun, akıllıca :)",
    "%s, %s'dan gelip %s'da takılmışsın. İyi eğlenceler :)",
    "%s, %s'dan gelip %s ile ilgileniyorsun. Cesaretine hayranım! :)",
    "%s, %s'dan %s hakkında daha ne öğrenebilirsin ki? :)",
    "Hadi bakalım %s, %s'dan %s'da ne bulacaksın görelim :)",
    "Hoş geldin %s, %s'dan gelip %s'yi merak ettin. Cesur bir adım :)",
    "%s, %s'dan gelip %s hakkında ne düşünüyorsun? :)",
    "%s, %s'dan %s konusunda kafa yoruyorsun, çok iyi :)",
    "%s, %s'dan %s için mi buraya kadar geldin, vay be! :)",
    "%s, %s'dan %s konusuna baya meraklısın ha :)",
    "%s, %s'dan %s'da geziniyorsun. Umarım aradığını bulursun :)"
    ];

    // Rastgele bir isim ve mizahi mesaj seçimi
    $random_name = $names[array_rand($names)];
    $random_message = $messages[array_rand($messages)];

    // Sayfa başlığı ve özetini alma
    $page_title = get_the_title();
    $page_content = get_post_field('post_content', get_the_ID());
    $page_summary = wp_trim_words($page_content, 15, '...'); // Sayfa özetini oluştur

    // İsim ve başlık üzerine hover özelliği eklenmiş yapı
    $name_with_ip = "<span title='IP Adresi: $ip'>$random_name</span>";
    $title_with_summary = "<a href='$page' title='$page_summary'>$page_title</a>";

    // Mizahi mesajı hazırlama (isim ve başlık hover ile)
    $log_entry = sprintf("$visit_time - $random_message", $name_with_ip, $random_location, $title_with_summary);

    // Log dosyasına yazma
    $file = plugin_dir_path(__FILE__) . '../logs/actions.log';
    $fp = fopen($file, 'a'); // Dosya açma ve yazma için hazırla

    if (flock($fp, LOCK_EX)) {  // Dosya kilitleme
        fwrite($fp, $log_entry . "\n");
        flock($fp, LOCK_UN);    // Kilidi kaldır
    }

    fclose($fp);  // Dosyayı kapatma

    // Log dosyasını sınırla (100 kayıttan fazlasını tutma)
    $logs = file($file);
    if (count($logs) > 100) {
        $logs = array_slice($logs, -100);  // Son 100 kaydı al
        file_put_contents($file, implode("", $logs), LOCK_EX);
    }
}
