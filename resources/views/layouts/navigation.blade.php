<nav x-data="{ open: false }" class="bg-red-600 dark:bg-red-800 border-b border-red-700 dark:border-red-900">
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
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')"
                                class="text-white hover:bg-red-500 px-3 py-2 rounded-md text-lg font-bold {{ request()->routeIs('home') ? 'bg-red-700' : '' }}">
                        {{ __('ADBM Team') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dbi.index')" :active="request()->routeIs('dbi.index')"
                                class="text-white hover:bg-red-500 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dbi.index') ? 'bg-red-700' : '' }}">
                        {{ __('DBI Tool') }}
                    </x-nav-link>
                </div>
                @if(Auth::user()->team_id == 4)
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('admin')"
                                class="text-white hover:bg-red-500 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin') ? 'bg-red-700' : '' }}">
                        {{ __('Administration') }}
                    </x-nav-link>
                </div>
                @endif
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
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

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-white hover:bg-red-500 focus:outline-none focus:bg-red-500 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
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
    /* Custom styles for active tab */
    .active-tab {
        background-color: #b91c1c;
    }
</style>