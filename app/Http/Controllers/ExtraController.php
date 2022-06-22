<?php

namespace App\Http\Controllers;

use App\Models\Extra;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ExtraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $extra = Extra::latest()->get();

        return response()->json([
            'msg' => 'Data ekstrakurikuler',
            'data' => $extra
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
            'nama' => 'required|unique:extras',
            'gambar' => 'required|file|image|max:2048'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $gambar = $request->file('gambar')->store('ekskul');

            $data = $request->all();
            $data['gambar'] = $gambar;

            $Extra = Extra::create($data);
            return response()->json([
                'msg' => 'Ekstrakurikuler berhasil ditambahkan',
                'data' => $Extra
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
     * @param  \App\Models\Extra  $extra
     * @return \Illuminate\Http\Response
     */
    public function show(Extra $ekskul)
    {
        return response()->json([
            'msg' => 'Data ekstrakurikuler',
            'data' => $ekskul
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Extra  $extra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Extra $ekskul)
    {
        $rule = [
            'gambar' => 'file|image|max:2048'
        ];

        if ($request->nama != $ekskul->nama) {
            $rule['nama'] = 'required|unique:extras';
        }

        $validate = Validator::make($request->all(), $rule);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $gambar = $ekskul['gambar'];
            if ($request->file('gambar')) {
                Storage::delete($gambar);

                $gambar = $request->file('gambar')->store('ekskul');
            }

            $data = $request->all();
            unset($data['_method']);
            unset($data['created_at']);
            unset($data['updated_at']);

            $data['gambar'] = $gambar;

            $Extra = Extra::where('id', $ekskul->id)->update($data);
            return response()->json([
                'msg' => 'Ekstrakurikuler berhasil diperbarui',
                'data' => Extra::where('id', $ekskul->id)->first()
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
     * @param  \App\Models\Extra  $extra
     * @return \Illuminate\Http\Response
     */
    public function destroy(Extra $ekskul)
    {
        Storage::delete($ekskul->gambar);
        Extra::destroy($ekskul->id);

        return response()->json([
            'msg' => 'Ekstrakurikuler berhasil dihapus',
            'data' => $ekskul
        ], Response::HTTP_OK);
    }
}
