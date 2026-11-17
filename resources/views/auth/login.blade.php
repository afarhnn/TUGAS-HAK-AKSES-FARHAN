@extends('layouts.dosen')

@section('title', 'Login')

@section('content')
    <div class="mx-auto max-w-md pt-8">
        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="mb-6 border-b border-slate-200 pb-5 text-center">
                <p class="text-sm font-medium text-emerald-700">
                    Sistem Informasi Tugas Akhir dan Skripsi
                </p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">Login Sistem</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Silakan masukkan email dan password untuk melanjutkan
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    Periksa kembali data yang Anda masukkan.
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="grid gap-5">
                @csrf

                <div class="grid gap-2">
                    <label for="email" class="text-sm font-medium text-slate-700">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="contoh: admin@kampus.ac.id"
                        class="rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none"
                    >
                    @error('email')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-2">
                    <label for="password" class="text-sm font-medium text-slate-700">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        placeholder="Masukkan password"
                        class="rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none"
                    >
                    @error('password')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="flex h-10 w-full items-center justify-center rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-slate-700 focus:outline-none cursor-pointer"
                    >
                        Login
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection
