<?php

namespace App\Http\Controllers;

use App\Models\Komentar;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class KomentarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $komentar = Komentar::latest()->with('blog')->get();

        return response()->json([
            'msg' => 'Data komentar',
            'data' => $komentar
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
            'blog_id' => 'required',
            'nama' => 'required',
            'komentar' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $Komentar = Komentar::create($request->all());
            return response()->json([
                'msg' => 'Komentar berhasil ditambahkan',
                'data' => $Komentar
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
     * @param  \App\Models\Komentar  $komentar
     * @return \Illuminate\Http\Response
     */
    public function show(Komentar $komentar)
    {
        return response()->json([
            'msg' => 'Data komentar',
            'data' => $komentar
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Komentar  $komentar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Komentar $komentar)
    {
        Komentar::destroy($komentar->id);

        return response()->json([
            'msg' => 'Komentar berhasil dihapus',
            'data' => $komentar
        ], Response::HTTP_OK);
    }
}
