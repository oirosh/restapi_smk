<?php

namespace App\Http\Controllers;

use App\Models\Statis;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use ILluminate\Support\Str;

class StatisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statis = Statis::latest()->get();

        return response()->json([
            'msg' => 'Data halaman statis',
            'data' => $statis
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
            'title' => 'required|unique:statis',
            'aktif' => 'required',
            'isi_text' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {

            $data = $request->all();
            $data['link'] = Str::slug($request->title);

            $Statis = Statis::create($data);
            return response()->json([
                'msg' => 'Halaman statis berhasil ditambahkan',
                'data' => $Statis
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
     * @param  \App\Models\Statis  $halaman_stati
     * @return \Illuminate\Http\Response
     */
    public function show(Statis $halaman_stati)
    {
        return response()->json([
            'msg' => 'Data halaman statis',
            'data' => $halaman_stati
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Statis  $halaman_stati
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Statis $halaman_stati)
    {
        $rule = [
            'aktif' => 'required',
            'isi_text' => 'required',
        ];

        if ($request->title != $halaman_stati->title) {
            $rule['title'] = 'required|unique:statis';
        }

        $validate = Validator::make($request->all(), $rule);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {

            $data = $request->all();
            $data['link'] = Str::slug($request->title);
            unset($data['_method']);
            unset($data['created_at']);
            unset($data['updated_at']);

            $Statis = Statis::where('id', $halaman_stati->id)->update($data);
            return response()->json([
                'msg' => 'Halaman statis berhasil diperbarui',
                'data' => Statis::where('id', $halaman_stati->id)->first()
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
     * @param  \App\Models\Statis  $halaman_stati
     * @return \Illuminate\Http\Response
     */
    public function destroy(Statis $halaman_stati)
    {
        Statis::destroy($halaman_stati->id);

        return response()->json([
            'msg' => 'Halaman statis berhasil dihapus',
            'data' => $halaman_stati
        ], Response::HTTP_OK);
    }
}
