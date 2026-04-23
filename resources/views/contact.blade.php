@extends('layouts.app')

@section('title', 'Kontak')

@section('content')
    <div class="py-20 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl">
                    Hubungi Kami
                </h1>
                <p class="mt-4 text-xl text-gray-600 dark:text-gray-400">
                    Ada pertanyaan? Tim kami siap membantu Anda
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Contact Info -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Informasi Kontak</h2>

                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-lg font-medium text-gray-900 dark:text-white">Alamat</p>
                                <p class="mt-1 text-gray-600 dark:text-gray-400">
                                    Jl. Teknologi No. 123<br>
                                    Jakarta Selatan, 12345<br>
                                    Indonesia
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-lg font-medium text-gray-900 dark:text-white">Telepon</p>
                                <p class="mt-1 text-gray-600 dark:text-gray-400">+62 21 5555 1234</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 7.89a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-lg font-medium text-gray-900 dark:text-white">Email</p>
                                <p class="mt-1 text-gray-600 dark:text-gray-400">support@stockumkm.id</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Kirim Pesan</h2>

                    <form class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama
                                Lengkap</label>
                            <input type="text" name="name" id="name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>

                        <div>
                            <label for="email"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" id="email"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>

                        <div>
                            <label for="message"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pesan</label>
                            <textarea name="message" id="message" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection