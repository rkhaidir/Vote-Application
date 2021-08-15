@extends('layouts.main')

@section('content')
  @foreach($polls as $p)
    <div class="card p-4 my-3 position-relative">
      <form action="{{ route('vote.store', $p->id) }}" method="POST">
        @csrf
        <p>Deadline : {{ date('d F Y, H:i', strtotime($p->deadline)) }}</p>
        <h3>{{ $p->title }} </h3>
        <p>{{ $p->description }}</p>
        @php
          $done = DB::table('votes')->where('poll_id', '=', $p->id)->where('user_id', '=', $user->id)->count();
        @endphp
        @foreach($p->choice as $c)
          <div class="form-inline my-1">
            <input type="radio" name="choice_id" id="cc{{ $c->id }}" class="d-inline pt-1" value="{{ $c->id }}">
            @if($done < 1 && $p->deadline > date('Y-m-d H:i:s', strtotime(now())))
            <label class="d-inline ml-2" for="cc{{ $c->id }}">{{ $c->choice }}</label>
            @else
            <label class="d-inline ml-2" for="cc{{ $c->id }}">{{ $c->choice.' ('.($c->poin ?? 0).'%)' }}</label>
            @endif
          </div>
        @endforeach
        <div class="form-group">
          <button type="submit" class="btn btn-primary mt-3 px-3" <?php if ($p->deadline < date('Y-m-d H:i:s', strtotime(now())) || $done > 0 || $user->role === 'admin') {echo 'disabled title="Cant Vote!"';} ?>>Vote</button>
        </div>
      </form>
    </div>
  @endforeach
@endsection