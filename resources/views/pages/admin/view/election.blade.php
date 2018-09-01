@extends('master')

@section('name', 'Admin · Elections · View')

@section('content')
  <div class="row top-buffer">
    <div class="col-md-8 col-sm-12">
      <div class="card">
        <div class="card-body">
          <h2>Viewing {{ $election["election_name"] }}</h2>
          <div class="row top-buffer">
            <div class="col-md-6 col-sm-12">
              <div class="card">
                <div class="card-body">
                  <h4>Information</h4>
                  <dl class="row">
                    <dt class="col-sm-6">Election Name</dt>
                    <dd class="col-sm-6">{{ $election["election_name"] }}</dd>
                    <dt class="col-sm-6">Start Date</dt>
                    <dd class="col-sm-6">{{ \Carbon\Carbon::createFromTimeString($election["election_start"], 'UTC')->setTimezone('Asia/Bangkok') }}</dd>
                    <dt class="col-sm-6">Start End</dt>
                    <dd class="col-sm-6">{{ \Carbon\Carbon::createFromTimeString($election["election_end"], 'UTC')->setTimezone('Asia/Bangkok') }}</dd>
                  </dl>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-sm-12">
              <div class="card">
                <div class="card-body">
                  <h4>Quick Actions</h4>
                  <a href="{{ route('admin.elect.edit.page', ['elect' => $election["election_id"]]) }}" class="btn btn-primary btn-block">Edit</a>
                  <button type="button" class="btn btn-outline-danger btn-block" data-toggle="modal" data-target="#Modal">Delete</button>
                  <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="ModalLabel">Confirmation</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          Are you sure to continue?
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                          <form action="{{ route('admin.elect.delete', ['elect' => $election["election_id"]]) }}" method="post">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger">Yes</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row top-buffer">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <h4>Candidates</h4>
                  <table class="table">
                    <thead>
                      <tr>
                        <th class="col">Name</th>
                        <th class="col">Voting Count</th>
                      </tr>
                    </thead>
                    <tbody>
                      @isset ($candidates)
                        @foreach ($candidates as $candidate)
                          <tr>
                            <td>{{ $candidate["candidate_name"] }}</td>
                            <td>
                              @php
                                $count = 0;
                                foreach($votes as $vote) {
                                  if ($vote["candidate_id"] === $candidate["candidate_id"])
                                    $count++;
                                }
                                echo $count;
                              @endphp
                            </td>
                          </tr>
                        @endforeach
                      @endisset
                    </tbody>
                  </table>
                </div>
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