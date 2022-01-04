<div wire:change.debounce class="min-h-full">
    <main class="mt-16 w-full">
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-between items-center">
                <h1 class="mt-3">
                    Settings
                </h1>
                <div class="space-x-2">
                    <a
                        class="w-full py-2 px-4 mt-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="/settings">
                        <span>
                            Settings
                        </span>
                    </a>
                    <a
                        class="w-full py-2 px-4 mt-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="/">
                        <span>
                        Home
                        </span>
                    </a>
                </div>

            </div>
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 mt-6">
                <form wire:submit.prevent="submit">

                    <label for="name"
                           class="ml-px pl-4 block sm:text-sm text-lg font-medium text-gray-700">
                        New User
                        <input
                            wire:model="name"
                            type="text"
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 px-4 rounded-full"
                        @error('name')<span class="error">{{ $message }}</span> @enderror
                        >
                    </label>
                    <button type="submit"
                            class="w-full py-2 px-4 mt-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save User
                    </button>
                </form>

            </div>

            @if ($users->count() > 0)
                <div class="space-y-2 mt-6">
                    <div class="flex flex-col">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Name
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Edit</span>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($users->sortBy('name') as $user)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $user->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a wire:click="delete({{ $user->id }})"
                                                       class="text-indigo-600 cursor-pointer hover:text-indigo-900">Delete</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>
</div>
