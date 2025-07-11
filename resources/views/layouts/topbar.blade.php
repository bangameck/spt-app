<header class="app-header sticky top-0 z-50 p-4 pb-0 bg-white/5 backdrop-blur-sm">
    <div class="min-h-topbar flex items-center bg-white rounded-md shadow">
        <div class="px-6 w-full flex items-center justify-between gap-4">
            <div class="flex items-center gap-5">
                <button
                    class="flex items-center text-default-500 rounded-full cursor-pointer p-2 bg-white border border-default-200 hover:bg-primary/15 hover:text-primary hover:border-primary/5 transition-all"
                    data-hs-overlay="#app-menu" aria-label="Toggle navigation">
                    <i class="i-lucide-align-left text-2xl"></i>
                </button>

                <a href="{{ url('/') }}" class="md:hidden flex">
                    <img src="{{ asset('assets/images/logo-sm.png') }}" class="h-5" alt="Small logo">
                </a>

                <div class="md:flex hidden items-center relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <i class="i-tabler-search text-base"></i>
                    </div>
                    <input type="search"
                        class="form-input px-10 rounded-lg  bg-default-500/10 border-transparent focus:border-transparent w-80"
                        placeholder="Search...">
                    <button type="button" class="absolute inset-y-0 end-0 flex items-center pe-3">
                        <i class="i-tabler-microphone text-base hover:text-black"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-5">
                <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
                    <button type="button"
                        class="hs-dropdown-toggle inline-flex items-center p-2 rounded-full bg-white border border-default-200 hover:bg-primary/15 hover:text-primary transition-all">
                        <i class="i-lucide-globe text-2xl"></i>
                    </button>

                    <div
                        class="hs-dropdown-menu duration mt-2 min-w-48 rounded-lg border border-default-200 bg-white p-2 opacity-0 shadow-md transition-[opacity,margin] hs-dropdown-open:opacity-100 hidden">
                        <a href="javascript:void(0);"
                            class="flex items-center gap-2.5 py-2 px-3 rounded-md text-sm text-default-800 hover:bg-default-100">
                            <img src="{{ asset('assets/images/flags/spain.jpg') }}" alt="user-image" class="h-4">
                            <span class="align-middle">Spanish</span>
                        </a>
                    </div>
                </div>

                <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
                    <button type="button"
                        class="hs-dropdown-toggle inline-flex items-center p-2 rounded-full bg-white border border-default-200 hover:bg-primary/15 hover:text-primary transition-all">
                        <i class="i-lucide-bell text-2xl"></i>
                    </button>

                    <div
                        class="hs-dropdown-menu duration mt-2 w-full max-w-sm rounded-lg border border-default-200 bg-white opacity-0 shadow-md transition-[opacity,margin] hs-dropdown-open:opacity-100 hidden">
                        <div
                            class="block px-4 py-2 font-medium text-center text-default-700 rounded-t-lg bg-default-50">
                            Notifications
                        </div>

                        <div class="divide-y divide-default-100">

                            <a href="#" class="flex px-4 py-3 hover:bg-default-100">
                                <div class="flex-shrink-0">
                                    <img class="rounded-full w-11 h-11" src="{{ asset('assets/images/users/avatar-9.jpg') }}"
                                        alt="Emma image">
                                    <div
                                        class="absolute flex items-center justify-center w-5 h-5 ms-6 -mt-5 bg-pink-500 border border-white rounded-full">
                                        <i class="i-tabler-heart text-white w-4 h-4"></i>
                                    </div>
                                </div>
                                <div class="w-full ps-3">
                                    <div class="text-default-500 text-sm mb-1.5">
                                        <span class="font-semibold text-default-900">Emma Stone</span> reacted to
                                        your post.
                                    </div>
                                    <div class="text-xs text-primary">30 minutes ago</div>
                                </div>
                            </a>
                        </div>


                        <a href="#"
                            class="block py-2 text-sm font-medium text-center text-default-900 rounded-b-lg bg-default-50 hover:bg-default-100">
                            <div class="inline-flex items-center ">
                                <i class="i-tabler-eye size-4 text-default-500"></i>
                                View all
                            </div>
                        </a>
                    </div>
                </div>

                <div class="md:flex hidden">
                    <button data-toggle="fullscreen" type="button"
                        class="p-2 rounded-full bg-white border border-default-200 hover:bg-primary/15 hover:text-primary transition-all">
                        <span class="sr-only">Fullscreen Mode</span>
                        <span class="flex items-center justify-center size-6">
                            <i class="i-lucide-maximize text-2xl flex group-[-fullscreen]:hidden"></i>
                            <i class="i-lucide-minimize text-2xl hidden group-[-fullscreen]:flex"></i>
                        </span>
                    </button>
                </div>

                <div class="relative">
                    <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">

                        <button type="button" class="hs-dropdown-toggle">
                            @auth {{-- Memastikan pengguna sudah login --}}
                            @php
                            $userImage = Auth::user()->img ? asset(Auth::user()->img) : asset('assets/images/users/default-avatar.png'); // Ganti 'default-avatar.png' dengan gambar default Anda
                            @endphp
                            <img src="{{ $userImage }}" alt="{{ Auth::user()->name }}" class="rounded-full h-10 w-10 object-cover">
                            @else {{-- Jika pengguna belum login, tampilkan gambar default --}}
                            <img src="{{ asset('assets/images/users/guest-avatar.png') }}" alt="Guest" class="rounded-full h-10 w-10 object-cover">
                            @endauth
                        </button>
                        <div
                            class="hs-dropdown-menu duration mt-2 min-w-48 rounded-lg border border-default-200 bg-white p-2 opacity-0 shadow-md transition-[opacity,margin] hs-dropdown-open:opacity-100 hidden">
                            <a class="flex items-center py-2 px-3 rounded-md text-sm text-default-800 hover:bg-default-100"
                                href="#">
                                Profile
                            </a>
                            <a class="flex items-center py-2 px-3 rounded-md text-sm text-default-800 hover:bg-default-100"
                                href="#">
                                Feed
                            </a>
                            <a class="flex items-center py-2 px-3 rounded-md text-sm text-default-800 hover:bg-default-100"
                                href="#">
                                Analytics
                            </a>
                            <a class="flex items-center py-2 px-3 rounded-md text-sm text-default-800 hover:bg-default-100"
                                href="#">
                                Settings
                            </a>
                            <a class="flex items-center py-2 px-3 rounded-md text-sm text-default-800 hover:bg-default-100"
                                href="#">
                                Support
                            </a>

                            <hr class="my-2 -mx-2">

                            <a class="flex items-center py-2 px-3 rounded-md text-sm text-default-800 hover:bg-default-100"
                                href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Log Out
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>