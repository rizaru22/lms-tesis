@php
    if (Auth::user()->isGuru()) {
        $route = 'guru.dashboard';
    } else if (Auth::user()->isSiswa()) {
        $route = 'siswa.dashboard';

    } else if (Auth::user()->isOrtu()) {
        $route = 'ortu.dashboard';
    } else {
        $route = 'admin.dashboard';
    }
@endphp

<a href="{{ route($route) }}" class="brand-link" style="text-align: left;">
    <img src="{{ asset('assets/image/logo.png') }}" alt="{{ config('app.name') }} Logo"
        class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text ml-1">{{ config('app.name') }}</span>
</a>
