@extends('user.layouts.app')

@section('title', 'Pengaturan Akun - GriyaOne')

@section('content')
<div class="max-w-4xl mx-auto fade-in">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Pengaturan Akun</h1>
        <p class="text-gray-600 mt-2">Kelola informasi profil dan keamanan akun Anda</p>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar with Profile Picture -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                <!-- Profile Avatar -->
                <div class="flex flex-col items-center mb-6">
                    @if($user->profilePhotoUrl())
                        <img src="{{ $user->profilePhotoUrl() }}" alt="Foto Profil" class="w-20 h-20 rounded-full object-cover mb-4 border-4 border-orange-100">
                    @else
                        <div class="w-20 h-20 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center text-white font-bold text-2xl mb-4">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <h3 class="text-lg font-semibold text-gray-900 text-center">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Pengguna Biasa</p>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Email</p>
                            <p class="text-sm text-gray-700 break-all">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nomor HP</p>
                            <p class="text-sm text-gray-700">{{ $user->phone ?? 'Belum diisi' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Bergabung Sejak</p>
                            <p class="text-sm text-gray-700">{{ $user->created_at->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-8">
                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="font-semibold text-red-900">Terjadi Kesalahan</p>
                                <ul class="text-sm text-red-700 mt-2 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="font-semibold text-green-900">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Tabs Navigation -->
                <div class="mb-8 border-b border-gray-200">
                    <div class="flex gap-8">
                        <button type="button" onclick="switchTab(event, 'profil')" class="tab-btn active px-1 py-3 border-b-2 border-orange-600 text-orange-600 font-semibold text-sm transition" data-tab="profil">
                            Informasi Profil
                        </button>
                        <button type="button" onclick="switchTab(event, 'password')" class="tab-btn px-1 py-3 text-gray-600 font-semibold text-sm transition hover:text-orange-600" data-tab="password">
                            Ubah Password
                        </button>
                    </div>
                </div>

                <!-- Tab: Profil -->
                <form id="profil-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="tab-content active">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="form_type" value="profile">

                    <!-- Foto Profil -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-900 mb-3">Foto Profil</label>
                        <div class="flex items-center gap-4">
                            <div id="previewPhoto" class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0 border-2 border-gray-200 overflow-hidden">
                                @if($user->profilePhotoUrl())
                                    <img src="{{ $user->profilePhotoUrl() }}" alt="Preview" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-bold text-xl">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" name="profile_photo" id="profile_photo" accept="image/*" onchange="previewProfilePhoto(this)" class="block w-full text-sm text-gray-500 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100">
                                <p class="text-xs text-gray-500 mt-2">JPG, PNG, WebP maksimal 2MB</p>
                            </div>
                        </div>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ $user->name }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">Email</label>
                        <input type="email" name="email" id="email" value="{{ $user->email }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                    </div>

                    <!-- Nomor HP -->
                    <div class="mb-6">
                        <label for="phone" class="block text-sm font-semibold text-gray-900 mb-2">Nomor HP</label>
                        <input type="tel" name="phone" id="phone" value="{{ $user->phone ?? '' }}" placeholder="+62 812 3456 7890" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 px-6 py-3 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 transition shadow-md hover:shadow-lg">
                            Simpan Perubahan
                        </button>
                        <button type="reset" class="flex-1 px-6 py-3 bg-gray-200 text-gray-900 font-semibold rounded-lg hover:bg-gray-300 transition">
                            Batal
                        </button>
                    </div>
                </form>

                <!-- Tab: Password -->
                <form id="password-form" action="{{ route('profile.update') }}" method="POST" class="tab-content hidden">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="form_type" value="password">

                    <!-- Password Lama -->
                    <div class="mb-6">
                        <label for="current_password" class="block text-sm font-semibold text-gray-900 mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                    </div>

                    <!-- Password Baru -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">Password Baru</label>
                        <input type="password" name="password" id="password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                        <p class="text-xs text-gray-500 mt-2">Minimal 8 karakter, kombinasi huruf, angka, dan simbol</p>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 px-6 py-3 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 transition shadow-md hover:shadow-lg">
                            Ubah Password
                        </button>
                        <button type="reset" class="flex-1 px-6 py-3 bg-gray-200 text-gray-900 font-semibold rounded-lg hover:bg-gray-300 transition">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(event, tabName) {
        event.preventDefault();

        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
        });

        // Remove active class from all tabs
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('active', 'border-b-2', 'border-orange-600', 'text-orange-600');
            el.classList.add('text-gray-600');
        });

        // Show selected tab
        document.getElementById(tabName + '-form').classList.remove('hidden');

        // Add active class to clicked tab
        event.target.classList.add('active', 'border-b-2', 'border-orange-600', 'text-orange-600');
        event.target.classList.remove('text-gray-600');
    }

    function previewProfilePhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = (e) => {
                const preview = document.getElementById('previewPhoto');
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`;
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<style>
    .tab-btn {
        border-bottom: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .tab-btn.active {
        border-bottom-color: currentColor;
    }

    .tab-content {
        animation: fadeIn 0.3s ease-out;
    }

    .tab-content.hidden {
        display: none;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
@endsection
