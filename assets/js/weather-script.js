document.addEventListener('DOMContentLoaded', function() {
    const weatherElements = document.querySelectorAll('.weather-auto-location');
    
    weatherElements.forEach(element => {
        const apiKey = element.getAttribute('data-api-key');
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                position => {
                    const {latitude: lat, longitude: lon} = position.coords;
                    fetchWeather(lat, lon, apiKey, element);
                },
                error => {
                    element.innerHTML = '<i class="fas fa-map-marker-alt"></i> Не удалось определить локацию';
                }
            );
        } else {
            element.innerHTML = 'Геолокация не поддерживается';
        }
    });
});

async function fetchWeather(lat, lon, apiKey, element) {
    try {
        const response = await fetch(
            `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric&lang=ru`
        );
        const data = await response.json();
        
        if (data.cod === 200) {
            element.innerHTML = formatWeather(data);
        } else {
            element.innerHTML = 'Ошибка загрузки погоды';
        }
    } catch (error) {
        element.innerHTML = 'Ошибка соединения';
    }
}

function formatWeather(data) {
    const iconClass = {
        '01d': 'fas fa-sun',
        '01n': 'fas fa-moon',
        // ... другие иконки ...
    }[data.weather[0].icon] || 'fas fa-cloud';
    
    return `
        ${data.name} 
        <i class="${iconClass}"></i> ${Math.round(data.main.temp)}°C 
        <i class="fas fa-tint"></i> ${data.main.humidity}% 
        <i class="fas fa-wind"></i> ${data.wind.speed} м/с
    `;
}