@props(['type' => 'danger', 'title', 'message', 'confirmText' => 'Konfirmasi', 'cancelText' => 'Batal'])

@php
$colors = [
    'danger' => [
        'bg' => 'bg-red-600 hover:bg-red-700',
        'icon' => 'text-red-600',
        'iconBg' => 'bg-red-100'
    ],
    'warning' => [
        'bg' => 'bg-yellow-600 hover:bg-yellow-700',
        'icon' => 'text-yellow-600',
        'iconBg' => 'bg-yellow-100'
    ],
    'success' => [
        'bg' => 'bg-green-600 hover:bg-green-700',
        'icon' => 'text-green-600',
        'iconBg' => 'bg-green-100'
    ],
    'info' => [
        'bg' => 'bg-blue-600 hover:bg-blue-700',
        'icon' => 'text-blue-600',
        'iconBg' => 'bg-blue-100'
    ]
];

$color = $colors[$type] ?? $colors['danger'];
@endphp

<div {{ $attributes->merge(['class' => 'fixed inset-0 z-50 overflow-y-auto hidden']) }} 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="this.parentElement.classList.add('hidden')"></div>

    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md md:max-w-lg">
            <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full {{ $color['iconBg'] }} sm:mx-0 sm:h-10 sm:w-10">
                        @if($type === 'danger')
                            <svg class="h-6 w-6 {{ $color['icon'] }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        @elseif($type === 'warning')
                            <svg class="h-6 w-6 {{ $color['icon'] }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                        @elseif($type === 'success')
                            <svg class="h-6 w-6 {{ $color['icon'] }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @else
                            <svg class="h-6 w-6 {{ $color['icon'] }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                        @endif
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left flex-1">
                        <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                            {{ $title }}
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 break-words whitespace-normal">
                                {{ $message }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                <button type="button" 
                        onclick="document.getElementById(this.closest('[id]').id + '-form').submit()"
                        class="inline-flex w-full justify-center rounded-md px-4 py-2 text-sm font-semibold text-white shadow-sm sm:w-auto {{ $color['bg'] }} focus:outline-none focus:ring-2 focus:ring-offset-2">
                    {{ $confirmText }}
                </button>
                <button type="button" 
                        onclick="this.closest('[role=dialog]').classList.add('hidden')"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                    {{ $cancelText }}
                </button>
            </div>
        </div>
    </div>
</div>
