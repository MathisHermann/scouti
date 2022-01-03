<div class="min-h-full">
    <main class="p-16 w-full">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 mb-6 items-center flex flex-row justify-between">
            <label for="name"
                   class="ml-px pl-4 block text-xl font-medium text-gray-500">Last <span
                    class="font-bold text-gray-600">{{ $number_of_results }}</span> Results for request <span
                    class="font-bold text-gray-600">{{ $current_request_number }}</span>
            </label>
            <div class="items-center">
                <span class="font-medium">
                    Last Search Parameters:
                </span>
                <ul>
                    @foreach($last_search_parameters as $parameter)
                        @if (array_key_exists('value', $parameter))
                            <li>{{ $parameter['value'] }}</li>
                        @elseif(array_key_exists('industry', $parameter))
                            <li>
                                <span class="font-medium text-gray-600">
                                    Industry:
                                </span>
                                {{ $parameter['industry'] }}
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>

        <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

            @foreach($results->sortByDesc('score') as $result)

                <li x-data="{ modalOpen : false }"
                    class="col-span-1 bg-white rounded-lg shadow divide-y divide-gray-200 w-full">
                    <div class="w-full flex items-center justify-between p-6 space-x-6">
                        <div class="flex-1 truncate">
                            <div class="flex justify-between space-x-3">
                                <h3 class="text-gray-900 text-sm font-medium truncate">{{  $result['keyword']  }}</h3>
                                <p class="text-gray-800 text-sm font-normal">Score: {{  $result['score']  }}</p>
                            </div>
                            <div class="text-sm">
                                <p class="mt-1 text-gray-500  truncate">Description:</p>
                                <p @click="modalOpen = true" class="break-normal whitespace-normal cursor-pointer">
                                    {{  substr($result['text'], 0, 200) . '...'  }} <span class="text-gray-500 italic"> more</span>
                                </p>
                            </div>

                        </div>
                    </div>
                    <div>
                        <div class="-mt-px flex divide-x divide-gray-200">
                            <div class="-ml-px w-0 flex-1 flex">
                                <a href="https://google.com/search?q={{ $result['keyword'] }}" target="_blank"
                                   class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-gray-700 font-medium border border-transparent rounded-br-lg hover:text-gray-500">

                                    <div class="w-5 h-5 text-gray-400">
                                        <x-heroicon-o-globe/>
                                    </div>

                                    <span class="ml-3">Find Product</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div x-cloak x-show="modalOpen">

                        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                             aria-modal="true">
                            <div
                                class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                     aria-hidden="true">
                                </div>

                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                      aria-hidden="true">
                                    &#8203;
                                </span>

                                <div
                                    class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full sm:p-6">

                                    <div>
                                        <span class="font-medium">
                                            Description:
                                        </span>
                                    </div>
                                    <button
                                        class="absolute top-0 right-0 p-4"
                                        @click="modalOpen = false">
                                        <span class="flex flex-row items-center text-gray-500">
                                            Close
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </span>
                                    </button>

                                    <div class="mt-4">
                                        <div class="mt-3 text-center sm:mt-5">
                                            <h3 class="text-sm leading-6 font-normal text-gray-900" id="modal-title">
                                                {{ $result['text'] }}
                                            </h3>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </li>
            @endforeach
        </ul>
    </main>
</div>
