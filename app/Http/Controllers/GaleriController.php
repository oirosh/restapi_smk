<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class GaleriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $galeri = Galeri::latest()->get();

        return response()->json([
            'msg' => 'Data galeri',
            'data' => $galeri
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
            'media' => 'required|file|mimes:png,jpg,jpeg,mkv,mp4,avi|max:30720'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $media = $request->file('media')->store('galeri');

            $Galeri = Galeri::create(['media' => $media]);
            return response()->json([
                'msg' => 'Media galeri berhasil ditambahkan',
                'data' => $Galeri
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
     * @param  \App\Models\Galeri  $galeri
     * @return \Illuminate\Http\Response
     */
    public function show(Galeri $galeri)
    {
        return response()->json([
            'msg' => 'Data galeri',
            'data' => $galeri
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Galeri  $galeri
     * @return \Illuminate\Http\Response
     */
    public function destroy(Galeri $galeri)
    {
        Storage::delete($galeri->media);
        Galeri::destroy($galeri->id);

        return response()->json([
            'msg' => 'Media galeri berhasil dihapus',
            'data' => $galeri
        ], Response::HTTP_OK);
    }
}
