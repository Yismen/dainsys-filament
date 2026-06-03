@extends('layouts.auth')

@section('title', 'Reset Password - Dainsys')

@section('content')
    @livewire('auth.reset-password', ['token' => $token, 'email' => $email])
@endsection
