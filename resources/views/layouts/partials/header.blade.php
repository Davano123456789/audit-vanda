<header class="no-print sticky top-0 z-20 flex h-16 items-center justify-between gap-4 border-b border-gray-200 bg-white/90 px-4 backdrop-blur lg:px-8">
    <div class="flex min-w-0 items-center gap-3">
        <button type="button" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 lg:hidden" data-sidebar-open aria-label="Buka menu">
            <i data-lucide="menu" class="h-5 w-5"></i>
        </button>
        <h1 class="truncate text-base font-semibold">@yield('title', 'Dashboard')</h1>
    </div>

    <a href="{{ route('profil.edit') }}" class="flex items-center gap-3 rounded-lg p-1 transition hover:bg-gray-50" title="Profil Saya">
        <div class="hidden text-right sm:block">
            <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-500">{{ auth()->user()->role_label }}</p>
        </div>
        @if (auth()->user()->photo_url)
            <img src="{{ auth()->user()->photo_url }}" alt="" class="h-9 w-9 rounded-full object-cover">
        @else
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-100 text-sm font-semibold text-brand-700">
                {{ auth()->user()->initials }}
            </div>
        @endif
    </a>
</header>
