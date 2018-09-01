@extends('master')

@section('name', 'Admin · Elections · Edit')

@section('content')
  <div class="row top-buffer">
    <div class="col-md-8 col-sm-12">
      <div class="card">
        <div class="card-body">
          <h2>Editing {{ $election["election_name"] }}</h2>
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
                  <a href="{{ route('admin.elect.show', ['elect' => $election["election_id"]]) }}" class="btn btn-primary btn-block">View</a>
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
                  <h4>Elections</h4>
                  <form action="{{ route('admin.elect.edit.sys', ['elect' => $election["election_id"]]) }}" method="post">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="inputelectionid">Election ID</label>
                        <input type="text" class="form-control" id="inputelectionid" placeholder="{{ $election["election_id"] }}" readonly>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="inputelectionname">Election Name</label>
                        <input type="text" class="form-control" id="inputelectionname" placeholder="Election Name" name="election_name" value="{{ $election["election_name"] }}" required aria-required="true">
                      </div>
                      <div class="form-group col-12">
                        <label for="inputelectionstart">Event Start</label>
                        <input type="text" class="form-control" id="inputelectionstart" placeholder="Election Start" name="election_start" value="{{ \Carbon\Carbon::createFromTimeString($election["election_start"], 'UTC')->setTimezone('Asia/Bangkok') }}" required aria-required="true">
                      </div>
                      <div class="form-group col-12">
                        <label for="inputelectionend">Event End</label>
                        <input type="text" class="form-control" id="inputelectionend" placeholder="Election End" name="election_end" value="{{ \Carbon\Carbon::createFromTimeString($election["election_end"], 'UTC')->setTimezone('Asia/Bangkok') }}" required aria-required="true">
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                  </form>
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
                        <th class="col">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @isset ($candidates)
                        @foreach ($candidates as $candidate)
                          <tr>
                            <td>{{ $candidate["candidate_name"] }}</td>
                            <td>
                              <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalEdit{{ $candidate["candidate_id"] }}">Edit</button>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#ModalDelete{{ $candidate["candidate_id"] }}"><i class="material-icons">delete</i></button>
                              </div>
                              <div class="modal fade" id="ModalEdit{{ $candidate["candidate_id"] }}" tabindex="-1" role="dialog" aria-labelledby="ModalEdit{{ $candidate["candidate_id"] }}Label" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="ModalEdit{{ $candidate["candidate_id"] }}Label">Editing {{ $candidate["candidate_name"] }}</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <form action="{{ route('admin.candidate.edit.sys', ['elect' => $election["election_id"], 'candidate' => $candidate["candidate_id"]]) }}" method="post">
                                      @csrf
                                      <input type="hidden" name="_method" value="PUT">
                                      <div class="modal-body">
                                        <div class="form-row">
                                          <div class="form-group col-12">
                                            <label for="inputcandidateid{{ $candidate["candidate_id"] }}">Candidate ID</label>
                                            <input type="text" class="form-control" id="inputcandidateid{{ $candidate["candidate_id"] }}" placeholder="{{ $candidate["candidate_id"] }}" readonly>
                                          </div>
                                          <div class="form-group col-12">
                                            <label for="inputcandidatename{{ $candidate["candidate_id"] }}">Candidate Name</label>
                                            <input type="text" class="form-control" id="inputcandidatename{{ $candidate["candidate_id"] }}" placeholder="Candidate Name" name="candidate_name" value="{{ $candidate["candidate_name"] }}" required aria-required="true">
                                          </div>
                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
                              <div class="modal fade" id="ModalDelete{{ $candidate["candidate_id"] }}" tabindex="-1" role="dialog" aria-labelledby="ModalDelete{{ $candidate["candidate_id"] }}Label" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="ModalDelete{{ $candidate["candidate_id"] }}Label">Confirmation</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      Are you sure to continue?
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                      <form action="{{ route('admin.candidate.delete', ['elect' => $election["election_id"], 'candidate' => $candidate["candidate_id"]]) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger">Yes</button>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                        @endforeach
                      @endisset
                    </tbody>
                  </table>
                  <button type="button" class="btn btn-outline-primary btn-block" data-toggle="modal" data-target="#ModalAddCandidate">Add</button>
                  <div class="modal fade" id="ModalAddCandidate" tabindex="-1" role="dialog" aria-labelledby="ModalAddCandidateLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="ModalAddCandidateLabel">Adding Candidate</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form action="{{ route('admin.candidate.add') }}" method="post">
                          @csrf
                          <input type="hidden" name="election_id" value="{{ $election["election_id"] }}">
                          <div class="modal-body">
                            <div class="form-row">
                              <div class="form-group col-12">
                                <label for="inputcandidatenameadd">Candidate Name</label>
                                <input type="text" class="form-control" id="inputcandidatenameadd" placeholder="Candidate Name" name="candidate_name" required aria-required="true">
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
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-12">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h3>Admin Menu</h3>
              <div class="list-group top-buffer">
                <a href="{{ route('admin.home') }}" class="list-group-item list-group-item-action">Admin Console</a>
                <a href="{{ route('admin.elect.all') }}" class="list-group-item list-group-item-action active">Election</a>
                <a href="{{ route('home') }}" class="list-group-item list-group-item-action">Back to home</a>
                <a href="{{ route('logout') }}" class="list-group-item list-group-item-action text-danger">Logout</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      @if(session('statusmsg'))
      <div class="row top-buffer">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h3>Notice!</h3>
              <p>{{ Session::get('statusmsg') }}</p>
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>
@endsection