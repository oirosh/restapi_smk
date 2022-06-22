<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use ILluminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blog = Blog::latest()->with('kategori')->with('komentar')->get();

        return response()->json([
            'msg' => 'Data blog',
            'data' => $blog
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'kategori_id' => 'required',
            'judul' => 'required',
            'isi_text' => 'required',
            'gambar' => 'required|file|image|max:2048',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $slug = Str::slug($request->judul);
            $jumlah = Blog::where('judul', $request->judul)->count();
            if ($jumlah > 0) {
                $slug = $slug . $jumlah;
            }

            $gambar = $request->file('gambar')->store('blog');

            $data = $request->all();
            $data['slug'] = $slug;
            $data['gambar'] = $gambar;

            $Blog = Blog::create($data);
            return response()->json([
                'msg' => 'Blog berhasil ditambahkan',
                'data' => Blog::where('slug', $slug)->with('kategori')->with('komentar')->first()
            ], Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'msg' => 'Error - ' . $e
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        return response()->json([
            'msg' => 'Data blog',
            'data' => $blog
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $validate = Validator::make($request->all(), [
            'kategori_id' => 'required',
            'judul' => 'required',
            'isi_text' => 'required',
            'gambar' => 'file|image|max:2048',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $slug = Str::slug($request->judul);
            $jumlah = Blog::where('judul', $request->judul)->count();
            if ($jumlah > 1) {
                if ($jumlah - 1 == 0) {
                    $slug = $slug;
                } else {
                    $slug = $slug . $jumlah;
                }
            }

            $gambar = $blog->gambar;
            if ($request->file('gambar')) {
                Storage::delete($gambar);

                $gambar = $request->file('gambar')->store('blog');
            }

            $data = $request->all();
            unset($data['_method']);
            unset($data['created_at']);
            unset($data['updated_at']);
            unset($data['kategori']);

            $data['slug'] = $slug;
            $data['gambar'] = $gambar;

            $Blog = Blog::where('id', $blog->id)->update($data);
            return response()->json([
                'msg' => 'Blog berhasil diperbarui',
                'data' => Blog::where('id', $blog->id)->with('kategori')->with('komentar')->first()
            ], Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'msg' => 'Error - ' . $e
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        Storage::delete($blog->gambar);
        Blog::destroy($blog->id);

        return response()->json([
            'msg' => 'Blog berhasil dihapus',
            'data' => $blog
        ], Response::HTTP_OK);
    }
}
