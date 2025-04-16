<?php
// Регистрируем шорткод
add_shortcode('weather_forecast', 'weather_forecast_shortcode_handler');

// Обработчик шорткода
function weather_forecast_shortcode_handler($atts) {
    $options = get_option('weather_forecast_settings');
    $api_key = $options['api_key'] ?? '';
    
    $atts = shortcode_atts(array(
        'location' => '',
        'show_icon' => 'true',
        'show_temp' => 'true',
        'show_humidity' => 'true',
        'show_wind' => 'true',
        'auto_location' => 'false'
    ), $atts);

    // Если включено автоопределение - возвращаем контейнер для JS
    if ($atts['auto_location'] === 'true') {
        return '<span class="weather-auto-location" data-api-key="'.esc_attr($api_key).'">
            <i class="fas fa-spinner fa-spin"></i> Загружаем погоду...
        </span>';
    }

    // Стандартный вывод для указанного местоположения
    $data = weather_forecast_get_data($atts['location'], $api_key);
    
    // Формируем HTML для отображения погоды
    ob_start();
    ?>
    <span class="weather-inline">
        <?php echo esc_html($data['name']); ?> 
        
        <?php if ($atts['show_icon'] === 'true') : ?>
        <i class="<?php echo esc_attr(weather_forecast_get_icon($data['weather'][0]['icon'])); ?>"></i>
        <?php endif; ?>
        
        <?php if ($atts['show_temp'] === 'true') : ?>
        <?php echo round($data['main']['temp']); ?>°C
        <?php endif; ?>
        
        <?php if ($atts['show_humidity'] === 'true') : ?>
        <i class="fas fa-tint"></i> <?php echo esc_html($data['main']['humidity']); ?>%
        <?php endif; ?>
        
        <?php if ($atts['show_wind'] === 'true') : ?>
        <i class="fas fa-wind"></i> <?php echo esc_html($data['wind']['speed']); ?> м/с
        <?php endif; ?>
    </span>
    <?php
    return ob_get_clean();
}