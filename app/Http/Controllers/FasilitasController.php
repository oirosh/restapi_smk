<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class FasilitasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fasilitas = Fasilitas::latest()->get();

        return response()->json([
            'msg' => 'Data fasilitas',
            'data' => $fasilitas
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
            'nama' => 'required|unique:fasilitas'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $Fasilitas = Fasilitas::create($request->all());
            return response()->json([
                'msg' => 'Fasilitas berhasil ditambahkan',
                'data' => $Fasilitas
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
     * @param  \App\Models\Fasilitas  $fasilitas
     * @return \Illuminate\Http\Response
     */
    public function show(Fasilitas $fasilita)
    {
        return response()->json([
            'msg' => 'Data fasilitas',
            'data' => $fasilita
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fasilitas  $fasilitas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fasilitas $fasilita)
    {
        if ($request->nama != $fasilita->nama) {
            $validate = Validator::make($request->all(), [
                'nama' => 'required|unique:fasilitas'
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

            $Fasilitas = Fasilitas::where('id', $fasilita->id)->update($data);
            return response()->json([
                'msg' => 'Fasilitas berhasil diperbarui',
                'data' => Fasilitas::where('id', $fasilita->id)->first()
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
     * @param  \App\Models\Fasilitas  $fasilitas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fasilitas $fasilita)
    {
        Fasilitas::destroy($fasilita->id);
        return response()->json([
            'msg' => 'Fasilitas berhasil dihapus',
            'data' => $fasilita
        ], Response::HTTP_OK);
    }
}
