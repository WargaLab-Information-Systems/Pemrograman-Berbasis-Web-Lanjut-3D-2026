@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

    {{-- Hero --}}
    <section class="relative overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">

            <div class="grid items-center gap-12 lg:grid-cols-2">

                {{-- Text --}}
                <div>

                    <span class="inline-flex items-center gap-2 rounded-full bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700">
                        <i class="fa-solid fa-book-open"></i>
                        Sistem Informasi Perpustakaan
                    </span>

                    <h1 class="mt-6 text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl lg:text-6xl">
                        Temukan Buku,
                        <span class="text-indigo-600">
                            Perluas Pengetahuan.
                        </span>
                    </h1>

                    <p class="mt-6 max-w-xl text-base leading-7 text-slate-600 sm:text-lg">
                        Jelajahi koleksi buku yang tersedia di perpustakaan.
                        Temukan informasi buku, penulis, tahun terbit,
                        dan kategorinya dengan mudah.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">

                        <a
                            href="{{ route('buku.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-indigo-700 hover:shadow-md"
                        >
                            <i class="fa-solid fa-book"></i>
                            Jelajahi Buku
                        </a>

                        <a
                            href="#tentang"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition duration-200 hover:bg-slate-50"
                        >
                            <i class="fa-solid fa-circle-info"></i>
                            Tentang Perpustakaan
                        </a>

                    </div>

                </div>


                {{-- Illustration --}}
                <div class="relative">

                    <div class="relative mx-auto max-w-md">

                        <div class="absolute -inset-4 rounded-[2rem] bg-indigo-100/70 blur-2xl"></div>

                        <div class="relative rounded-3xl border border-slate-200 bg-white p-6 shadow-xl">

                            <div class="rounded-2xl bg-gradient-to-br from-indigo-600 to-indigo-800 p-8 text-white">

                                <div class="flex items-center justify-between">

                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/15">
                                        <i class="fa-solid fa-book-open text-xl"></i>
                                    </div>

                                    <i class="fa-solid fa-star text-indigo-200"></i>

                                </div>

                                <div class="mt-12">
                                    <p class="text-sm text-indigo-200">
                                        Koleksi Perpustakaan
                                    </p>

                                    <h2 class="mt-2 text-2xl font-bold">
                                        Buku untuk Setiap Cerita
                                    </h2>
                                </div>

                            </div>

                            <div class="mt-5 grid grid-cols-3 gap-3">

                                <div class="rounded-xl bg-slate-50 p-4 text-center">
                                    <i class="fa-solid fa-book text-indigo-600"></i>
                                    <p class="mt-2 text-xs font-medium text-slate-500">
                                        Koleksi
                                    </p>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4 text-center">
                                    <i class="fa-solid fa-user-pen text-indigo-600"></i>
                                    <p class="mt-2 text-xs font-medium text-slate-500">
                                        Penulis
                                    </p>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4 text-center">
                                    <i class="fa-solid fa-tags text-indigo-600"></i>
                                    <p class="mt-2 text-xs font-medium text-slate-500">
                                        Kategori
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </section>


    {{-- Tentang --}}
    <section id="tentang" class="border-y border-slate-200 bg-white">

        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">

            <div class="mx-auto max-w-2xl text-center">

                <span class="text-sm font-semibold text-indigo-600">
                    Tentang Aplikasi
                </span>

                <h2 class="mt-2 text-3xl font-bold text-slate-900">
                    Perpustakaan Digital
                </h2>

                <p class="mt-4 leading-7 text-slate-600">
                    Aplikasi sederhana untuk membantu pengguna melihat
                    koleksi buku dan mendapatkan informasi mengenai setiap
                    buku yang tersedia.
                </p>

            </div>


            <div class="mt-10 grid gap-5 sm:grid-cols-3">

                {{-- Feature 1 --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>

                    <h3 class="mt-5 font-bold text-slate-900">
                        Mudah Dicari
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Lihat daftar koleksi buku dengan informasi yang
                        tersusun secara sederhana dan jelas.
                    </p>

                </div>


                {{-- Feature 2 --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>

                    <h3 class="mt-5 font-bold text-slate-900">
                        Informasi Lengkap
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Lihat judul, penulis, tahun terbit, kategori,
                        dan informasi lainnya pada halaman detail.
                    </p>

                </div>


                {{-- Feature 3 --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                        <i class="fa-solid fa-mobile-screen"></i>
                    </div>

                    <h3 class="mt-5 font-bold text-slate-900">
                        Responsive
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Tampilan dapat digunakan dengan nyaman pada
                        perangkat desktop maupun mobile.
                    </p>

                </div>

            </div>

        </div>

    </section>


    {{-- CTA --}}
    <section>

        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">

            <div class="overflow-hidden rounded-3xl bg-indigo-600 px-6 py-10 text-center text-white sm:px-10">

                <i class="fa-solid fa-book-open text-3xl text-indigo-200"></i>

                <h2 class="mt-4 text-2xl font-bold sm:text-3xl">
                    Siap Menjelajahi Koleksi Buku?
                </h2>

                <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-indigo-100 sm:text-base">
                    Lihat seluruh buku yang tersedia dan temukan
                    informasi buku yang kamu cari.
                </p>

                <a
                    href="{{ route('buku.index') }}"
                    class="mt-6 inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-indigo-700 transition duration-200 hover:-translate-y-0.5 hover:bg-indigo-50"
                >
                    <i class="fa-solid fa-arrow-right"></i>
                    Lihat Daftar Buku
                </a>

            </div>

        </div>

    </section>

@endsection