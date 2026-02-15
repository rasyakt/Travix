@extends('layouts.app')

@section('title', 'Check-In')

@section('content')
    @livewire('check-in-process', ['bookingId' => $booking->id])
@endsection