<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('CSV Uploads') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden p-6 shadow-sm sm:rounded-lg">
                <h4 class="font-semibold text-lg text-gray-900 my-5 dark:text-gray-100">
                    Import CSV
                </h4>
                <div class="text-gray-900 dark:text-gray-100">
                    <form action="{{ route('csv-records.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex flex-col lg:flex-row items-center">

                            <!-- File Upload -->
                            <div>
                                <x-text-input id="csvUpload" class="block  w-full" type="file" name="file" :value="old('file')" required autofocus accept=".csv"/>
                                <x-input-error :messages="$errors->get('file')" class="mt-2" />
                            </div>

                            <div class="">
                                <x-primary-button class="ms-3">
                                    {{ __('Upload') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
               
                @if ($csvRecords->count() <= 0)
                    <h4 class="font-semibold text-base text-gray-900 my-5 dark:text-gray-100">
                        No data found...
                    </h4>
                @else
                    @foreach ($csvRecords as $fileName => $records)
                        <div class="overflow-x-auto mt-10">
                            <div class="flex flex-column md:flex-row justify-between md:items-center my-5">
                                <h4 class="font-semibold text-base text-gray-900  capitalize dark:text-gray-100">
                                    <span class="font-bold text-gray-500 my-3  dark:text-gray-500">File Name:</span> {{ $fileName }}
                                </h4>
                                 <form method="POST" action="{{ route('csv-records.delete',$fileName) }}">
                                    @csrf
                                    @method('delete')
                                    <button  class="bg-red-600 text-white px-3 py-2 border-transparent rounded" type="submit">
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            </div>
                            <table class="table-auto min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 dark:bg-gray-400">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">S/N</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">File Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">Phone</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-100 uppercase tracking-wider">Address</th>
                                        
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 ">
                                    @foreach ($records as $key => $record)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $key + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $record->file_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $record->csv_id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $record->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $record->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $record->phone }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $record->address }}</td>
                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Display pagination links -->
                        <div class="mt-4 text-gray-900 my-5 dark:text-gray-100">
                            {{  $records->links() }}
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</x-app-layout>