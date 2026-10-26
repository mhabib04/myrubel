@extends('layouts.app', ['navActive' => 'beranda'])

@section('title', 'Bimbel My Rubel – Rumah Belajar Pertama di Pasaman Barat')
@section('description', 'Bimbel My Rubel Pasaman Barat. Bimbingan belajar TK–SMA, olimpiade & Basic English sejak 2016.')

@section('content')
@include('home.hero')
@include('home.kenapa')
@isset($sections['program']) @include('home.program') @endisset
@isset($sections['prestasi']) @include('home.prestasi') @endisset
@isset($sections['alumni']) @include('home.alumni') @endisset
@isset($sections['testimoni']) @include('home.testimoni') @endisset
@isset($sections['event']) @include('home.event') @endisset
@isset($sections['info']) @include('home.info') @endisset
@isset($sections['galeri']) @include('home.galeri') @endisset
@include('home.tentang')
@isset($sections['pengajar']) @include('home.pengajar') @endisset
@isset($sections['lokasi']) @include('home.lokasi') @endisset
@include('home.ikuti')
@endsection
