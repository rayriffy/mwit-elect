@extends('master')

@section('name', 'Election')

@section('content')
  <div class="row top-buffer">
    <div class="col-md-8 col-sm-12">
      <div class="card">
        <div class="card-body">
          <h2>Election</h2>
          <p>Election list in the last 30 days</p>
          <table class="table">
            <thead>
              <tr>
                <th class="col">Name</th>
                <th class="col">Start</th>
                <th class="col">End</th>
                <th class="col">Action</th>
              </tr>
            </thead>
            <tbody>
              @isset($elections)
                @foreach ($elections as $election)
                <tr>
                  <td>
                    {{ $election["election_name"] }}
                    @if (\App\VOTE::where('election_id', $election["election_id"])->where('ticket_id', \Illuminate\Support\Facades\Crypt::decryptString(Cookie::get('ticketdata')))->exists())
                    <span class="badge badge-secondary">Voted</span>
                    @elseif (\Carbon\Carbon::parse($election["election_start"])->isFuture())
                    <span class="badge badge-primary">Soon</span>
                    @elseif (\Carbon\Carbon::parse($election["election_end"])->isPast())
                    <span class="badge badge-danger">Closed</span>
                    @else
                    <span class="badge badge-success">Open</span>
                    @endif
                  </td>
                  <td>{{ \Carbon\Carbon::createFromTimeString($election["election_start"], 'UTC')->setTimezone('Asia/Bangkok') }}</td>
                  <td>{{ \Carbon\Carbon::createFromTimeString($election["election_end"], 'UTC')->setTimezone('Asia/Bangkok') }}</td>
                  <td>
                    @if (\App\VOTE::where('election_id', $election["election_id"])->where('ticket_id', \Illuminate\Support\Facades\Crypt::decryptString(Cookie::get('ticketdata')))->exists())
                    <button type="button" class="btn btn-primary" disabled>Vote</button>
                    @elseif (\Carbon\Carbon::parse($election["election_start"])->isFuture())
                    <button type="button" class="btn btn-primary" disabled>Vote</button>
                    @elseif (\Carbon\Carbon::parse($election["election_end"])->isPast())
                    <button type="button" class="btn btn-primary" disabled>Vote</button>
                    @else
                    <a class="btn btn-primary" href="{{ route('user.vote.page', ['elect' => $election["election_id"]]) }}" role="button">Vote</a>
                    @endif
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
          <h3>Menu</h3>
          <div class="list-group">
            <a href="{{ route('home') }}" class="list-group-item list-group-item-action">Home</a>
            <a href="#" class="list-group-item list-group-item-action active">Election</a>
            <a href="{{ route('logout') }}" class="list-group-item list-group-item-action text-danger">Logout</a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection