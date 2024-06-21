<nav x-data="{ open: false }" class="vodafone-nav">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('/images/download.jpg') }}" alt="Logo" class="block h-9 w-auto fill-current text-white" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        {{ __('ADBM Team') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dbi.index')" :active="request()->routeIs('dbi.index')" class="nav-link {{ request()->routeIs('dbi') ? 'active' : '' }}">
                        {{ __('DBI Tool') }}
                    </x-nav-link>
                </div>
                @if(Auth::user()->team_id == 4)
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')" class="nav-link {{ request()->routeIs('admin') ? 'active' : '' }}">
                        {{ __('Administration') }}
                    </x-nav-link>
                </div>
                @endif
            </div>


            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
            <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-white hover:bg-red-500 px-3 py-2 rounded-md text-sm font-medium">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')"
                                    class="text-white hover:bg-red-500 block px-3 py-2 rounded-md text-base font-bold">
                {{ __('ADBM Team') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-red-700">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-red-300">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')"
                                        class="text-white hover:bg-red-500 block px-3 py-2 rounded-md text-base font-medium">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-white hover:bg-red-500 block px-3 py-2 rounded-md text-base font-medium">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    .vodafone-nav {
        background-color: #e60000;
        border-bottom: 1px solid #b60000;
    }

    .vodafone-nav .nav-link {
        color: white;
        font-weight: bold;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        transition: background-color 0.2s;
    }

    .vodafone-nav .nav-link:hover {
        background-color: #b60000;
    }

    .vodafone-nav .nav-link.active {
        background-color: #9e0000;
    }

    .vodafone-nav .dropdown-link {
        color: white;
    }

    .vodafone-nav .dropdown-link:hover {
        background-color: #b60000;
    }

    .vodafone-nav .hamburger-button {
        color: white;
    }

    .vodafone-nav .hamburger-button:hover,
    .vodafone-nav .hamburger-button:focus {
        background-color: #b60000;
    }

    /* Remove the .active-tab class as it's no longer needed */
</style>