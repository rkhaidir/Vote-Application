@extends('layouts.main')

@section('content')
<div class="card shadow">
  <div class="card-header">
    <h5 class="float-start m-0 mt-1 p-0">New Users</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('user.store') }}" method="POST">
      @if (session('status'))
        <div class="alert alert-danger my-2" role="alert">
          {{ session('status') }}
        </div>
      @endif
      @csrf
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" class="form-control">
        @if ($errors->has('username'))
          <span class="text-danger">{{ $errors->first('username') }}</span>
        @endif
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control">
        @if ($errors->has('password'))
          <span class="text-danger">{{ $errors->first('password') }}</span>
        @endif
      </div>
      <div class="form-group">
        <label for="role">Role</label>
        <select name="role" id="role" class="form-select">
          <option value="" selected disabled>Choose Role</option>
          <option value="admin">Admin</option>
          <option value="user">User</option>
        </select>
        @if ($errors->has('role'))
          <span class="text-danger">{{ $errors->first('role') }}</span>
        @endif
      </div>
      <div class="form-group">
        <label for="division_id">Division</label>
        <select name="division_id" id="division_id" class="form-select">
          <option value="" selected disabled>Choose Division</option>
          @foreach ($division as $item)
              <option value="{{ $item->id }}">{{ $item->name }}</option>
          @endforeach
        </select>
        @if ($errors->has('division_id'))
          <span class="text-danger">{{ $errors->first('division_id') }}</span>
        @endif
      </div>
      <div class="form-group mt-3">
        <a href="{{ route('user.index') }}" class="btn btn-dark">Back</a>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
  </div>
</div>
@endsection