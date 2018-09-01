@extends('master')

@section('name', 'Voted')

@section('content')
  <div class="row top-buffer">
    <div class="col-12">
      <div class="jumbotron">
        <h1 class="display-4">Voted!</h1>
        <p class="lead">Record has been saved successfully.</p>
        <hr class="my-4">
        <p>Click the button below to go back to the front page.</p>
        <a class="btn btn-primary btn-lg" href="{{ route('home') }}" role="button">Home</a>
      </div>
    </div>
  </div>
@endsection