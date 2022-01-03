<div wire:change.debounce class="min-h-full">
    <main class="mt-16 w-full">
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-between items-center">
                <h1 class="mt-3">
                    Settings
                </h1>
                <div>
                    <a
                        class="w-full py-2 px-4 mt-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="/">
                        <span>
                        Back
                        </span>
                    </a>
                </div>

            </div>
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 mt-6">
                <label for="name"
                       class="ml-px pl-4 block sm:text-sm text-lg font-medium text-gray-700">
                    Industries
                </label>
                <div x-data="{
                        show_add_button: true,
                        show_delete_button: false,
                        fields: @entangle('industry_fields'),
                        currentOption: {value: ''},
                        get showDeleteField() {
                            return this.fields.length > 2
                        },
                        get showAddField() {
                            return this.fields.length < 10
                        },
                        get add () {
                            if (this.fields.length < 10) {
                                this.fields.push({
                                    value: '',
                                })
                            }
                        },
                        remove (option) {
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
                        @click.stop="add"
                        x-show="showAddField"
                    >
                        <x-ei-plus class="h-8 w-8"/>
                        <span class="align-middle">
                                Add new
                            </span>
                    </button>
                </div>
            </div>

            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 mt-6">
                <button wire:click="save()"
                        class="w-full py-2 px-4 mt-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save
                </button>
            </div>
        </div>
    </main>
</div>
