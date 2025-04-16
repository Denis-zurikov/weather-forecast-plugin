<?php
// Функция для получения данных о погоде через API
function weather_forecast_get_data($location, $api_key) {
    $transient_key = 'weather_data_' . md5($location);
    
    // Пробуем получить данные из кэша
    $data = get_transient($transient_key);
    
    if (false === $data) {
        // Если данных нет в кэше, делаем запрос к API
        $api_url = "http://api.openweathermap.org/data/2.5/weather?q=" . urlencode($location) . "&appid=" . $api_key . "&units=metric&lang=ru";
        
        $response = wp_remote_get($api_url);
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        // Сохраняем в кэш на 30 минут
        if ($data && isset($data['cod']) && $data['cod'] == 200) {
            set_transient($transient_key, $data, 30 * MINUTE_IN_SECONDS);
        }
    }
    
    return $data;
}

// Функция для получения иконки погоды
function weather_forecast_get_icon($weather_id) {
    $icons = array(
        '01d' => 'fas fa-sun',           // ясно (день)
        '01n' => 'fas fa-moon',          // ясно (ночь)
        '02d' => 'fas fa-cloud-sun',     // мало облаков (день)
        '02n' => 'fas fa-cloud-moon',    // мало облаков (ночь)
        '03d' => 'fas fa-cloud',         // облачно
        '03n' => 'fas fa-cloud',
        '04d' => 'fas fa-cloud',         // пасмурно
        '04n' => 'fas fa-cloud',
        '09d' => 'fas fa-cloud-rain',    // дождь
        '09n' => 'fas fa-cloud-rain',
        '10d' => 'fas fa-cloud-sun-rain',// дождь с прояснениями (день)
        '10n' => 'fas fa-cloud-moon-rain',// дождь с прояснениями (ночь)
        '11d' => 'fas fa-bolt',          // гроза
        '11n' => 'fas fa-bolt',
        '13d' => 'fas fa-snowflake',     // снег
        '13n' => 'fas fa-snowflake',
        '50d' => 'fas fa-smog',         // туман
        '50n' => 'fas fa-smog'
    );
    
    return isset($icons[$weather_id]) ? $icons[$weather_id] : 'fas fa-question';
}

function weather_forecast_get_user_location() {
    // Проверяем, есть ли уже сохраненное местоположение
    if (isset($_COOKIE['user_weather_location'])) {
        return json_decode(stripslashes($_COOKIE['user_weather_location']), true);
    }
    return false;
}