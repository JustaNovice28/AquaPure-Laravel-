@extends('layouts.app')

@section('title', 'AquaPure – Water Refilling Station')

@section('content')
    @include('partials.hero')
    @include('partials.services')
    @include('partials.order-form')
    @include('partials.contact')
@endsection