<?php

namespace App\Http\Controllers;

use App\Models\Dropdown;
use App\Models\Menu;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu = Menu::latest()->with('dropdown')->get();

        return response()->json([
            'msg' => 'Data menu',
            'data' => $menu
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
            'title' => 'required|unique:menus',
            'link' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $Menu = Menu::create($request->all());
            return response()->json([
                'msg' => 'Menu berhasil ditambahkan',
                'data' => $Menu
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
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        return response()->json([
            'msg' => 'Data menu',
            'data' => $menu
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        $rule = [
            'link' => 'required'
        ];

        if ($request->title != $menu->title) {
            $rule['title'] = 'required|unique:menus';
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
            unset($data['dropdown']);

            $Menu = Menu::where('id', $menu->id)->update($data);
            return response()->json([
                'msg' => 'Menu berhasil diperbarui',
                'data' => Menu::where('id', $menu->id)->first()
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
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        foreach (Dropdown::where('menu_id', $menu->id)->get() as $child) {
            Dropdown::destroy($child->id);
        }
        Menu::destroy($menu->id);

        return response()->json([
            'msg' => 'Menu berhasil dihapus',
            'data' => $menu
        ], Response::HTTP_OK);
    }
}
