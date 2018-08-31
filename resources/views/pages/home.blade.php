@extends('master')

@section('name', 'Home')

@section('content')
  <div class="row top-buffer">
    <div class="col-md-8 col-sm-12">
      <div class="card">
        <div class="card-body">
          <h2>Home</h2>
          <p>Election list in the last 30 days</p>
          <table class="table">
            <thead>
              <tr>
                <th class="col">Name</th>
                <th class="col">Status</th>
              </tr>
            </thead>
            <tbody>
              @isset($elections)
                @foreach ($elections as $election)
                <tr>
                  <td>
                    {{ $election["election_name"] }}
                  </td>
                  <td>
                    @if (\App\VOTE::where('election_id', $election["election_id"])->where('ticket_id', Crypt::decryptString(Cookie::get('ticketdata')))->exists())
                    <span class="badge badge-secondary">Voted</span>
                    @elseif (\Carbon\Carbon::parse($election["election_start"])->isFuture())
                    <span class="badge badge-primary">Soon</span>
                    @elseif (\Carbon\Carbon::parse($election["election_end"])->isPast())
                    <span class="badge badge-danger">Closed</span>
                    @else
                    <span class="badge badge-success">Open</span>
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
          @if (Cookie::get('ticketdata') === null)
            <h3>Ticket</h3>
            @if (session('errorcode'))
              @if (Session::get('errorcode') == 7001)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Boom!</strong> Invalid ticket
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              @elseif (Session::get('errorcode') == 7002)
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  <strong>Whoops!</strong> Please login first
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              @elseif (Session::get('errorcode') == 7003)
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  <strong>Whoops!</strong> This ticket is invalid or expired
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              @endif
            @endif
            <form action="{{ route('auth') }}" method="post">
              @csrf
              <div class="input-group">
                <input type="text" name="ticket" class="form-control" placeholder="Ticket ID" aria-label="Ticket ID" aria-describedby="basic-addon2" required aria-required="true">
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="submit">Login</button>
                </div>
              </div>
            </form>
          @else
            <h3>Menu</h3>
            <div class="list-group">
              <a href="#" class="list-group-item list-group-item-action active">Home</a>
              <a href="{{ route('user.elect') }}" class="list-group-item list-group-item-action">Election</a>
              <a href="{{ route('logout') }}" class="list-group-item list-group-item-action">Logout</a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection