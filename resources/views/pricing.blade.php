@extends('layouts.app')

@section('title', 'Harga')

@section('content')
    <div class="py-20 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl">
                    Pilih Paket yang Tepat
                </h1>
                <p class="mt-4 text-xl text-gray-600 dark:text-gray-400">
                    Mulai gratis dan upgrade sesuai kebutuhan bisnis Anda
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Free Plan -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Gratis</h3>
                    <p class="mt-4 text-gray-600 dark:text-gray-400">Untuk UMKM yang baru memulai</p>
                    <p class="mt-8">
                        <span class="text-4xl font-extrabold text-gray-900 dark:text-white">Rp 0</span>
                        <span class="text-gray-600 dark:text-gray-400">/bulan</span>
                    </p>
                    <ul class="mt-8 space-y-4">
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="ml-3 text-gray-600 dark:text-gray-400">Maksimal 100 produk</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="ml-3 text-gray-600 dark:text-gray-400">1 User</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="ml-3 text-gray-600 dark:text-gray-400">Laporan Dasar</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}"
                        class="mt-8 block w-full bg-indigo-600 text-white rounded-md py-3 text-center font-semibold hover:bg-indigo-700">
                        Mulai Gratis
                    </a>
                </div>

                <!-- Pro Plan -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8 border-2 border-indigo-600 transform scale-105">
                    <div class="absolute top-0 right-0 -mt-3 -mr-3">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-600 text-white">
                            POPULER
                        </span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Pro</h3>
                    <p class="mt-4 text-gray-600 dark:text-gray-400">Untuk bisnis yang berkembang</p>
                    <p class="mt-8">
                        <span class="text-4xl font-extrabold text-gray-900 dark:text-white">Rp 99.000</span>
                        <span class="text-gray-600 dark:text-gray-400">/bulan</span>
                    </p>
                    <ul class="mt-8 space-y-4">
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="ml-3 text-gray-600 dark:text-gray-400">Produk Unlimited</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="ml-3 text-gray-600 dark:text-gray-400">5 User</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="ml-3 text-gray-600 dark:text-gray-400">Laporan Lengkap</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="ml-3 text-gray-600 dark:text-gray-400">Export Excel & PDF</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}"
                        class="mt-8 block w-full bg-indigo-600 text-white rounded-md py-3 text-center font-semibold hover:bg-indigo-700">
                        Coba Gratis 14 Hari
                    </a>
                </div>

                <!-- Enterprise Plan -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Enterprise</h3>
                    <p class="mt-4 text-gray-600 dark:text-gray-400">Untuk bisnis besar</p>
                    <p class="mt-8">
                        <span class="text-4xl font-extrabold text-gray-900 dark:text-white">Custom</span>
                    </p>
                    <ul class="mt-8 space-y-4">
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="ml-3 text-gray-600 dark:text-gray-400">Semua fitur Pro</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="ml-3 text-gray-600 dark:text-gray-400">Unlimited User</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="ml-3 text-gray-600 dark:text-gray-400">API Access</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="ml-3 text-gray-600 dark:text-gray-400">Support 24/7</span>
                        </li>
                    </ul>
                    <a href="{{ route('contact') }}"
                        class="mt-8 block w-full bg-gray-800 dark:bg-gray-900 text-white rounded-md py-3 text-center font-semibold hover:bg-gray-900">
                        Hubungi Sales
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection