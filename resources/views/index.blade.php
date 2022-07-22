@extends('layout')

@section('title')
    Главная страница
@endsection

@section('content')
    <a href="/settings" hidden>Настройки отслеживания</a>
    <x-widget size="30%"></x-widget>
@endsection
