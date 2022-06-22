<?php

namespace App\Http\Controllers;

use App\Models\Tujuan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class Visi_MisiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $visi_misi = Tujuan::latest()->get();

        return response()->json([
            'msg' => 'Data visi misi',
            'data' => $visi_misi
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
        $rule = [
            'isi_text' => 'required',
            'tipe' => 'required'
        ];

        if ($request->tipe == 'visi') {
            $rule['tipe'] = 'required|unique:tujuans';
        }

        $validate = Validator::make($request->all(), $rule);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $Visi_misi = Tujuan::create($request->all());
            return response()->json([
                'msg' => 'Visi misi berhasil ditambahkan',
                'data' => $Visi_misi
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
     * @param  \App\Models\Tujuan  $visi_misi
     * @return \Illuminate\Http\Response
     */
    public function show(Tujuan $visi_misi)
    {
        return response()->json([
            'msg' => 'Data visi misi',
            'data' => $visi_misi
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tujuan  $visi_misi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tujuan $visi_misi)
    {
        $rule = [
            'isi_text' => 'required',
            'tipe' => 'required'
        ];

        if ($request->tipe == 'visi') {
            if ($request->tipe != $visi_misi->tipe) {
                $rule['tipe'] = 'required|unique:tujuans';
            }
        }

        $validate = Validator::make($request->all(), $rule);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = $request->all();
            unset($data['_method']);
            unset($data['created_at']);
            unset($data['updated_at']);

            $Visi_misi = Tujuan::where('id', $visi_misi->id)->update($data);
            return response()->json([
                'msg' => 'Visi misi berhasil diperbarui',
                'data' => Tujuan::where('id', $visi_misi->id)->first()
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
     * @param  \App\Models\Tujuan  $visi_misi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tujuan $visi_misi)
    {
        Tujuan::destroy($visi_misi->id);
        return response()->json([
            'msg' => 'Visi misi berhasil dihapus',
            'data' => $visi_misi
        ], Response::HTTP_OK);
    }
}
