<?php
/*
Plugin Name: Weather Forecast
Plugin URI: https://zurikoff.ru/
Description: Плагин для отображения прогноза погоды с иконками
Version: 1.2
Author: Denis Zurikov
Author URI: https://zurikoff.ru/
*/

if (!defined('ABSPATH')) {
    exit; // Запрет прямого доступа
}

// Подключаем необходимые файлы
require_once plugin_dir_path(__FILE__) . 'includes/weather-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/weather-shortcode.php';
require_once plugin_dir_path(__FILE__) . 'includes/weather-settings.php';

// Инициализация плагина
function weather_forecast_init() {
    // Загрузка стилей и скриптов
    add_action('wp_enqueue_scripts', 'weather_forecast_enqueue_scripts');
}
add_action('init', 'weather_forecast_init');

// Загрузка CSS и JS
function weather_forecast_enqueue_scripts() {
    wp_enqueue_style(
        'weather-font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'
    );
    
    // Подключаем только при наличии шорткода
    if (has_shortcode(get_post()->post_content, 'weather_forecast')) {
        wp_enqueue_script(
            'weather-forecast-script',
            plugin_dir_url(__FILE__) . 'assets/js/weather-script.js',
            [],
            '1.0',
            true
        );
    }
}

