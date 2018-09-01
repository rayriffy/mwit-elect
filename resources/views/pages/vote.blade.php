@extends('master')

@section('name', 'Voting')

@section('content')
  <div class="row top-buffer">
    <div class="col-md-8 col-sm-12">
      <div class="card">
        <div class="card-body">
          <h2>{{ $election["election_name"] }}</h2>
          <p>Choose following name below.</p>
          <form action="{{ route('user.vote.sys') }}" method="post" class="funkyradio">
            @csrf
            <input type="hidden" name="election_id" value="{{ $election["election_id"] }}">
            <div class="row">
              <div class="col-12">
              @foreach ($candidates as $candidate)
                <div class="funkyradio-success">
                  <input type="radio" name="candidate_id" value="{{ $candidate["candidate_id"] }}" id="{{ $candidate["candidate_id"] }}" required />
                  <label for="{{ $candidate["candidate_id"] }}">{{ $candidate["candidate_name"] }}</label>
                </div>
              @endforeach
              </div>
            </div>
            <div class="row top-buffer">
              <div class="col-12">
                <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#confirmmodal">
                  Vote
                </button>
                <div class="modal fade" id="confirmmodal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
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
                        <button type="submit" class="btn btn-primary">Yes</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-12">
      <div class="card">
        <div class="card-body">
          <h3>Menu</h3>
          <div class="list-group">
            <a href="{{ route('home') }}" class="list-group-item list-group-item-action">Home</a>
            <a href="{{ route('user.elect') }}" class="list-group-item list-group-item-action active">Election</a>
            <a href="{{ route('logout') }}" class="list-group-item list-group-item-action">Logout</a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection