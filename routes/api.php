<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function (Request $request) {

    //Abrimos o arquivo das cidades
    $cities = file_get_contents(base_path('public/cities.json'));
    $cities = json_decode($cities);
    $cities = $cities->cities;

    //A cidade que foi pedida
    $city = strtolower($request->city);

    //Request na API da OpenWeather
    $api_req = Http::get('https://api.openweathermap.org/data/2.5/weather?q=' . $city . '&appid=' . env('OPEN_WEATHER_API_KEY') . '&units=metric');

    $req = $api_req->json();

    //Se a requisição da cidade der erro
    if ($api_req->status() == 404)
        return view('welcome')->with([
            'cities' => $cities,
            'city_data' => [
                '0',
                'CITY_NOT_FOUND',
                'LAVENDER TOWN'
            ],
            'poke_data' => [
                '000',
                'POKEMON_NOT_FOUND',
                'https://wiki.p-insurgence.com/images/0/09/722.png'
            ]
        ]);


    //Pegamos a temperatura atual de lá
    $temp = $req['main']['temp'];

    //Situação da chuva
    $raining = ($req['weather'][0]['main'] == 'Rain' ? 1 : 0);

    //Dados daquela cidade
    $city_data = [
        $temp,
        $raining ? 'Está chovendo em ' . $city : 'Não está chovendo em ' . ucwords($city),
        ucwords($city)
    ];

    //Função para pegarmos um pokémon aleatório
    function randomPokemon($type, $raining = 0)
    {
        if ($raining == 1)
            $type = 'electric';

        //Os pokemons daquele tipo
        $random_pokemons = Http::get('https://pokeapi.co/api/v2/type/' . $type)
                            ->json();

        //Índice aleatório do Pokémon
        $index = rand(0, sizeof($random_pokemons['pokemon']) - 1);

        //Pegamos um deles aleatoriamente
        $name = $random_pokemons['pokemon'][$index]['pokemon']['name'];

        //Junto dele, pegamos a url para mais informações sobre
        $poke_url = $random_pokemons['pokemon'][$index]['pokemon']['url'];

        //Damos GET naquela url, para trazer as informações que queremos
        $poke_info = Http::get($poke_url)
                        ->json();

        $id = $poke_info['id'];
        $sprite = $poke_info['sprites']['front_default'];

        //Um array de dados do pokémon
        $poke_data = [
            $id,
            $name,
            $sprite == '' ? 'https://wiki.p-insurgence.com/images/0/09/722.png' : $sprite
        ];

        return $poke_data;
    }


    //Checagem para as temperturas
    if ($temp < 5)
        $poke_data = randomPokemon('ice', $raining);

    else if ($temp >= 5 && $temp < 10)
        $poke_data = randomPokemon('water', $raining);

    else if ($temp >= 12 && $temp < 15)
        $poke_data = randomPokemon('grass', $raining);

    else if ($temp >= 15 && $temp < 21)
        $poke_data = randomPokemon('ground', $raining);

    else if ($temp >= 23 && $temp < 27)
        $poke_data = randomPokemon('bug', $raining);

    else if ($temp >= 27 && $temp <= 33)
        $poke_data = randomPokemon('rock', $raining);

    else if ($temp > 33)
        $poke_data = randomPokemon('fire', $raining);

    else
        $poke_data = randomPokemon('normal', $raining);

    //Se o pokémon for o mesmo de antes
    //Não consegui imaginar um jeito de adaptar meu código pra isso...
    //O approach seria pegar o ID do pokémon e, se for o mesmo do old_pokemon,
    //eu refazia a busca!

    return view('welcome')->with([
        'cities' => $cities,
        'city_data' => $city_data,
        'poke_data' => $poke_data
    ]);

})->name('city');
