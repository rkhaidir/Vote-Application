@extends('layouts.main')

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="float-start m-0 mt-1 p-0">Poll Management</h5>
    <a href="{{ route('poll.create') }}" class="btn btn-sm btn-primary float-end">New Poll</a>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      @if (session('success'))
        <div class="alert alert-success my-3">
          {{ session('success') }}
        </div>
      @endif
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Deadline</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($poll as $data)
              <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $data->title }}</td>
                <td>{{ $data->deadline }}</td>
                <td>
                  <form action="{{ route('poll.delete', $data->id) }}" method="POST">
                    <a href="{{ route('poll.edit', $data->id) }}" class="btn btn-warning btn-sm mr-1">Edit</a>
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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