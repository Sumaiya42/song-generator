<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Faker\Factory as Faker;

class SongController extends Controller
{
    public function generateSongs(Request $request)
    {
        $language = $request->input('language', 'en');
        $seed = $request->input('seed') ?? random_int(1, PHP_INT_MAX);
        $num_songs = $request->input('num_songs', 10);
        $likes_per_song = $request->input('likes', 5);

        $locale = $language === 'bn' ? 'bn_BD' : 'en_US';
        $faker = Faker::create($locale);
        $faker->seed($seed);

        $songs = [];

        for ($i = 1; $i <= $num_songs; $i++) {
            $integerLikes = floor($likes_per_song);
            $fractional = $likes_per_song - $integerLikes;
            $likes = $integerLikes + ($faker->boolean($fractional * 100) ? 1 : 0);

            $songs[] = [
                'index' => $i,
                'title' => $faker->words(2, true),
                'artist' => $faker->name,
                'album' => $faker->word ?: 'Single',
                'genre' => $faker->randomElement(['Pop','Jazz','Electronic','Ambient','Rock']),
                'likes' => $likes,
                'cover' => 'https://picsum.photos/150?random=' . $i,
                'preview' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-' . ($i % 16 + 1) . '.mp3',
                'review' => $faker->sentence,
            ];
        }

        return response()->json([
            'seed' => $seed,
            'songs' => $songs
        ]);
    }
}
