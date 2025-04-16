<?php
// Добавляем страницу настроек в админку
add_action('admin_menu', 'weather_forecast_add_admin_menu');
add_action('admin_init', 'weather_forecast_settings_init');

function weather_forecast_add_admin_menu() {
    add_options_page(
        'Weather Forecast Settings',
        'Weather Forecast',
        'manage_options',
        'weather-forecast',
        'weather_forecast_options_page'
    );
}

function weather_forecast_settings_init() {
    register_setting('weather_forecast', 'weather_forecast_settings');
    
    add_settings_section(
        'weather_forecast_section',
        'Настройки API',
        'weather_forecast_section_callback',
        'weather_forecast'
    );
    
    add_settings_field(
        'api_key',
        'OpenWeatherMap API Key',
        'weather_forecast_api_key_render',
        'weather_forecast',
        'weather_forecast_section'
    );
    
    add_settings_field(
        'default_location',
        'Default Location',
        'weather_forecast_default_location_render',
        'weather_forecast',
        'weather_forecast_section'
    );
}

function weather_forecast_api_key_render() {
    $options = get_option('weather_forecast_settings');
    ?>
    <input type="text" name="weather_forecast_settings[api_key]" value="<?php echo isset($options['api_key']) ? esc_attr($options['api_key']) : ''; ?>">
    <p class="description">Получите API ключ на <a href="https://openweathermap.org/api" target="_blank">OpenWeatherMap</a></p>
    <?php
}

function weather_forecast_default_location_render() {
    $options = get_option('weather_forecast_settings');
    ?>
    <input type="text" name="weather_forecast_settings[default_location]" value="<?php echo isset($options['default_location']) ? esc_attr($options['default_location']) : 'Moscow'; ?>">
    <p class="description">Город по умолчанию (например: Moscow, London, New York)</p>
    <?php
}

function weather_forecast_section_callback() {
    echo '<p>Введите настройки для работы плагина прогноза погоды</p>';
}

function weather_forecast_options_page() {
    ?>
    <div class="wrap">
        <h1>Weather Forecast Settings</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('weather_forecast');
            do_settings_sections('weather_forecast');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}