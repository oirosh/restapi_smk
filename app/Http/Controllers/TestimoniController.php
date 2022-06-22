<?php

namespace App\Http\Controllers;

use App\Models\Testimoni;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TestimoniController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $testimoni = Testimoni::latest()->with('user')->get();

        return response()->json([
            'msg' => 'Testimoni user',
            'data' => $testimoni
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
            'user_id' => 'required',
            'pesan' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $Testimoni = Testimoni::create($request->all());
            return response()->json([
                'msg' => 'Testimoni berhasil ditambahkan',
                'data' => Testimoni::where('id', $Testimoni->id)->with('user')->first()
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
     * @param  \App\Models\Testimoni  $testimoni
     * @return \Illuminate\Http\Response
     */
    public function show(Testimoni $testimoni)
    {
        return response()->json([
            'msg' => 'Testimoni user',
            'data' => $testimoni
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Testimoni  $testimoni
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Testimoni $testimoni)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'pesan' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = $request->only(['user_id', 'pesan']);
            unset($data['_method']);
            unset($data['created_at']);
            unset($data['updated_at']);

            $Testimoni = Testimoni::where('id', $testimoni->id)->update($data);
            return response()->json([
                'msg' => 'Testimoni berhasil diperbarui',
                'data' => Testimoni::where('id', $testimoni->id)->with('user')->first()
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
     * @param  \App\Models\Testimoni  $testimoni
     * @return \Illuminate\Http\Response
     */
    public function destroy(Testimoni $testimoni)
    {
        Testimoni::destroy($testimoni->id);

        return response()->json([
            'msg' => 'Testimoni berhasil dihapus',
            'data' => $testimoni
        ], Response::HTTP_OK);
    }
}
