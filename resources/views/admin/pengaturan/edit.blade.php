@extends('admin.layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Pengaturan Akun</h1>
        <p class="text-gray-600 mt-2">Kelola informasi profil dan preferensi akun Anda</p>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar with Profile Picture -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <!-- Profile Avatar -->
                <div class="flex flex-col items-center mb-6">
                    @if($user->profilePhotoUrl())
                        <img src="{{ $user->profilePhotoUrl() }}" alt="Foto Profil" class="w-20 h-20 rounded-full object-cover mb-4 border border-gray-200">
                    @else
                        <div class="w-20 h-20 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center text-white font-bold text-2xl mb-4">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                    <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ ucfirst($user->role) }}</p>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Email</p>
                            <p class="text-sm text-gray-700">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Role</p>
                            <span class="inline-block px-3 py-1 font-semibold text-xs rounded-full
                                @if($user->role === 'admin')
                                    bg-purple-100 text-purple-700
                                @elseif($user->role === 'marketing')
                                    bg-orange-100 text-orange-700
                                @else
                                    bg-gray-100 text-gray-700
                                @endif
                            ">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Bergabung Sejak</p>
                            <p class="text-sm text-gray-700">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <!-- Success Message -->
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

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="font-semibold text-green-900">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Tabs -->
                <div class="mb-8 border-b border-gray-200">
                    <div class="flex gap-8">
                        <button type="button" class="px-1 py-3 border-b-2 border-red-600 text-red-600 font-semibold text-sm transition" onclick="switchTab(event, 'profil')">
                            Informasi Profil
                        </button>
                        <button type="button" class="px-1 py-3 border-b-2 border-transparent text-gray-600 hover:text-gray-900 font-semibold text-sm transition" onclick="switchTab(event, 'password')">
                            Ubah Password
                        </button>
                    </div>
                </div>

                <!-- Profil Tab -->
                <div id="profil" class="tab-content">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="form_type" value="profile">

                        <div class="space-y-6">
                            <!-- Nama -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Nama Lengkap</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition @error('name') border-red-500 @enderror"
                                    placeholder="Masukkan nama lengkap">
                                @error('name')
                                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition @error('email') border-red-500 @enderror"
                                    placeholder="Masukkan email">
                                @error('email')
                                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nomor Telepon -->
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-900 mb-2">Nomor Telepon</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition @error('phone') border-red-500 @enderror"
                                    placeholder="Masukkan nomor telepon">
                                @error('phone')
                                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Foto Profil -->
                            <div>
                                <label for="profile_photo" class="block text-sm font-semibold text-gray-900 mb-2">Foto Profil</label>
                                <input type="file" id="profile_photo" name="profile_photo" accept="image/png,image/jpeg,image/jpg,image/webp"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition @error('profile_photo') border-red-500 @enderror">
                                <p class="text-xs text-gray-500 mt-2">Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB.</p>
                                @error('profile_photo')
                                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex gap-3 pt-4">
                                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition duration-200">
                                    Simpan Perubahan
                                </button>
                                <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition duration-200">
                                    Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Password Tab -->
                <div id="password" class="tab-content hidden">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="form_type" value="password">

                        <div class="space-y-6">
                            <!-- Current Password -->
                            <div>
                                <label for="current_password" class="block text-sm font-semibold text-gray-900 mb-2">Password Saat Ini</label>
                                <input type="password" id="current_password" name="current_password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition @error('current_password') border-red-500 @enderror"
                                    placeholder="Masukkan password saat ini">
                                @error('current_password')
                                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">Password Baru</label>
                                <input type="password" id="password" name="password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition @error('password') border-red-500 @enderror"
                                    placeholder="Masukkan password baru">
                                <p class="text-xs text-gray-500 mt-2">Minimal 8 karakter</p>
                                @error('password')
                                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 mb-2">Konfirmasi Password Baru</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition"
                                    placeholder="Konfirmasi password baru">
                            </div>

                            <!-- Info Box -->
                            <div class="bg-orange-50 border border-orange-200 p-4 rounded-lg">
                                <div class="flex gap-3">
                                    <svg class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="text-sm text-orange-700">
                                        <p class="font-semibold">Tips Keamanan Password</p>
                                        <ul class="list-disc list-inside mt-2 text-xs space-y-1">
                                            <li>Gunakan kombinasi huruf besar, kecil, angka, dan simbol</li>
                                            <li>Jangan gunakan informasi pribadi Anda</li>
                                            <li>Hindari password yang mudah ditebak</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex gap-3 pt-4">
                                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition duration-200">
                                    Perbarui Password
                                </button>
                                <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition duration-200">
                                    Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(event, tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });

        // Show selected tab
        document.getElementById(tabName).classList.remove('hidden');

        // Update button styles
        document.querySelectorAll('[onclick*="switchTab"]').forEach(btn => {
            btn.classList.remove('border-red-600', 'text-red-600');
            btn.classList.add('border-transparent', 'text-gray-600');
        });

        event.currentTarget.classList.remove('border-transparent', 'text-gray-600');
        event.currentTarget.classList.add('border-red-600', 'text-red-600');
    }
</script>
@endsection


