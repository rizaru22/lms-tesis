<aside class="main-sidebar sidebar-dark-primary elevation-4">
    @if (Auth::user()->isAdmin())
        @include('layouts.sub-dashboard.sidebar.admin')
    @elseif (Auth::user()->isGuru())
        @include('layouts.sub-dashboard.sidebar.guru')
    @elseif (Auth::user()->isSiswa())
        @include('layouts.sub-dashboard.sidebar.siswa')
    {{-- @elseif (Auth::user()->isKepsek())
        @include('layouts.sub-dashboard.sidebar.kepsek') --}}
    @elseif (Auth::user()->isOrtu())
    @include('layouts.sub-dashboard.sidebar.ortu')
    @endif

</aside>

@push('js')
    <script>
        $(document).ready(function() {
            // Set sidebar scroll position from local storage
            let item = `${noIndukUser}_sidebarScrollTop`;
            let storedScrollTop = localStorage.getItem(item);
            if (storedScrollTop) {
                $('.sidebar').scrollTop(storedScrollTop);
            }

            // Set sidebar scroll position to local storage on scroll
            $('.sidebar').scroll(function() {
                let scrollTop = $(this).scrollTop();
                localStorage.setItem(item, scrollTop);
            });
        });
    </script>
@endpush
