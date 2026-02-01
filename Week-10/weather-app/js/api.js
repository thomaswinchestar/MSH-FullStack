const API_KEY = import.meta.env.VITE_OPENWEATHER_API_KEY;
const BASE_URL = 'https://api.openweathermap.org/data/2.5/weather'

export async function getWeather(city) {
    if(!API_KEY) {
        throw new Error('API key is not configured');
    }

    const response = await fetch(
        `${BASE_URL}?q=${city}&units=metric&appid=${API_KEY}`
    );

    if(!response.ok) {
        throw new Error('City not found');
    }

    return await response.json();
}