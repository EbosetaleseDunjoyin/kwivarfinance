<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Public Apis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden p-6 shadow-sm sm:rounded-lg">
                <h4 class="font-semibold text-lg text-gray-900 my-5 dark:text-gray-100">
                    Import Public Api Data
                </h4>
                <div class="text-gray-900 dark:text-gray-100">
                    <form action="{{ route('public-api.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex flex-col lg:flex-row items-center">

                            <!-- File Upload -->
                            <div>
                                <x-text-input id="url" class="block lg:w-[300px] w-full" type="text" name="url" value="https://api.publicapis.org/entries" required autofocus readonly />
                                <x-input-error :messages="$errors->get('url')" class="mt-2" />
                            </div>

                            <div class="">
                                <x-primary-button class="ms-3">
                                    {{ __('Import') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="flex flex-column md:flex-row justify-between md:items-center my-5">
                    <h4 class="font-semibold text-lg text-gray-900  capitalize dark:text-gray-100">
                        Public Api data ({{ $apidataCount }})
                    </h4>
                    @if ($apidata->count() > 0)
                        <form method="POST" action="{{ route('public-api.truncate') }}">
                            @csrf
                            @method('delete')
                            <button class="bg-red-600 text-white px-3 py-2 border-transparent rounded" type="submit">
                                {{ __('Delete All') }}
                            </button>
                        </form>
                    @endif
                </div>
               
                @if ($apidata->count() <= 0)
                    <h4 class="font-semibold text-base text-gray-900 my-5 dark:text-gray-100">
                        No data found...
                    </h4>
                @else
                    
                        <div class="overflow-x-auto mt-10">
                           
                            <table class="table-auto min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 dark:bg-gray-400">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">S/N</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">API</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">Auth</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">HTTPS</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">Cors</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">Link</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">Category</th>
                                        
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 ">
                                    @foreach ($apidata as $key => $record)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $key + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $record->api }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $record->description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $record->auth }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                            @if($record->https)
                                                True
                                            @else
                                                False
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $record->cors }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $record->link }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $record->category }}</td>
                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Display pagination links -->
                        <div class="mt-4 text-gray-900 my-5 dark:text-gray-100">
                            {{ $apidata->links() }}
                        </div>
                    
                @endif
            </div>
        </div>
    </div>
</x-app-layout>