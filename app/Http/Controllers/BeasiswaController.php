<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class BeasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $beasiswa = Beasiswa::latest()->get();

        return response()->json([
            'msg' => 'Data beasiswa',
            'data' => $beasiswa
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
            'nama' => 'required|unique:beasiswas'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $Beasiswa = Beasiswa::create($request->all());

            return response()->json([
                'msg' => 'Beasiswa berhasil ditambahkan',
                'data' => $Beasiswa
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
     * @param  \App\Models\Beasiswa  $beasiswa
     * @return \Illuminate\Http\Response
     */
    public function show(Beasiswa $beasiswa)
    {
        return response()->json([
            'msg' => 'Data beasiswa',
            'data' => $beasiswa
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Beasiswa  $beasiswa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Beasiswa $beasiswa)
    {
        if ($request->nama != $beasiswa->nama) {
            $validate = Validator::make($request->all(), [
                'nama' => 'required|unique:beasiswas'
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        try {
            $data = $request->all();
            unset($data['_method']);
            unset($data['created_at']);
            unset($data['updated_at']);

            $Beasiswa = Beasiswa::where('id', $beasiswa->id)->update($data);

            return response()->json([
                'msg' => 'Beasiswa berhasil diperbarui',
                'data' => Beasiswa::where('id', $beasiswa->id)->first()
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
     * @param  \App\Models\Beasiswa  $beasiswa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Beasiswa $beasiswa)
    {
        Beasiswa::destroy($beasiswa->id);

        return response()->json([
            'msg' => 'Beasiswa berhasil dihapus',
            'data' => $beasiswa
        ], Response::HTTP_OK);
    }
}
