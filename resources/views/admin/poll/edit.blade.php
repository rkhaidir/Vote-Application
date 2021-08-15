@extends('layouts.main')

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="float-start m-0 mt-1 p-0">New Poll</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('poll.update', $poll->id) }}" method="POST">
      @csrf
      @method('PUT')
      @if (session('status'))
        <div class="alert alert-success my-3">
          {{ session('status') }}
        </div>
      @endif
      <div class="form-group">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" class="form-control" value="{{ $poll->title }}">
        @if ($errors->has('title'))
            <span class="text-danger">{{ $errors->first('title') }}</span>
        @endif
      </div>
      <div class="form-group">
        <label for="description">Description</label>
        <textarea name="description" id="description" rows="5" class="form-control">{{ $poll->description }}</textarea>
        @if ($errors->has('description'))
            <span class="text-danger">{{ $errors->first('description') }}</span>
        @endif
      </div>
      <div class="form-group">
        <label for="deadline">Deadline</label>
        <div class="row">
          <div class="col-6 ml-3">
            <input type="date" name="deadline1" id="dealine1" class="form-control" value="{{ date('Y-m-d', strtotime($poll->deadline)) }}">
          </div>
          <div class="col-6 mr-3">
            <input type="time" name="deadline2" id="dealine2" class="form-control" value="{{ date('H:i', strtotime($poll->deadline)) }}">
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="choices1">Choice 1</label>
        <input type="text" name="choices1" id="choices1" class="form-control" onkeyup="added(1)" value="{{ $ch->choice }}">
        @if ($errors->has('choices1'))
            <span class="text-danger">{{ $errors->first('choices1') }}</span>
        @endif
      </div>

      @foreach ($choice as $data)
        <?php $i = ++$i ?>
        <div class="form-group {{ $i === 1 ? 'd-none': '' }}" id="c{{ $i }}">
          <label for="choices{{ $i }}">Choice {{ $i }}</label>
          <div class="row m-0 p-0">
            <div class="col-10">
              <input type="text" name="choices{{ $i }}" {{ $i === 1 ? 'disable':'' }} id="choices{{ $i }}" class="form-control" value="{{ $data->choice }}" onkeyup="added({{ $i }})">
            </div>
            <div class="col-2">
              <a href="{{ route('poll.del', $data->id) }}" class="btn btn-sm btn-danger">Delete</a>
            </div>
          </div>
        </div>
      @endforeach

      @for($a=1;$a<=4;$a++)
        <div id="gas{{ $a }}"></div>
      @endfor

      <script>
        for (let index = 0; index <= 5; index++) {
          function added(index) {
            let a = index+1;
            let x = document.getElementById(`choices${index}`).value;
            let y = "<div class='form-group'><label for='choices"+a+"'>Choices "+a+"</label><div class='row m-0 p-0'><div class='col-10'><input type='text' name='choices"+a+"' id='choices"+a+"' class='form-control' onchange='added("+a+")'></div><div class='col-2'><button type='button' onclick='deleted("+a+")' class='btn btn-sm btn-danger'>Delete</button></div></div></div>";
            let z = document.getElementById(`choices${a}`);

            if (x !== "") {
              if (!z) {
                document.getElementById(`gas${index}`).innerHTML = y;
              } else {
                document.getElementById(`c${a}`).style.display = 'block';
              }
            } else {
              document.getElementById(`gas${index}`).innerHTML = x;
            }
          }
          
          function deleted(index) {
            let b = index-1;
            let x = document.getElementById(`choices${index}`).value;
            if (x !== "") {
              document.getElementById(`gas${b}`).innerHTML = '';
            } else {
              document.getElementById(`gas${b}`).innerHTML = '';
            }
          }
        }
      </script>
      <div class="form-group mt-3">
        <a href="{{ route('poll') }}" class="btn btn-dark mr-1">Back</a>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
  </div>
</div>
@endsection