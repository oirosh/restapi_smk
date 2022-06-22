<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Kategori;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategori = Kategori::latest()->with('blog')->get();

        return response()->json([
            'msg' => 'Data kategori',
            'data' => $kategori
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
            'nama' => 'required|unique:kategoris',
            'gambar' => 'required|file|image|max:2048'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $gambar = $request->file('gambar')->store('kategori');

            $data = $request->all();
            $data['gambar'] = $gambar;

            $Kategori = Kategori::create($data);
            return response()->json([
                'msg' => 'Kategori berhasil ditambahkan',
                'data' => $Kategori
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
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function show(Kategori $kategori)
    {
        return response()->json([
            'msg' => 'Data kategori',
            'data' => $kategori
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kategori $kategori)
    {
        $rule = [
            'gambar' => 'file|image|max:2048'
        ];

        if ($request->nama != $kategori->nama) {
            $rule['nama'] = 'required|unique:kategoris';
        }

        $validate = Validator::make($request->all(), $rule);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $gambar = $kategori->gambar;
            if ($request->file('gambar')) {
                Storage::delete($gambar);

                $gambar = $request->file('gambar')->store('kategori');
            }

            $data = $request->all();
            unset($data['_method']);
            unset($data['created_at']);
            unset($data['updated_at']);

            $data['gambar'] = $gambar;

            $Kategori = Kategori::where('id', $kategori->id)->update($data);
            return response()->json([
                'msg' => 'Kategori berhasil diperbarui',
                'data' => Kategori::where('id', $kategori->id)->first()
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
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kategori $kategori)
    {
        foreach (Blog::where('kategori_id', $kategori->id)->get() as $blog) {
            Storage::delete($blog->gambar);
            Blog::destroy($blog->id);
        }

        Storage::delete($kategori->gambar);
        Kategori::destroy($kategori->id);

        return response()->json([
            'msg' => 'Kategori berhasil dihapus',
            'data' => $kategori
        ], Response::HTTP_OK);
    }
}
