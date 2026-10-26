@extends('layouts.app', ['navActive' => 'beranda'])

@section('title', 'My Rubel Motivation & Outbound Center – Pasaman Barat')
@section('description', 'My Rubel Motivation & Outbound Center Pasaman Barat: program motivasi dan outbound, event, dan testimoni.')
@section('wa_message', 'Halo My Rubel, saya mau tanya info Motivation & Outbound Center.')

@section('content')
@include('home.hero')
@isset($sections['program']) @include('home.program') @endisset
@isset($sections['event']) @include('home.event') @endisset
@isset($sections['testimoni']) @include('home.testimoni') @endisset
@include('home.ikuti')
@endsection
