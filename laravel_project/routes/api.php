

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SongController;

Route::post('/generate-songs', [SongController::class, 'generateSongs']);
Route::get('/test', function() {
    return 'API loaded';
});
