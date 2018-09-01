@extends('master')

@section('name', 'Error!')

@section('content')
  <div class="row top-buffer">
    <div class="col-12">
      <div class="jumbotron">
        <h1 class="display-4">Error!</h1>
        <p class="lead">{{ $error_text }}</p>
        <hr class="my-4">
        <p>Please go back to previous page or click another button to go to the front page</p>
        <a class="btn btn-primary btn-lg" href="javascript:history.back()" role="button">Back</a>
        <a class="btn btn-light btn-lg" href="{{ route('home') }}" role="button">Home</a>
      </div>
    </div>
  </div>
@endsection