@extends('master')

@section('name', 'Admin · Home')

@section('content')
  <div class="row top-buffer">
    <div class="col-md-8 col-sm-12">
      <div class="card">
        <div class="card-body">
          <h2>Quick Actions</h2>
          <p>Displaying last 5 elections that created by <i>you</i></p>
          <table class="table">
              <thead>
                <tr>
                  <th class="col-9">Name</th>
                  <th class="col-3">Actions</th>
                </tr>
            <tbody>
              @isset($elections)
                @foreach ($elections as $election)
                  <tr>
                    <td>{{ $election["election_name"] }}
                      @if (\Carbon\Carbon::parse($election["election_start"])->isFuture())
                      <span class="badge badge-primary">Soon</span>
                      @elseif (\Carbon\Carbon::parse($election["election_end"])->isPast())
                      <span class="badge badge-danger">Closed</span>
                      @else
                      <span class="badge badge-success">Open</span>
                      @endif
                    </td>
                    <td>
                      <a class="btn btn-primary" href="{{ route('admin.elect.show', ['elect' => $election["election_id"]]) }}" role="button">View</a>
                      <a class="btn btn-outline-info" href="{{ route('admin.elect.edit.page', ['elect' => $election["election_id"]]) }}" role="button">Edit</a>
                    </td>
                  </tr>
                @endforeach
              @endisset
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-12">
      <div class="card">
        <div class="card-body">
          <h3>Admin Menu</h3>
          <div class="list-group">
            <a href="{{ route('admin.home') }}" class="list-group-item list-group-item-action active">Admin Console</a>
            <a href="{{ route('admin.elect.all') }}" class="list-group-item list-group-item-action">Election</a>
            <a href="{{ route('home') }}" class="list-group-item list-group-item-action">Back to home</a>
            <a href="{{ route('logout') }}" class="list-group-item list-group-item-action text-danger">Logout</a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection