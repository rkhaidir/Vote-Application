<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Division;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('division')->orderBy('division_id', 'ASC')->get();
            return view('admin.user.index', compact('users'), [
            'title' => 'Users'
        ])->with('i');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $division = Division::orderBy('name', 'ASC')->get();;
        return view('admin.user.create', compact('division'), [
            'title' => 'Create User',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'username'      => 'required',
            'password'      => 'required|min:8',
            'role'          => 'required',
            'division_id'   => 'required'
        ];
    
        $message = [
            'username.required'     => 'Name is required',
            'password.required'     => 'Password is required',
            'password.min'          => 'Min length 8 character',
            'role.required'         => 'Role is required',
            'division_id.required'  => 'Division is required'
        ];
    
        $validator = Validator::make($request->all(), $rules, $message);
    
        if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
    
        $success = User::create([
            'username'      => $request->username,
            'password'      => Hash::make($request->password),
            'role'          => $request->role,
            'division_id'   => $request->division_id
        ]);
    
        if ($success) {
            return redirect()->route('user.index')->with('success', 'User Created');
        } else {
            return redirect()->back()->withInput()->with('status', 'Create Failed!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $division = Division::orderBy('name', 'ASC')->get();
        return view('admin.user.edit', compact('user', 'division'), [
            'title' => 'Edit User'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $password = $user->password;

        $rules = [
            'username'      => 'required',
            'role'          => 'required',
            'division_id'   => 'required'
        ];

        $message = [
            'username.required'     => 'Name is required',
            'role.required'         => 'Role is required',
            'division_id.required'  => 'Division is required'
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        if ($request->password === "") {
        $success = $user->update([
            'username'      => $request->username,
            'password'      => $password,
            'role'          => $request->role,
            'division_id'   => $request->division_id
        ]);
        } else {
        $success = $user->update([
            'username'      => $request->username,
            'password'      => Hash::make($request->password),
            'role'          => $request->role,
            'division_id'   => $request->division_id
        ]);
        }

        if ($success) {
            return redirect()->route('user.index')->with('success', 'User Edited');
        } else {
            return redirect()->back()->withInput()->with('status', 'Edit Failed!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $success = $user->delete();

        if ($success) {
            return redirect()->route('user.index')->with('success', 'User Deleted');
        } else {
            return redirect()->back()->withInput()->with('status', 'Delete Failed!');
        }
    }
}
