@extends('layouts.app')

@section('title', 'AquaPure – Water Refilling Station')

@section('content')
    @include('partials.hero')
    @include('partials.about')
    @include('partials.features')
    @include('partials.services')
    @include('partials.order-form')
    @include('partials.team')
    @include('partials.contact')
@endsection