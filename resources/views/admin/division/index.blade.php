@extends('layouts.main')

@section('content')
<div class="card shadow">
  <div class="card-header">
    <h5 class="float-start m-0 mt-1 p-0">Division Management</h5>
    <a href="{{ route('division.create') }}" class="btn btn-primary float-end btn-sm">New Division</a>
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
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($division as $item)
          <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $item->name }}</td>
            <td>
              <form action="{{ route('division.destroy', $item->id) }}" method="POST">
                <a href="{{ route('division.edit', $item->id) }}" class="btn btn-sm btn-warning mr-1">Edit</a>
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