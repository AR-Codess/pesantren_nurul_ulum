<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-4">{{ __("You're logged in!") }}</p>
                    
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Informasi Akun</h3>
                        <div class="flex items-center mb-2">
                            <span class="font-medium mr-2">Nama:</span> 
                            <span>{{ auth()->user()->name }}</span>
                        </div>
                        <div class="flex items-center mb-2">
                            <span class="font-medium mr-2">Email:</span> 
                            <span>{{ auth()->user()->email }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-medium mr-2">Role:</span> 
                            @if(auth()->user()->hasRole('admin'))
                                <span class="px-3 py-1 text-xs text-white bg-red-500 rounded-full">Admin</span>
                            @elseif(auth()->user()->hasRole('guru'))
                                <span class="px-3 py-1 text-xs text-white bg-blue-500 rounded-full">Guru</span>
                            @elseif(auth()->user()->hasRole('santri'))
                                <span class="px-3 py-1 text-xs text-white bg-green-500 rounded-full">Santri</span>
                            @else
                                <span class="px-3 py-1 text-xs text-white bg-gray-500 rounded-full">User</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
