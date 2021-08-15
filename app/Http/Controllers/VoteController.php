<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vote;
use App\Models\Poll;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class VoteController extends Controller
{
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'choice_id' => ['required']
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('status', 'Vote Failed!');
        }

        $vote = new Vote;
        $vote->choice_id = $request->input('choice_id');
        $vote->user_id = Auth::user()->id;
        $vote->poll_id = $id;
        $vote->division_id = Auth::user()->division_id;

        $vote->save();

        if ($vote) {
            return redirect()->route('home')->with('status', 'Vote Success!');
        } else {
            return redirect()->back()->with('status', 'Vote Failed!');
        }
    }
}
