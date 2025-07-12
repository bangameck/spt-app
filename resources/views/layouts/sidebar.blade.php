<aside id="app-menu"
    class="w-sidenav min-w-sidenav bg-white shadow overflow-y-auto hs-overlay fixed inset-y-0 start-0 z-60 hidden -translate-x-full transform transition-all duration-200 hs-overlay-open:translate-x-0 lg:bottom-0 lg:end-auto lg:z-30 lg:block lg:translate-x-0 rtl:translate-x-full rtl:hs-overlay-open:translate-x-0 rtl:lg:translate-x-0 print:hidden [--body-scroll:true] [--overlay-backdrop:true] lg:[--overlay-backdrop:false]">

    <div class="flex flex-col h-full">
        <div class="sticky top-0 flex h-topbar items-center justify-center px-6">
            <a href="{{ route('dashboard') }}"> {{-- Gunakan route('dashboard') untuk default --}}
                <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo" class="flex h-6">
            </a>
        </div>

        <div class="p-4 h-[calc(100%-theme('spacing.topbar'))] flex-grow" data-simplebar>
            <ul class="admin-menu hs-accordion-group flex w-full flex-col gap-1">

                <li class="px-3 py-2 text-xs uppercase font-medium text-default-500">Menu Utama</li>

                {{-- Dashboard Link (Bisa Umum atau Spesifik) --}}
                <li class="menu-item hs-accordion">
                    <a href="javascript:void(0)"
                        class="hs-accordion-toggle group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-600 transition-all hover:bg-primary/5 hs-accordion-active:bg-primary/5 hs-accordion-active:text-primary">
                        <i class="i-lucide-layout-grid size-5"></i>
                        <span class="menu-text"> Dashboards </span>
                        <i class="i-lucide-chevron-right hs-accordion-active:rotate-90 ms-auto size-4"></i>
                    </a>

                    <div class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                        <ul class="mt-1 space-y-1">
                            @auth
                                @if (Auth::user()->isAdmin())
                                    <li class="menu-item">
                                        <a class="flex items-center gap-x-3.5 rounded-md px-3 py-1.5 text-sm font-medium text-default-600 transition-all hover:bg-primary/5"
                                            href="{{ route('admin.dashboard') }}">
                                            <i class="menu-dot"></i>
                                            Dashboard Admin
                                        </a>
                                    </li>
                                @elseif(Auth::user()->isLeader())
                                    <li class="menu-item">
                                        <a class="flex items-center gap-x-3.5 rounded-md px-3 py-1.5 text-sm font-medium text-default-600 transition-all hover:bg-primary/5"
                                            href="{{ route('leader.dashboard') }}">
                                            <i class="menu-dot"></i>
                                            Dashboard Leader
                                        </a>
                                    </li>
                                @elseif(Auth::user()->isFieldCoordinator())
                                    <li class="menu-item">
                                        <a class="flex items-center gap-x-3.5 rounded-md px-3 py-1.5 text-sm font-medium text-default-600 transition-all hover:bg-primary/5"
                                            href="{{ route('field_coordinator.dashboard') }}">
                                            <i class="menu-dot"></i>
                                            Dashboard Koordinator Lapangan
                                        </a>
                                    </li>
                                @elseif(Auth::user()->isStaff())
                                    <li class="menu-item">
                                        <a class="flex items-center gap-x-3.5 rounded-md px-3 py-1.5 text-sm font-medium text-default-600 transition-all hover:bg-primary/5"
                                            href="{{ route('staff.dashboard') }}">
                                            <i class="menu-dot"></i>
                                            Dashboard Staff
                                        </a>
                                    </li>
                                @else
                                    <li class="menu-item">
                                        <a class="flex items-center gap-x-3.5 rounded-md px-3 py-1.5 text-sm font-medium text-default-600 transition-all hover:bg-primary/5"
                                            href="{{ route('dashboard') }}">
                                            <i class="menu-dot"></i>
                                            Dashboard Default
                                        </a>
                                    </li>
                                @endif
                            @endauth
                        </ul>
                    </div>
                </li>

                {{-- Admin Specific Menu --}}
                @auth
                    @if (Auth::user()->isAdmin())
                        <li class="px-3 py-2 text-xs uppercase font-medium text-default-500 mt-4">Administrasi</li>

                        {{-- Manage Users Accordion Menu --}}
                        <li class="hs-accordion menu-item" id="manage-users-accordion">
                            <a class="hs-accordion-toggle group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-600 transition-all hover:bg-primary/5 hs-accordion-active:bg-primary/5 hs-accordion-active:text-primary"
                                href="javascript:;">
                                <i class="i-lucide-users size-5"></i>
                                <span class="menu-text"> Manage Users </span>
                                <i class="i-lucide-chevron-right hs-accordion-active:rotate-90 ms-auto size-4"></i>
                            </a>
                            <div
                                class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                                <ul class="mt-1 space-y-1">
                                    <li class="menu-item">
                                        <a class="flex items-center gap-x-3.5 rounded-md px-3 py-1.5 text-sm font-medium text-default-600 transition-all hover:bg-primary/5"
                                            href="{{ route('admin.users.index') }}">
                                            <i class="menu-dot"></i>
                                            <span class="menu-text"> Users </span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="flex items-center gap-x-3.5 rounded-md px-3 py-1.5 text-sm font-medium text-default-600 transition-all hover:bg-primary/5"
                                            href="{{ route('admin.leaders.index') }}">
                                            <i class="menu-dot"></i>
                                            <span class="menu-text"> Leader </span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="flex items-center gap-x-3.5 rounded-md px-3 py-1.5 text-sm font-medium text-default-600 transition-all hover:bg-primary/5"
                                            href="{{ route('admin.field-coordinators.index') }}">
                                            <i class="menu-dot"></i>
                                            <span class="menu-text"> Coordinator </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        {{-- Manage Ruas Jalan Link (for Admin) --}}
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-600 transition-all hover:bg-primary/5"
                                href="{{ route('masterdata.road-sections.index') }}">
                                <i class="i-lucide-map-pin size-5"></i>
                                <span class="menu-text"> Manage Ruas Jalan </span>
                            </a>
                        </li>

                        {{-- Lokasi Parkir (Admin) jika masih dibutuhkan menu terpisah --}}
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-600 transition-all hover:bg-primary/5"
                                href="{{ route('masterdata.parking-locations.index') }}">
                                <i class="i-lucide-parking-meter size-5"></i>
                                <span class="menu-text"> Lokasi Parkir (Admin) </span>
                            </a>
                        </li>
                        {{-- Tambahkan menu khusus admin lainnya di sini --}}
                    @endif

                    {{-- Leader Specific Menu --}}
                    @if (Auth::user()->isLeader())
                        <li class="px-3 py-2 text-xs uppercase font-medium text-default-500 mt-4">Manajemen Leader</li>
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-600 transition-all hover:bg-primary/5"
                                href="#"> {{-- Contoh: route laporan tim --}}
                                <i class="i-lucide-file-text size-5"></i>
                                <span class="menu-text"> Laporan Tim </span>
                            </a>
                        </li>
                        {{-- Tambahkan menu khusus leader lainnya --}}
                    @endif

                    {{-- Field Coordinator Specific Menu --}}
                    @if (Auth::user()->isFieldCoordinator())
                        <li class="px-3 py-2 text-xs uppercase font-medium text-default-500 mt-4">Operasional Lapangan</li>
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-600 transition-all hover:bg-primary/5"
                                href="{{ route('field_coordinator.staff.index') }}"> {{-- Contoh: route kelola staff --}}
                                <i class="i-lucide-user-check size-5"></i>
                                <span class="menu-text"> Kelola Staff </span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-600 transition-all hover:bg-primary/5"
                                href="{{ route('field_coordinator.parkir_data') }}"> {{-- Contoh: route data parkir area --}}
                                <i class="i-lucide-parking-meter size-5"></i>
                                <span class="menu-text"> Data Parkir Area </span>
                            </a>
                        </li>
                        {{-- Tambahkan menu khusus Field Coordinator lainnya --}}
                    @endif

                    {{-- Menu Transaksi & Laporan: Untuk Admin, Staff, dan Leader --}}
                    @if (Auth::user()->isStaff() || Auth::user()->isAdmin() || Auth::user()->isLeader())
                        <li class="px-3 py-2 text-xs uppercase font-medium text-default-500 mt-4">Transaksi & Laporan</li>
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-600 transition-all hover:bg-primary/5"
                                href="{{ route('masterdata.deposit-transactions.index') }}">
                                <i class="i-lucide-wallet size-5"></i>
                                <span class="menu-text"> Transaksi Setoran </span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-600 transition-all hover:bg-primary/5"
                                href="{{ route('masterdata.parking-locations.index') }}">
                                <i class="i-lucide-map-pin size-5"></i>
                                <span class="menu-text"> Lokasi Parkir Ruas Jalan </span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-600 transition-all hover:bg-primary/5"
                                href="{{ route('masterdata.agreements.index') }}"> {{-- Perjanjian Kerjasama --}}
                                <i class="i-lucide-handshake size-5"></i>
                                <span class="menu-text"> Perjanjian Kerjasama </span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-600 transition-all hover:bg-primary/5"
                                href="{{ route('masterdata.deposit-transactions.create') }}"> {{-- Contoh: route input transaksi baru --}}
                                <i class="i-lucide-square-plus size-5"></i>
                                <span class="menu-text"> Input Transaksi </span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-600 transition-all hover:bg-primary/5"
                                href="#"> {{-- Contoh: route laporan harian --}}
                                <i class="i-lucide-calendar-check size-5"></i>
                                <span class="menu-text"> Laporan Harian </span>
                            </a>
                        </li>
                        {{-- Tambahkan menu khusus Staff lainnya --}}
                    @endif
                @endauth {{-- End @auth --}}

                {{-- Bagian "Apps" Generik dari template Anda (dipertahankan jika masih relevan) --}}
                <li class="px-3 py-2 text-xs uppercase font-medium text-default-500 mt-4">Apps</li>

                <li class="menu-item">
                    <a class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-600 transition-all hover:bg-primary/5"
                        href="app-calendar.html"> {{-- Sesuaikan route jika ini bukan static HTML --}}
                        <i class="i-lucide-calendar size-5"></i>
                        Calendar
                    </a>
                </li>

            </ul>
        </div>
    </div>
</aside>
