@extends('layouts.main')

@section('content')
<div class="card shadow">
  <div class="card-header">
    <h5 class="float-start m-0 mt-1 p-0">New Division</h5>
  </div>
  <div class="card-body">

    <form action="{{ route('division.store') }}" method="POST">
      @csrf
      @if (session('status'))
        <div class="alert alert-danger" role="alert">
          {{ session('status') }}
        </div>
      @endif
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" class="form-control">
        @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
      </div>
      <div class="form-group mt-3">
        <a href="{{ route('division.index') }}" class="btn btn-dark mr-1">Back</a>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
  </div>
</div>
@endsection