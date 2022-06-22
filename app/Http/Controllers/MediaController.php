<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $media = Media::latest()->get();

        return response()->json([
            'msg' => 'Data media sosial',
            'data' => $media
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
            'nama' => 'required|unique:media',
            'ikon' => 'required',
            'link' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $Media = Media::create($request->all());
            return response()->json([
                'msg' => 'Media sosial berhasil ditambahkan',
                'data' => $Media
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
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function show(Media $media_sosial)
    {
        return response()->json([
            'msg' => 'Data media sosial',
            'data' => $media_sosial
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Media $media_sosial)
    {
        $rule = [
            'ikon' => 'required',
            'link' => 'required'
        ];

        if ($request->nama != $media_sosial->nama) {
            $rule['nama'] = 'required|unique:media';
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


            $Media = Media::where('id', $media_sosial->id)->update($data);
            return response()->json([
                'msg' => 'Media sosial berhasil diperbarui',
                'data' => Media::where('id', $media_sosial->id)->first()
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
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function destroy(Media $media_sosial)
    {
        Media::destroy($media_sosial->id);

        return response()->json([
            'msg' => 'Media sosial berhasil dihapus',
            'data' => $media_sosial
        ], Response::HTTP_OK);
    }
}
