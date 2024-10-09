jQuery(document).ready(function($) {
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            // Geolocation API yoksa rastgele şehir seç
            setRandomCity();
        }
    }

    function showPosition(position) {
        let lat = position.coords.latitude;
        let lon = position.coords.longitude;

        // Harici bir API kullanarak enlem/boylam bilgilerini şehre çevirebiliriz (örneğin OpenWeather veya başka bir servis)
        $.getJSON(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lon}&localityLanguage=en`, function(data) {
            if (data && data.city) {
                // Şehir bilgisi elde edilirse
                setCity(data.city);
            } else {
                // Şehir bilgisi yoksa rastgele şehir seç
                setRandomCity();
            }
        });
    }

    function showError(error) {
        // Hata durumunda rastgele şehir kullan
        setRandomCity();
    }

    function setCity(city) {
        // PHP tarafına şehir bilgisini göndermek için
        $.ajax({
            url: rvAjax.ajaxurl,
            type: 'post',
            data: {
                action: 'rv_set_city',
                city: city
            }
        });
    }

    function setRandomCity() {
      let cities = [
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

        let randomCity = cities[Math.floor(Math.random() * cities.length)];
        setCity(randomCity);
    }

    // Sayfa yüklendiğinde konum almayı dene
    getLocation();
});
