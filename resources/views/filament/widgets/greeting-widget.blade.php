<x-filament-widgets::widget>
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-neutral-950 via-zinc-900 to-stone-900 p-6 text-white shadow-xl border border-red-950/40">
        <!-- Background subtle decorative pattern -->
        <div class="absolute -right-8 -top-8 h-48 w-48 rounded-full bg-red-600/10 blur-2xl"></div>
        <div class="absolute right-32 -bottom-8 h-32 w-32 rounded-full bg-rose-600/10 blur-xl"></div>

        <div class="relative z-10 flex flex-col justify-between gap-6 md:flex-row md:items-center">
            <!-- Left user & system summary -->
            <div class="space-y-2">
                <div class="flex items-center gap-2.5">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-500/20 px-3 py-1 text-xs font-semibold text-red-300 border border-red-500/30">
                        <span class="h-2 w-2 rounded-full bg-red-400 animate-pulse"></span>
                        {{ auth()->user()->user_type ?? 'Staff' }}
                    </span>
                    <span class="text-xs text-zinc-400 flex items-center gap-1">
                        <x-heroicon-m-calendar class="w-3.5 h-3.5 text-zinc-400" />
                        {{ now()->format('l, F j, Y') }}
                    </span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-white md:text-3xl">
                    Welcome back, {{ auth()->user()->name }}
                </h1>
                <p class="text-sm text-zinc-300 max-w-xl">
                    SIFCARGO TONIGHT Operations Center. Forwarding & Clearing freight operations, container tracking, bonded store management, and financial control.
                </p>
            </div>

            <!-- Right quick actions -->
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ \App\Filament\Resources\ShippmentResource::getUrl('create') }}" 
                   class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-red-500 transition-all focus:ring-2 focus:ring-red-400 focus:outline-none">
                    <x-heroicon-m-plus-circle class="w-4 h-4" />
                    <span>New Cargo Consignment</span>
                </a>

                <a href="{{ \App\Filament\Resources\ManualInvoiceResource::getUrl('create') }}" 
                   class="inline-flex items-center gap-2 rounded-xl bg-zinc-800/90 border border-zinc-700 px-4 py-2.5 text-xs font-semibold text-zinc-200 hover:bg-zinc-700 transition-all focus:ring-2 focus:ring-zinc-500 focus:outline-none">
                    <x-heroicon-m-document-currency-dollar class="w-4 h-4 text-emerald-400" />
                    <span>Manual Invoice</span>
                </a>

                <a href="{{ \App\Filament\Resources\RequisitionResource::getUrl('create') }}" 
                   class="inline-flex items-center gap-2 rounded-xl bg-zinc-800/90 border border-zinc-700 px-4 py-2.5 text-xs font-semibold text-zinc-200 hover:bg-zinc-700 transition-all focus:ring-2 focus:ring-zinc-500 focus:outline-none">
                    <x-heroicon-m-clipboard-document-check class="w-4 h-4 text-amber-400" />
                    <span>Budget Requisition</span>
                </a>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
