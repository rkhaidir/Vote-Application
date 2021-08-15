<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Division;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $division = Division::all();
        return view('admin.division.index', compact('division'), [
            'title' => 'Division',
        ])->with('i');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.division.create', [
            'title' => 'Create Division'
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
            'name' => 'required|max:255'
        ];

        $message = [
            'name.required' => 'Name is required',
            'name.max' => 'Max length 255 character'
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $success = Division::create($request->all());

        if ($success) {
            return redirect()->route('division.index')->with('success', 'Division Created');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $division = Division::findOrFail($id);
        return view('admin.division.edit', compact('division'), [
            'title' => 'Edit Division'
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
        $division = Division::findOrFail($id);

        $rules = [
            'name' => 'required|max:255'
        ];

        $message = [
            'name.required' => 'Name is required',
            'name.max' => 'Max length 255'
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $success = $division->update([
            'name' => $request->name
        ]);

        if ($success) {
            return redirect()->route('division.index')->with('success', 'Division Edited');
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
        $division = Division::findOrFail($id);

        $success = $division->delete();

        if ($success) {
            return redirect()->route('division.index')->with('success', 'Division Deleted');
        } else {
            return redirect()->back()->withInput()->with('status', 'Delete Failed!');
        }
    }
}
