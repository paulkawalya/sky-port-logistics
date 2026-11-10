<x-filament-widgets::widget>
    @php
        $hour = (int) now()->format('H');
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
        $user = auth()->user();
        $userName = $user ? $user->name : 'Operator';
        $userType = $user ? ($user->user_type ?? 'Staff') : 'Staff';
    @endphp

    <div class="relative overflow-hidden rounded-xl border border-gray-200/80 bg-white p-5 shadow-sm transition-all dark:border-gray-800 dark:bg-gray-900 md:p-6">
        <!-- Brand accent bar on top -->
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-red-600 via-rose-500 to-red-600"></div>

        <div class="flex flex-col justify-between gap-5 lg:flex-row lg:items-center">
            <!-- Left Info Block -->
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-950/40 dark:text-red-400 dark:ring-red-500/30">
                        <span class="h-1.5 w-1.5 rounded-full bg-red-600 dark:bg-red-400 animate-pulse"></span>
                        {{ $userType }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                        <x-heroicon-m-calendar class="h-3.5 w-3.5 text-gray-400 dark:text-gray-500" />
                        {{ now()->format('l, F j, Y') }}
                    </span>
                    <span class="hidden text-xs text-emerald-600 dark:text-emerald-400 sm:inline-flex items-center gap-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Freight Operations Online
                    </span>
                </div>

                <div>
                    <h1 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-2xl">
                        {{ $greeting }}, {{ $userName }}
                    </h1>
                    <p class="mt-1 text-xs text-gray-600 dark:text-gray-300 sm:text-sm">
                        Welcome to <strong class="text-red-600 dark:text-red-400 font-semibold">SIFCARGO TONIGHT</strong>. Forwarding & clearing, container transit tracking, bonded store inventory, and financial control.
                    </p>
                </div>
            </div>

            <!-- Right Action Buttons -->
            <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2.5 w-full lg:w-auto">
                <a href="{{ \App\Filament\Resources\ShippmentResource::getUrl('create') }}" 
                   class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-red-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 transition-colors">
                    <x-heroicon-m-plus-circle class="h-4 w-4" />
                    <span>New Consignment</span>
                </a>

                <a href="{{ \App\Filament\Resources\ManualInvoiceResource::getUrl('create') }}" 
                   class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-750 transition-colors">
                    <x-heroicon-m-document-currency-dollar class="h-4 w-4 text-emerald-600 dark:text-emerald-400" />
                    <span>Manual Invoice</span>
                </a>

                <a href="{{ \App\Filament\Resources\RequisitionResource::getUrl('create') }}" 
                   class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-750 transition-colors">
                    <x-heroicon-m-clipboard-document-check class="h-4 w-4 text-amber-600 dark:text-amber-400" />
                    <span>Budget Request</span>
                </a>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
