<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PrestasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prestasi = Prestasi::latest()->get();

        return response()->json([
            'msg' => 'Data prestasi',
            'data' => $prestasi
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
            'tingkat' => 'required',
            'tahun' => 'required',
            'juara' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $Prestasi = Prestasi::create($request->all());
            return response()->json([
                'msg' => 'Prestasi berhasil ditambahkan',
                'data' => $Prestasi
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
     * @param  \App\Models\Prestasi  $prestasi
     * @return \Illuminate\Http\Response
     */
    public function show(Prestasi $prestasi)
    {
        return response()->json([
            'msg' => 'Data prestasi',
            'data' => $prestasi
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prestasi  $prestasi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prestasi $prestasi)
    {
        $validate = Validator::make($request->all(), [
            'nama' => 'required',
            'tingkat' => 'required',
            'tahun' => 'required',
            'juara' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = $request->all();
            unset($data['_method']);
            unset($data['created_at']);
            unset($data['updated_at']);

            $Prestasi = Prestasi::where('id', $prestasi->id)->update($data);
            return response()->json([
                'msg' => 'Prestasi berhasil diperbarui',
                'data' => Prestasi::where('id', $prestasi->id)->first()
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
     * @param  \App\Models\Prestasi  $prestasi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prestasi $prestasi)
    {
        Prestasi::destroy($prestasi->id);

        return response()->json([
            'msg' => 'Prestasi berhasil dihapue',
            'data' => $prestasi
        ], Response::HTTP_OK);
    }
}
