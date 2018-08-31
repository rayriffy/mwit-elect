@extends('master')

@section('name', 'Home')

@section('content')
  <div class="row top-buffer">
    <div class="col-md-8 col-sm-12">
      <div class="card">
        <div class="card-body">
          <h2>MWIT Election System</h2>
          <p>Currently Avaliable Election</p>
          <table class="table">
            <thead>
              <tr>
                <th class="col">Name</th>
                <th class="col">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Student Commitee #28</td>
                <td><span class="badge badge-success">Avaliable</span></td>
              </tr>
              <tr>
                <td>DSC Commitee #28</td>
                <td><span class="badge badge-info">Closing</span></td>
              </tr>
              <tr>
                <td>Stupid Vote</td>
                <td><span class="badge badge-secondary">Voted</span></td>
              </tr>
              <tr>
                <td>Developer Conf</td>
                <td><span class="badge badge-danger">Unavaliable</span></td>
              </tr>
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
              @endif
            @endif
            <form action="/auth" method="post">
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
              <a href="#" class="list-group-item list-group-item-action">Election</a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection