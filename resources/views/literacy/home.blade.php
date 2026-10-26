@extends('layouts.app', ['navActive' => 'beranda'])

@section('title', 'My Rubel Literacy Center – Pasaman Barat')
@section('description', 'My Rubel Literacy Center Pasaman Barat: program literasi, prestasi, karya siswa, dan event.')
@section('wa_message', 'Halo My Rubel, saya mau tanya info Literacy Center.')

@section('content')
@include('home.hero')
@isset($sections['program']) @include('home.program') @endisset
@isset($sections['prestasi']) @include('home.prestasi') @endisset
@foreach (array_keys(\App\Models\Work::TYPES) as $type)
  @isset($sections[$type])
    @include('literacy.works-section', ['type' => $type, 'items' => $works[$type], 'bg' => $sections[$type]])
  @endisset
@endforeach
@isset($sections['event']) @include('home.event') @endisset
@isset($sections['testimoni']) @include('home.testimoni') @endisset
@include('home.ikuti')
@endsection
