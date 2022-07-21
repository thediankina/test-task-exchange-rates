@extends('layout')

@section('title')
    Главная страница
@endsection

@section('content')
    <a href="/settings">Настройки отслеживания</a>
    <x-widget></x-widget>
    <x-settings></x-settings>
@endsection
