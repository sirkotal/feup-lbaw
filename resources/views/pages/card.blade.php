@extends('layouts.app')

@section('title', $card->name)
@include('headers.header')
@section('header')
    @yield('header')
@endsection

@section('content')
    <section id="cards">
        @include('partials.card', ['card' => $card])
    </section>
@endsection