<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8 border-4 bg-slate-100 mt-5">
        <form method="POST" action="{{ route('posts.store') }}">
            @csrf
            <input
                name="title"
                placeholder="{{ __('Title') }}"
                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mb-4"
            />
            <textarea
                name="body"
                placeholder="{{ __('Body') }}"
                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            ></textarea>
            <x-input-error :messages="$errors->get('body')" class="mt-2" />
            <x-primary-button class="mt-4">{{ __('Post') }}</x-primary-button>
        </form>

        <div class="mt-6 rounded-lg divide-y">
            @foreach ($posts as $post)
                <div
                    class="block rounded-lg bg-neutral-50 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-white my-10">
                    <div class="border-b-2 border-[#0000002d] px-6 py-3 text-black flex justify-between">
                        <div>
                            <span class="text-gray-800">{{ $post->author->name }}</span>
                            @unless ($post->published_on->eq($post->last_modified))
                                <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                            @endunless
                        </div>
                        <div class="flex">
                            <small class="mr-5 text-sm text-gray-600">{{ $post->published_on->format('j M Y, g:i a') }}</small>
                            @if ($post->author->is(auth()->user()))
                                <x-dropdown>
                                    <x-slot name="trigger">
                                        <button>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('posts.edit', $post)">
                                            {{ __('Edit') }}
                                        </x-dropdown-link>
                                        @if($post->active)
                                            <x-dropdown-link :href="route('posts.archive', $post)">
                                                {{ __('Archive') }}
                                            </x-dropdown-link>
                                        @endif
                                        @if(!$post->active)
                                            <x-dropdown-link :href="route('posts.publish', $post)">
                                                {{ __('Publish') }}
                                            </x-dropdown-link>
                                        @endif
                                        <form method="POST" action="{{ route('posts.destroy', $post) }}">
                                            @csrf
                                            @method('delete')
                                            <x-dropdown-link :href="route('posts.destroy', $post)" onclick="event.preventDefault(); this.closest('form').submit();">
                                                {{ __('Delete') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            @endif
                        </div>
                    </div>
                    <div class="p-6 relative">
                        <h5 class="mb-2 text-xl font-medium leading-tight text-black">
                            {{$post->title}}
                        </h5>
                        <p class="text-base text-black">
                            {{$post->body}}
                        </p>
                        @if(!$post->active)
                            <small class="text-sm text-gray-500 absolute bottom-1 right-2">Archived</small>
                        @endif
                    </div>
                    @foreach($post->comments as $comment)
                        <div class="bg-white border-4 p-2 flex flex-col">
                            <span class="text-sm text-gray-500">
                                {{$comment->from_user->name}}
                            </span>
                            <span>
                                {{$comment->body}}
                            </span>
                        </div>
                    @endforeach
                    <div class="bg-white border-4 p-2 flex flex-col">
                        <form method="POST" action="{{ route('posts.comment', $post) }}" class="flex flex-col items-end">
                            @csrf
                            <input
                                name="comment"
                                placeholder="{{ __('Comment') }}"
                                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                            />
                            <x-primary-button class="mt-2">{{ __('Comment') }}</x-primary-button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
