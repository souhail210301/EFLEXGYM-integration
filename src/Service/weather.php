<?php
use GuzzleHttp\Client;


class weather{

}
$apiKey = 'e7e933b8ee14963f05103ef05c85201c';
$city = 'London'; // Replace with the city you want to get the weather for

$client = new Client([
'base_uri' => 'https://api.openweathermap.org/data/2.5/',
'timeout'  => 2.0,
]);

$response = $client->request('GET', 'weather', [
'query' => [
'q' => $city,
'appid' => $apiKey,
'units' => 'metric', // Use 'metric' for Celsius, 'imperial' for Fahrenheit
]
]);

$data = json_decode($response->getBody()->getContents(), true);

// Access weather data
$temperature = $data['main']['temp'];
$weatherDescription = $data['weather'][0]['description'];
