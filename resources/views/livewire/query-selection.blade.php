<div class="min-h-full">
    <main class="mt-16 w-full">
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <label for="name"
                       class="ml-px pl-4 block sm:text-sm text-lg font-medium text-gray-700">Keywords</label>
                <div x-data="{
                        show_add_button: true,
                        show_delete_button: false,
                        fields: @entangle('terms'),
                        currentOption: {value: ''},
                        get showDeleteField() {
                            return this.fields.length > 2
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
                           if (this.fields.length > 2) {
                                const index = this.fields.findIndex(element => element.value === option.value);
                                if (index !== -1) {
                                    this.fields.splice(index, 1);
                                }
                           }
                        }
                    }"
                     class="space-y-2"
                >
                    <template x-for="option in fields">
                        <div class="flex justify-between">
                            <input
                                type="text"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 px-4 rounded-full"
                                x-model="option.value"
                                @keyup.enter="$wire.find_results()"
                            >

                            <button
                                class="block sm:text-sm border-gray-300"
                                x-show="showDeleteField"
                                @click.stop="remove(option)"
                            >
                                <x-ei-close class="text-gray-500 h-6 w-6"/>
                            </button>
                        </div>
                    </template>
                    <button
                        class="flex flex-row items-center block sm:text-sm text-base text-gray-500 border-gray-300 ml-auto mr-0"
                        x-show="showAddField"
                        @click.stop="add"
                    >
                        <x-ei-plus class="h-8 w-8"/>
                        <span class="align-middle">
                                Add new
                            </span>
                    </button>
                    <button wire:click="find_results()"
                            class="w-full py-2 px-4 mt-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Find results
                    </button>
                </div>
            </div>
        </div>

        <div wire:loading.delay.longer>

            <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                 aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                         aria-hidden="true"></div>


                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                          aria-hidden="true">&#8203;</span>

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
                                    Loading {{ $modal_msg }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
