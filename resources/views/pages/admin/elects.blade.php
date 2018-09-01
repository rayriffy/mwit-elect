@extends('master')

@section('name', 'Admin · Elections')

@section('content')
  <div class="row top-buffer">
    <div class="col-md-8 col-sm-12">
      <div class="card">
        <div class="card-body">
          <h2>Elections</h2>
          <p>Displaying <i>ALL</i> elections that created by <i>you</i></p>
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
          <button type="button" class="btn btn-outline-primary btn-block" data-toggle="modal" data-target="#ModalAddElection">Add</button>
          <div class="modal fade" id="ModalAddElection" tabindex="-1" role="dialog" aria-labelledby="ModalAddElectionLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalAddElectionLabel">Adding Election</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="{{ route('admin.elect.add') }}" method="post">
                  @csrf
                  <div class="modal-body">
                    <div class="form-row">
                      <div class="form-group col-12">
                        <label for="inputelectionname">Election Name</label>
                        <input type="text" class="form-control" id="inputelectionname" placeholder="Election Name" name="election_name" required aria-required="true">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="inputelectionstart">Election Start</label>
                        <input type="text" class="form-control" id="inputelectionstart" placeholder="Election Start" aria-describedby="inputelectionstartHelp" name="election_start" required aria-required="true">
                        <small id="inputelectionstartHelp" class="form-text text-muted">Please type in following format (YYYY-MM-DD HH:MM:SS). Ex. 2018-09-30 14:39:37</small>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="inputelectionend">Election End</label>
                        <input type="text" class="form-control" id="inputelectionend" placeholder="Election End" aria-describedby="inputelectionendHelp" name="election_end" required aria-required="true">
                        <small id="inputelectionendHelp" class="form-text text-muted">Please type in following format (YYYY-MM-DD HH:MM:SS). Ex. 2018-09-30 14:39:37</small>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-12">
      <div class="card">
        <div class="card-body">
          <h3>Admin Menu</h3>
          <div class="list-group">
            <a href="{{ route('admin.home') }}" class="list-group-item list-group-item-action">Admin Console</a>
            <a href="{{ route('admin.elect.all') }}" class="list-group-item list-group-item-action active">Election</a>
            <a href="{{ route('home') }}" class="list-group-item list-group-item-action">Back to home</a>
            <a href="{{ route('logout') }}" class="list-group-item list-group-item-action text-danger">Logout</a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection