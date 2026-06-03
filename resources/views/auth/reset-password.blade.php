@extends('layouts.auth')

@section('title', 'Reset Password - DainSys')

@section('content')
    @livewire('auth.reset-password', ['token' => $token, 'email' => $email])
@endsection
