<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-gray-200 bg-white shadow-sm">
    <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14">
            <!-- Nav links -->
            <div class="flex">
                <div class="hidden sm:flex sm:items-stretch sm:gap-1">
                    <x-nav-link :href="Auth::user()->isAdmin() ? route('admin.dashboard') : route('dashboard')"
                        :active="Auth::user()->isAdmin() ? request()->routeIs('admin.dashboard') : request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>

                    @if (Auth::user()->isAdmin())
                        <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                            Laporan
                        </x-nav-link>
                        <x-nav-link :href="route('admin.recap.annual')" :active="request()->routeIs('admin.recap.annual', 'admin.recap.monthly')">
                            Rekapitulasi
                        </x-nav-link>
                        <x-nav-link :href="route('admin.recap.tracking')" :active="request()->routeIs('admin.recap.tracking')">
                            Kepatuhan
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('reports.create')" :active="request()->routeIs('reports.create')">
                            Laporan Baru
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- User dropdown -->
            <div class="hidden sm:flex sm:items-center sm:gap-3">
                <span class="inline-flex items-center rounded-full bg-emas-100 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-kumham-800">
                    {{ Auth::user()->isAdmin() ? 'Admin' : 'Notaris' }}
                </span>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 text-sm font-semibold text-kumham-800 hover:text-kumham-600 focus:outline-none transition">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-kumham-700 text-xs font-bold text-white">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')">Profil</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                Keluar
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-kumham-800 hover:bg-kumham-50 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-200">
        <div class="space-y-1 px-4 pt-2 pb-3">
            <x-responsive-nav-link :href="Auth::user()->isAdmin() ? route('admin.dashboard') : route('dashboard')"
                :active="Auth::user()->isAdmin() ? request()->routeIs('admin.dashboard') : request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>

            @if (Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">Laporan</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.recap.annual')" :active="request()->routeIs('admin.recap.*')">Rekapitulasi</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.recap.tracking')" :active="request()->routeIs('admin.recap.tracking')">Kepatuhan</x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('reports.create')" :active="request()->routeIs('reports.create')">Laporan Baru</x-responsive-nav-link>
            @endif
        </div>

        <div class="border-t border-gray-200 pt-4 pb-3">
            <div class="flex items-center gap-3 px-4">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-kumham-700 text-sm font-bold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">Profil</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Keluar</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
