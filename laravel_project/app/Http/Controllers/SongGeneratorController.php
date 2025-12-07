<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Faker\Factory as Faker;

class SongGeneratorController extends Controller
{
    public function generate(Request $request)
    {
        $faker = Faker::create();

        // ------------------------------
        // 1. PARAMETERS
        // ------------------------------
        $language = $request->input('language', 'en');
        $numSongs = $request->input('num_songs', 10);
        $likesInput = $request->input('likes', 5);

        // Seed: use provided or generate random 64-bit
        if ($request->has('seed') && $request->input('seed') !== '') {
            $seed = (int)$request->input('seed');
        } else {
            $seed = random_int(PHP_INT_MIN, PHP_INT_MAX); // random seed
        }

        mt_srand($seed);

        // ------------------------------
        // 2. BANGLA WORD LISTS
        // ------------------------------
        $banglaTitles = [
            'নীল আকাশ', 'তোমার পথে', 'বৃষ্টি দিনে', 'চেনা অনুভূতি', 'আলো হাওয়া',
            'শেষ বিকেল', 'মেঘলা সকাল', 'স্বপ্নের শহর', 'নিশীথ রাতে', 'ভোরের গান'
        ];

        $banglaAlbums = [
            'স্বপ্ন', 'ভালোবাসা', 'অপূর্ণতা', 'রোদেলা দুপুর', 'রাতের গান',
            'অনুভূতি', 'চিরকালের গল্প', 'আমার শহর', 'দিগন্ত', 'নীল পরী'
        ];

        $banglaGenres = [
            'পপ', 'রক', 'জ্যাজ', 'অ্যাম্বিয়েন্ট', 'ইলেকট্রনিক'
        ];

        $banglaArtists = [
            'সুমন', 'রাহাত', 'নাজমুল', 'তানভীর', 'মেহেদী',
            'আশফাক', 'জয়া', 'লাবণ্য', 'মোহনা', 'অর্ণব'
        ];

        // ------------------------------
        // 3. GENERATE SONGS
        // ------------------------------
        $songs = [];

        for ($i = 1; $i <= $numSongs; $i++) {

            // ====== TITLE, ALBUM, GENRE, ARTIST ======
            if ($language === 'bn') {
                $title  = $banglaTitles[array_rand($banglaTitles)];
                $album  = $banglaAlbums[array_rand($banglaAlbums)];
                $genre  = $banglaGenres[array_rand($banglaGenres)];
                $artist = $banglaArtists[array_rand($banglaArtists)];
            } else {
                $title  = $faker->sentence(2);
                $album  = $faker->word();
                $genre  = $faker->randomElement(['Pop', 'Rock', 'Jazz', 'Ambient', 'Electronic']);
                $artist = $faker->name();
            }

            // ====== LIKES LOGIC ======
            if ($likesInput == 0) {
                $likes = 0;
            } elseif ($likesInput == 10) {
                $likes = 10;
            } elseif (floor($likesInput) != $likesInput) {
                // fractional (probabilistic)
                $fraction = $likesInput - floor($likesInput); // decimal part
                $likes = floor($likesInput);
                if (mt_rand() / mt_getrandmax() < $fraction) {
                    $likes++;
                }
            } else {
                // normal integer likes
                $likes = $likesInput;
            }

            $songs[] = [
                'index'  => $i,
                'title'  => $title,
                'artist' => $artist,
                'album'  => $album,
                'genre'  => $genre,
                'likes'  => $likes
            ];
        }

        // ------------------------------
        // RETURN FINAL RESPONSE
        // ------------------------------
        return response()->json([
            'seed'  => $seed,
            'songs' => $songs,
        ]);
    }
}
