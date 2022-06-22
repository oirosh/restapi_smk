<?php

namespace App\Http\Controllers;

use App\Models\Dropdown;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class DropdownController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dropdown = Dropdown::latest()->with('menu')->get();

        return response()->json([
            'msg' => 'Data dropdown',
            'data' => $dropdown
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
            'menu_id' => 'required',
            'title' => 'required|unique:dropdowns',
            'link' => 'required',
            'aktif' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $Dropdown = Dropdown::create($request->all());
            return response()->json([
                'msg' => 'Dropdown berhasil ditambahkan',
                'data' => Dropdown::where('id', $Dropdown->id)->with('menu')->first()
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
     * @param  \App\Models\Dropdown  $dropdown
     * @return \Illuminate\Http\Response
     */
    public function show(Dropdown $dropdown)
    {
        $dropdown = Dropdown::where('id', $dropdown->id)->with('menu')->first();
        return response()->json([
            'msg' => 'Data dropdown',
            'data' => $dropdown
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dropdown  $dropdown
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dropdown $dropdown)
    {
        $rule = [
            'menu_id' => 'required',
            'link' => 'required',
            'aktif' => 'required',
        ];
        if ($request->title != $dropdown->title) {
            $rule['title'] = 'required|unique:dropdowns';
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
            unset($data['menu']);

            $Dropdown = Dropdown::where('id', $dropdown->id)->update($data);
            return response()->json([
                'msg' => 'Dropdown berhasil diperbarui',
                'data' => Dropdown::where('id', $dropdown->id)->with('menu')->first()
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
     * @param  \App\Models\Dropdown  $dropdown
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dropdown $dropdown)
    {
        Dropdown::destroy($dropdown->id);

        return response()->json([
            'msg' => 'Dropdown berhasil diperbarui',
            'data' => $dropdown
        ], Response::HTTP_OK);
    }
}
