<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $guru = Guru::latest()->get();

        return response()->json([
            'msg' => 'Data guru',
            'data' => $guru
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
            'nama' => 'required',
            'nip' => 'required|unique:gurus',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'gambar' => 'required|image|file|max:2046'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $gambar = $request->file('gambar')->store('guru');

            $data = $request->all();
            $data['gambar'] = $gambar;

            $Guru = Guru::create($data);
            return response()->json([
                'msg' => 'Guru berhasil ditambahkan',
                'data' => $Guru
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
     * @param  \App\Models\Guru  $guru
     * @return \Illuminate\Http\Response
     */
    public function show(Guru $guru)
    {
        return response()->json([
            'msg' => 'Data guru',
            'data' => $guru
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Guru  $guru
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Guru $guru)
    {
        $rule = [
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'gambar' => 'image|file|max:2046'
        ];

        if ($request->nip != $guru->nip) {
            $rule['nip'] = 'required|unique:gurus';
        }

        $validate = Validator::make($request->all(), $rule);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $gambar = $guru->gambar;
            if ($request->file('gambar')) {
                Storage::delete($gambar);

                $gambar = $request->file('gambar')->store('guru');
            }

            $data = $request->all();
            unset($data['_method']);
            unset($data['created_at']);
            unset($data['updated_at']);

            $data['gambar'] = $gambar;

            $Guru = Guru::where('id', $guru->id)->update($data);
            return response()->json([
                'msg' => 'Guru berhasil diperbarui',
                'data' => Guru::where('id', $guru->id)->first()
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
     * @param  \App\Models\Guru  $guru
     * @return \Illuminate\Http\Response
     */
    public function destroy(Guru $guru)
    {
        Storage::delete($guru->gambar);
        Guru::destroy($guru->id);

        return response()->json([
            'msg' => 'Guru berhasil dihapus',
            'data' => $guru
        ], Response::HTTP_OK);
    }
}
