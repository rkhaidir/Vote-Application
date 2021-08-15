@extends('layouts.main')

@section('content')
<div class="card shadow">
  <div class="card-header">
    <h5 class="float-start m-0 mt-1 p-0">Users Management</h5>
    <a href="{{ route('user.create') }}" class="btn btn-primary float-end btn-sm">New User</a>
  </div>
  <div class="card-body">
    @if (session('success'))
      <div class="alert alert-success my-2" role="alert">
        {{ session('success') }}
      </div>
    @endif
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Division</th>
            <th>Role</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $item)
              <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $item->username }}</td>
                <td>{{ $item->division->name }}</td>
                <td>{{ Str::upper($item->role)  }}</td>
                <td>
                  <form action="{{ route('user.destroy', $item->id) }}" method="POST">
                    <a href="{{ route('user.edit', $item->id) }}" class="btn btn-sm btn-warning mr-1">Edit</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection