<?php

use App\Http\Controllers\BeasiswaController;
use App\Http\Controllers\BerandaCotroller;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\ExtraController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KomentarController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\StatisController;
use App\Http\Controllers\TestimoniController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Visi_MisiController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/login', [LoginController::class, 'login']);
Route::get('/app/profile', [ProfilController::class, 'index']);

Route::get('/app/beranda', [BerandaCotroller::class, 'index']);
Route::get('/app/header', [BerandaCotroller::class, 'header']);
Route::get('/app/footer', [BerandaCotroller::class, 'footer']);
Route::get('/app/blogpost', [BerandaCotroller::class, 'blog']);
Route::get('/app/blogpost/{slug}', [BerandaCotroller::class, 'detail_blog']);
Route::get('/app/blog_kategori/{nama}', [BerandaCotroller::class, 'blog_kategori']);
Route::get('/app/galeri', [BerandaCotroller::class, 'galeri']);
Route::get('/app/info', [BerandaCotroller::class, 'info']);
Route::get('/app/guru', [BerandaCotroller::class, 'guru']);
Route::get('/app/statis/{link}', [BerandaCotroller::class, 'statis']);

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/admin/beasiswa', BeasiswaController::class)->except(['create', 'edit']);
    Route::resource('/admin/blog', BlogController::class)->except(['create', 'edit']);
    Route::resource('/admin/menu/dropdown', DropdownController::class)->except(['create', 'edit']);
    Route::resource('/admin/ekskul', ExtraController::class)->except(['create', 'edit']);
    Route::resource('/admin/fasilitas', FasilitasController::class)->except(['create', 'edit']);
    Route::resource('/admin/galeri', GaleriController::class)->except(['create', 'edit', 'update']);
    Route::resource('/admin/guru', GuruController::class)->except(['create', 'edit']);
    Route::resource('/admin/program_studi', JurusanController::class)->except(['create', 'edit']);
    Route::resource('/admin/kategori', KategoriController::class)->except(['create', 'edit']);
    Route::resource('/admin/komentar', KomentarController::class)->except(['create', 'edit']);
    Route::resource('/admin/media_sosial', MediaController::class)->except(['create', 'edit']);
    Route::resource('/admin/menu', MenuController::class)->except(['create', 'edit']);
    Route::resource('/admin/prestasi', PrestasiController::class)->except(['create', 'edit']);
    Route::resource('/admin/profil', ProfilController::class)->except(['create', 'edit']);
    Route::resource('/admin/halaman_statis', StatisController::class)->except(['create', 'edit']);
    Route::resource('/admin/testimoni', TestimoniController::class)->except(['create', 'edit']);
    Route::resource('/admin/user', UserController::class)->except(['create', 'edit']);
    Route::resource('/admin/visi_misi', Visi_MisiController::class)->except(['create', 'edit']);
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::post('/me', [LoginController::class, 'getToken']);
});
