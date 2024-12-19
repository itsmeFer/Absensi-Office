<!-- Navigation Links -->
<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
    @if(auth()->user()->isAdmin())
        <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
            {{ __('Admin Dashboard') }}
        </x-nav-link>
    @elseif(auth()->user()->isManager())
        <x-nav-link href="{{ route('team.attendance') }}" :active="request()->routeIs('team.attendance')">
            {{ __('Team Attendance') }}
        </x-nav-link>
    @else
        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-nav-link>
    @endif
</div>