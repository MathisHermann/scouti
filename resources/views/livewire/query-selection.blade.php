<div wire:poll="load_process_status">
    <div class="min-h-full">
        <main class="mt-16 w-full">
            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="w-full space-x-2 flex justify-end">
                    <a
                        class="py-2 px-4 mt-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="/settings">
                        <span>
                            Settings
                        </span>
                    </a>
                    <a
                        class="py-2 px-4 mt-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="/results">
                        <span>
                        Results
                        </span>
                    </a>
                </div>

                <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 mt-3">
                    <label for="name"
                           class="ml-px pl-4 block sm:text-sm text-lg font-medium text-gray-700">
                        Keywords
                    </label>
                    <div x-data="{
                        show_add_button: true,
                        show_delete_button: false,
                        fields: @entangle('terms').defer,
                        currentOption: {value: ''},
                        get showDeleteField() {
                            return this.fields.length > 1
                        },
                        get showAddField () {
                            return this.fields.length < 6
                        },
                        get add () {
                            if (this.fields.length < 6) {
                                this.fields.push({
                                    value: '',
                                })
                            }
                        },
                        remove (option) {
                            console.log(option)
                           if (this.fields.length > 1) {
                                const index = this.fields.findIndex(element => element.value === option.value);
                                if (index !== -1) {
                                    this.fields.splice(index, 1);
                                }
                           }
                        }
                    }"
                         class="space-y-4"
                    >
                        <div class="space-y-2">
                            <template x-for="option in fields">
                                <div class="flex justify-between">
                                    <input
                                        @if (!$process_finished) disabled @endif
                                    type="text"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 px-4 rounded-full"
                                        x-model="option.value"
                                        @keyup.enter="$wire.find_results()"
                                    >

                                    <button
                                        @if (!$process_finished) disabled @endif
                                    class="block sm:text-sm border-gray-300"
                                        x-show="showDeleteField"
                                        @click.stop="remove(option)"
                                    >
                                        <x-ei-close class="text-gray-500 h-6 w-6"/>
                                    </button>
                                </div>
                            </template>
                            <button
                                @if (!$process_finished) disabled @endif
                            class="flex flex-row items-center block sm:text-sm text-base text-gray-500 border-gray-300 ml-auto mr-0"
                                x-show="showAddField"
                                @click.stop="add"
                            >
                                <x-ei-plus class="h-8 w-8"/>
                                <span class="align-middle">
                                Add new
                            </span>
                            </button>
                        </div>

                        <div>
                            <label for="location" class="ml-px pl-4 block sm:text-sm text-lg font-medium text-gray-700">
                                Industry
                            </label>
                            <select
                                wire:model="industry"
                                @if (!$process_finished) disabled @endif
                                id="location"
                                name="location"
                                class="text-gray-600 mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-full">
                                <option selected disabled>
                                    {{ 'Select' }}
                                </option>
                                @foreach($industries as $industry_option)
                                    <option
                                        value="{{ $industry_option['value'] }}">{{ $industry_option['value'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="user" class="ml-px pl-4 block sm:text-sm text-lg font-medium text-gray-700">
                                User
                            </label>
                            <select
                                wire:model="user"
                                @if (!$process_finished) disabled @endif
                                id="location"
                                name="user"
                                class="text-gray-600 mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-full">
                                <option selected>
                                    {{ '-' }}
                                </option>
                                @foreach($users->sortByDesc('name') as $one_user)
                                    <option value="{{ $one_user->name }}">{{ $one_user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button @if (!$process_finished) disabled @endif wire:click="find_results()"
                                class="w-full py-2 px-4 mt-3 border border-transparent rounded-md shadow-sm text-sm font-medium
                        text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2
                        focus:ring-indigo-500"
                        >
                            Find results
                        </button>
                    </div>
                </div>
            </div>

        </main>
    </div>

    @if($enable_loading)
        <div wire:loading.delay.longer>

            <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                 aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                         aria-hidden="true">
                    </div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                          aria-hidden="true">
                        &#8203;
                    </span>

                    <div
                        class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                        <div>
                            <div class="mt-5 sm:mt-6">
                                <div
                                    class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-black">
                                    <div>
                                        <div style="border-top-color:transparent"
                                             class="w-16 h-16 border-4 border-gray-700 border-dotted rounded-full animate-spin-slow"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 text-center sm:mt-5">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Loading
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(Session::has('error_dropdown_selection'))
    <!-- This example requires Tailwind CSS v2.0+ -->
        <!-- Global notification live region, render this permanently at the end of the document -->
        <div
            x-data="{error_notification : true }">
            <div
                x-show="error_notification"
                aria-live="assertive"
                class="fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start">
                <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
                    <!--
                      Notification panel, dynamically insert this into the live region when it needs to be displayed

                      Entering: "transform ease-out duration-300 transition"
                        From: "translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                        To: "translate-y-0 opacity-100 sm:translate-x-0"
                      Leaving: "transition ease-in duration-100"
                        From: "opacity-100"
                        To: "opacity-0"
                    -->
                    <div
                        class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
                        <div class="p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="stroke-2 text-red-600 h-6 w-6">
                                        <!-- Heroicon name: outline/check-circle -->
                                        <x-ei-close-o class="stroke-2"/>
                                    </div>
                                </div>
                                <div class="ml-3 w-0 flex-1 pt-0.5">
                                    <p class="text-sm font-medium text-gray-900">
                                        Industry needed
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        To make a request, please select an industry.
                                    </p>
                                </div>
                                <div class="ml-4 flex-shrink-0 flex">
                                    <button
                                        x-on:click="error_notification = false"
                                        class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <span class="sr-only">Close</span>
                                        <!-- Heroicon name: solid/x -->
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                             fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
