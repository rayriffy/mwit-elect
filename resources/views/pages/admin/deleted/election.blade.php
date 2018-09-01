@extends('master')

@section('name', 'Admin · Election · Deleted')

@section('content')
  <div class="row top-buffer">
    <div class="col-12">
      <div class="jumbotron">
        <h1 class="display-4">Deleted!</h1>
        <p class="lead">All the record related to this election has been deleted successfully.</p>
        <hr class="my-4">
        <p>Click the button below to go back to the front page.</p>
        <a class="btn btn-primary btn-lg" href="{{ route('admin.home') }}" role="button">Admin Console</a>
      </div>
    </div>
  </div>
@endsection