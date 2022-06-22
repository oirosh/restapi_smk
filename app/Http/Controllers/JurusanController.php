<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jurusan = Jurusan::latest()->get();

        return response()->json([
            'msg' => 'Data kompetensi keahlian',
            'data' => $jurusan
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
            'nama' => 'required|unique:jurusans',
            'deskripsi' => 'required',
            'gambar' => 'required|image|file|max:2048'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $gambar = $request->file('gambar')->store('program_studi');

            $data = $request->all();
            $data['gambar'] = $gambar;

            $Jurusan = Jurusan::create($data);
            return response()->json([
                'msg' => 'Kompetensi keahlian berhasil ditambahkan',
                'data' => $Jurusan
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
     * @param  \App\Models\Jurusan  $jurusan
     * @return \Illuminate\Http\Response
     */
    public function show(Jurusan $program_studi)
    {
        return response()->json([
            'msg' => 'Data kompetensi keahlian',
            'data' => $program_studi
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Jurusan  $jurusan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jurusan $program_studi)
    {
        $rule = [
            'deskripsi' => 'required',
            'gambar' => 'image|file|max:2048'
        ];

        if ($request->nama != $program_studi->nama) {
            $rule['nama'] = 'required|unique:jurusans';
        }

        $validate = Validator::make($request->all(), $rule);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $gambar = $program_studi->gambar;
            if ($request->file('gambar')) {
                Storage::delete($gambar);

                $gambar = $request->file('gambar')->store('program_studi');
            }

            $data = $request->all();
            unset($data['_method']);
            unset($data['created_at']);
            unset($data['updated_at']);

            $data['gambar'] = $gambar;

            $Jurusan = Jurusan::where('id', $program_studi->id)->update($data);
            return response()->json([
                'msg' => 'Kompetensi keahlian berhasil diperbarui',
                'data' => Jurusan::where('id', $program_studi->id)->first()
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
     * @param  \App\Models\Jurusan  $jurusan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jurusan $program_studi)
    {
        Storage::delete($program_studi->gambar);
        Jurusan::destroy($program_studi->id);

        return response()->json([
            'msg' => 'Kompetensi keahlian berhasil diperbarui',
            'data' => $program_studi
        ], Response::HTTP_OK);
    }
}
