@extends('layouts.app')

@section('content')


    <dashboard :auth="{{ json_encode( auth()->user()) }}"></dashboard>

@endsection
