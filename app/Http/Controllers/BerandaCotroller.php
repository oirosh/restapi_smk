<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use App\Models\Blog;
use App\Models\Extra;
use App\Models\Fasilitas;
use App\Models\Galeri;
use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\Kategori;
use App\Models\Media;
use App\Models\Menu;
use App\Models\Prestasi;
use App\Models\Profil;
use App\Models\Statis;
use App\Models\Testimoni;
use App\Models\Tujuan;
use Illuminate\Http\Request;

class BerandaCotroller extends Controller
{
    public function index()
    {
        $profil = Profil::first();
        $visi_misi = Tujuan::orderBy('tipe', 'ASC')->get();
        $testimoni = Testimoni::latest()->with('user')->get();
        $medsos = Media::latest()->get();
        $studi = Jurusan::latest()->get();

        return response()->json([
            'msg' => 'Data yang anda butuhkan',
            'data' => [
                'studi' => $studi,
                'medsos' => $medsos,
                'profil' => $profil,
                'visi_misi' => $visi_misi,
                'testimoni' => $testimoni,
            ]
        ]);
    }

    public function header()
    {
        $profil = Profil::first();
        $menu = Menu::where('aktif', 'Y')->with('dropdown')->get();

        return response()->json([
            'msg' => 'Data yang anda butuhkan',
            'data' => [
                'menu' => $menu,
                'profil' => $profil
            ]
        ]);
    }

    public function footer()
    {
        $profil = Profil::first();
        $halaman = Statis::where('aktif', 'Y')->get();

        return response()->json([
            'msg' => 'Data yang anda butuhkan',
            'data' => [
                'halaman' => $halaman,
                'profil' => $profil
            ]
        ]);
    }

    public function blog()
    {
        $blog = Blog::latest()->with('kategori')->get();
        return response()->json([
            'msg' => 'Data yang anda butuhkan',
            'data' => [
                'blog' => $blog
            ]
        ]);
    }

    public function detail_blog($slug)
    {
        $blog = Blog::where('slug', $slug)->with('kategori')->first();
        if ($blog) {
            Blog::where('slug', $slug)->update([
                'views' => $blog->views + 1
            ]);
            return response()->json([
                'msg' => 'Data yang anda butuhkan',
                'data' => [
                    'blog' => $blog
                ]
            ]);
        }
    }

    public function blog_kategori($nama)
    {
        $kategori = Kategori::where('nama', $nama)->first();
        $blog = Blog::where('kategori_id', $kategori->id)->with('kategori')->get();
        if ($blog) {
            return response()->json([
                'msg' => 'Data yang anda butuhkan',
                'data' => [
                    'blog' => $blog
                ]
            ]);
        }
    }

    public function galeri()
    {
        $galeri = Galeri::latest()->get();

        return response()->json([
            'msg' => 'Data yang anda butuhkan',
            'data' => [
                'galeri' => $galeri
            ]
        ]);
    }

    public function info()
    {
        $extra = Extra::latest()->get();
        $prestasi = Prestasi::latest()->get();
        $beasiswa = Beasiswa::latest()->get();
        $fasilitas = Fasilitas::latest()->get();

        return response()->json([
            'msg' => 'Data yang anda butuhkan',
            'data' => [
                'fasilitas' => $fasilitas,
                'extra' => $extra,
                'prestasi' => $prestasi,
                'beasiswa' => $beasiswa
            ]
        ]);
    }

    public function guru()
    {
        $guru = Guru::latest()->get();

        return response()->json([
            'msg' => 'Data yang anda butuhkan',
            'data' => [
                'guru' => $guru,
            ]
        ]);
    }

    public function statis($link)
    {
        $statis = Statis::where('link', $link)->first();

        return response()->json([
            'msg' => 'Data yang anda butuhkan',
            'data' => [
                'statis' => $statis,
            ]
        ]);
    }
}
