@php
    $campuses = \App\Models\Campus::where('is_active', true)->orderBy('name')->get();
@endphp
@include('auth.auth', ['isLogin' => false, 'campuses' => $campuses])
