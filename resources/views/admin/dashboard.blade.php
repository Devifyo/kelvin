@extends('layouts.admin')

@section('title', 'Overview Dashboard')

@push('styles')
<link href="{{ asset('css/admin/dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('css/admin/visitor-analytics.css') }}" rel="stylesheet">
@endpush

@section('content')

    {{-- This calls the Livewire component containing your stats and list --}}
    <livewire:admin.dashboard-overview />

@endsection