<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Select System') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>


    <div class="flex justify-center gap-4 mt-6">
    <a href="{{ route('door.control') }}" target="_blank"
       class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
        Door Control
    </a>

    <a href="{{ route('sunblock.net') }}" target="_blank"
       class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
        Sunblock Net
    </a>
</div>

</x-app-layout>
