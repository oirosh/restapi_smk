<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profil = Profil::first();

        return response()->json([
            'msg' => 'Data profil',
            'data' => $profil
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
            'nama_pendidikan' => 'required',
            'nama_sekolah' => 'required',
            'slogan' => 'required',
            'singkatan' => 'required',
            'logo' => 'required|image|file|max:2048',
            'icon' => 'required|image|file|max:2048',
            'banner' => 'required|image|file|max:2048',
            'npsn' => 'required',
            'sambutan' => 'required',
            'perkenalan' => 'required',
            'alamat' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $logo = $request->file('logo')->store('profil');
            $icon = $request->file('icon')->store('profil');
            $banner = $request->file('banner')->store('profil');

            $data = $request->all();
            $data['logo'] = $logo;
            $data['icon'] = $icon;
            $data['banner'] = $banner;

            $Profil = Profil::create($data);
            return response()->json([
                'msg' => 'Profil berhasil ditambahkan',
                'data' => $Profil
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
     * @param  \App\Models\Profil  $profil
     * @return \Illuminate\Http\Response
     */
    public function show(Profil $profil)
    {
        return response()->json([
            'msg' => 'Data profil',
            'data' => $profil
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profil  $profil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profil $profil)
    {
        $validate = Validator::make($request->all(), [
            'nama_pendidikan' => 'required',
            'nama_sekolah' => 'required',
            'slogan' => 'required',
            'singkatan' => 'required',
            'logo' => 'image|file|max:2048',
            'icon' => 'image|file|max:2048',
            'banner' => 'image|file|max:2048',
            'npsn' => 'required',
            'sambutan' => 'required',
            'perkenalan' => 'required',
            'alamat' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $logo = $profil->logo;
            $icon = $profil->icon;
            $banner = $profil->banner;
            if ($request->file('logo')) {
                Storage::delete($logo);

                $logo = $request->file('logo')->store('profil');
            }
            if ($request->file('icon')) {
                Storage::delete($icon);

                $icon = $request->file('icon')->store('profil');
            }
            if ($request->file('banner')) {
                Storage::delete($banner);

                $banner = $request->file('banner')->store('profil');
            }

            $data = $request->all();
            unset($data['_method']);
            unset($data['created_at']);
            unset($data['updated_at']);

            $data['logo'] = $logo;
            $data['icon'] = $icon;
            $data['banner'] = $banner;

            $Profil = Profil::where('id', $profil->id)->update($data);
            return response()->json([
                'msg' => 'Profil berhasil diperbarui',
                'data' => Profil::where('id', $profil->id)->first()
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
     * @param  \App\Models\Profil  $profil
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profil $profil)
    {
        Storage::delete($profil->logo);
        Storage::delete($profil->icon);
        Storage::delete($profil->banner);
        Profil::destroy($profil->id);

        return response()->json([
            'msg' => 'Profil berhasil dihapus',
            'data' => $profil
        ], Response::HTTP_OK);
    }
}
