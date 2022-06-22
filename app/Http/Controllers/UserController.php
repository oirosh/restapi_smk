<?php

namespace App\Http\Controllers;

use App\Models\Testimoni;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::latest()->with('testimoni')->get();

        return response()->json([
            'msg' => 'Data user',
            'data' => $user
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
            'nama' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'level' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = $request->all();
            $data['password'] = Hash::make($request->password);

            $User = User::create($data);
            return response()->json([
                'msg' => 'User berhasil ditambahkan',
                'data' => $User
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json([
            'msg' => 'Data user',
            'data' => $user
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rule = [
            'nama' => 'required',
            'password' => 'required',
            'level' => 'required'
        ];

        if ($request->email != $user->email) {
            $rule['email'] = 'required|unique:users';
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

            if ($request->password != $user->password) {
                $data['password'] = Hash::make($request->password);
            }

            $User = User::where('id', $user->id)->update($data);
            return response()->json([
                'msg' => 'User berhasil diperbarui',
                'data' => User::where('id', $user->id)->first()
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        foreach (Testimoni::where('user_id', $user->id)->get() as $test) {
            User::destroy($test->id);
        }

        User::destroy($user->id);

        return response()->json([
            'msg' => 'User berhasil diperbarui',
            'data' => $user
        ], Response::HTTP_OK);
    }
}
