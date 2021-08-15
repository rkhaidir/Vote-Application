<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Poll;
use App\Models\Choice;

class PollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $poll = Poll::all();
        $polls = Poll::orderBy('created_at', 'DESC')->first();
        return view('admin.poll.index', compact('poll', 'polls'), [
            'title' => 'Polling'
        ])->with('i');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.poll.create', [
            'title' => 'New Polling'
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
            'title'         => 'required',
            'description'   => 'required',
            'deadline1'     => 'required',
            'deadline2'     => 'required',
            'choices1'      => 'required'
        ];

        $message = [
            'title.required'    => 'Title is required',
            'description'       => 'Description is required',
            'deadline1'         => 'Date is required',
            'deadline2'         => 'Time is required',
            'choices1'          => 'Choices is required',

        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $poll = Poll::create([
            'title'         => $request->title,
            'description'   => $request->description,
            'deadline'      => $request->deadline1.' '.$request->deadline2,
            'created_by'     => Auth::user()->id
        ]);

        if ($poll) {
            $poll_id = Poll::findOrFail($poll->id);
            for ($i=1; $i <= 5; $i++) {
                if($request->input('choices'.$i)) {
                    $choice = Choice::create([
                        'choice'   =>  $request->input('choices'.$i),
                        'poll_id'   =>  $poll_id->id
                    ]);
                }
            }
            return redirect()->route('poll')->with('success', 'Poll Created');
            if ($choice) {
                return redirect()->route('poll')->with('success', 'Poll Created');
            } else {
                return redirect()->back()->withInput()->with('status', 'Create Poll Failed!');
            }
        } else {
            return redirect()->back()->withInput()->with('status', 'Create Poll Failed!');
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
        $poll = Poll::findOrFail($id);
        $ch = Choice::where('poll_id', '=', $id)->first();
        $choice = Choice::where('poll_id', '=', $id)->get();
        $count = Choice::where('poll_id', '=', $id)->count();
        return view('admin.poll.edit', compact('poll', 'choice', 'count', 'ch'), [
            'title' => 'Edit Poll'
        ])->with('i');
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
        $poll = Poll::findOrFail($id);

        $rules = [
            'title'         => 'required',
            'description'   => 'required',
            'deadline1'     => 'required',
            'deadline2'     => 'required',
            'choices1'      => 'required'
        ];

        $message = [
            'title.required'    => 'Title is required',
            'description'       => 'Description is required',
            'deadline1'         => 'Date is required',
            'deadline2'         => 'Time is required',
            'choices1'          => 'Choices is required',

        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $update = $poll->update([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline1.' '.$request->deadline2
        ]);

        if ($update) {
            $choice = Choice::where('poll_id', '=', $id)->get();
            $count = Choice::where('poll_id', '=', $id)->count();
            $i = 1;
            foreach ($choice as $c) {
                $b = $c->id;
                $z = $i++;
                $ch = Choice::findOrFail($b);
                $updateChoice = $ch->update([
                    'choice' => $request->input('choices'.$z),
                    'poll_id' => $id
                ]);
            }
            $create = $count + 1;
            if ($request->input('choices'.$create)) {
                $createChoice = Choice::create([
                    'choice' => $request->input('choices'.$create),
                    'poll_id' => $id
                ]);
            }
            return redirect()->route('poll')->with('success', 'Poll Updated');
        } else {
            return redirect()->back()->with('status', 'Update Poll Failed');
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
        $poll = Poll::findOrFail($id);
        $success = $poll->delete();

        if ($success) {
            return redirect()->route('poll')->with('success', 'Poll Deleted');
        } else {
            return redirect()->back()->withInput()->with('status', 'Delete Poll Failed!');
        }
    }

    public function del($id) 
    {
        $c = Choice::findOrFail($id);

        $success = $c->delete();

        if ($success) {
            return redirect()->back()->withInput();
        } else {
            return redirect()->back()->withInput();
        }
    }
}
