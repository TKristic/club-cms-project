<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\SponsorController;
use App\Models\Player;
use Illuminate\Http\Response;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/vijesti', [NewsController::class, 'index'])->name('news.index');
Route::get('/vijesti/{slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/galerija', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/galerija/{gallery}', [GalleryController::class, 'show'])->name('gallery.show');

Route::get('/momcad', [PlayerController::class, 'index'])->name('players.index');

Route::get('/upis', [EnrollmentController::class, 'create'])->name('enroll.create');
Route::post('/upis/pregled', [EnrollmentController::class, 'review'])->name('enroll.review');
Route::post('/upis', [EnrollmentController::class, 'store'])->name('enroll.store');

Route::get('/kontakt', [ContactController::class, 'create'])->name('contact.create');
Route::post('/kontakt', [ContactController::class, 'store'])->name('contact.store');

Route::get('/uplatnica/{invoice}/pdf', [InvoiceController::class, 'download'])->name('invoice.download');

Route::get('/prijava', [AuthController::class, 'showLogin'])->name('login');
Route::post('/prijava', [AuthController::class, 'login']);
Route::get('/registracija', [AuthController::class, 'showRegister'])->name('register');
Route::post('/registracija', [AuthController::class, 'register']);
Route::post('/odjava', [AuthController::class, 'logout'])->name('logout');

Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/{topic}', [ForumController::class, 'show'])->name('forum.show');
Route::middleware('auth')->group(function () {
    Route::get('/forum-nova-tema', [ForumController::class, 'create'])->name('forum.create');
    Route::post('/forum-nova-tema', [ForumController::class, 'storeTopic'])->name('forum.storeTopic');
    Route::post('/forum/{topic}/odgovor', [ForumController::class, 'storePost'])->name('forum.storePost');
});

Route::get('/jezik/{locale}', function (string $locale) {
    if (in_array($locale, ['hr', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('locale.set');

Route::get('/sponzori/{format?}', [SponsorController::class, 'index'])->name('sponsors.index');
Route::post('/sponzori/{format}', [SponsorController::class, 'store'])->name('sponsors.store');
Route::put('/sponzori/{format}/{id}', [SponsorController::class, 'update'])->name('sponsors.update');
Route::delete('/sponzori/{format}/{id}', [SponsorController::class, 'destroy'])->name('sponsors.destroy');

Route::get('/igrac/{player}/slika-blob', function (Player $player) {
    abort_if(! $player->photo_blob, 404);

    return response($player->photo_blob)
        ->header('Content-Type', 'image/jpeg');
})->name('player.photoBlob');