@extends('admin.layouts.app')

@section('title', 'Pengaturan Login Page - GriyaOne')
@section('role', 'Pengaturan Login Page')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3 mb-1">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white flex-shrink-0" style="background:linear-gradient(135deg,#ea580c,#f97316);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Pengaturan Halaman Login</h2>
            <p class="text-sm text-gray-500">Edit teks yang ditampilkan pada halaman login GriyaOne</p>
        </div>
    </div>
</div>

@if(session('success'))
<div class="mb-5 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium" style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534;">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 items-start">

    <!-- ========== LEFT: FORM ========== -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            <h3 class="font-bold text-gray-800 text-sm">Edit Konten</h3>
        </div>

        <form method="POST" action="{{ route('admin.login-settings.update') }}" id="settingsForm" class="p-6 space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            {{-- NAMA WEB --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1.5 h-5 rounded-full" style="background:#f97316;"></div>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Aplikasi / Web</span>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Web <span class="text-gray-400 font-normal">(tampil di navbar & login)</span></label>
                    <input type="text" name="site_name" id="f_site_name"
                        value="{{ old('site_name', $settings['site_name'] ?? 'GriyaOne') }}"
                        class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none transition"
                        placeholder="GriyaOne"
                        oninput="syncPreview()">
                </div>
            </div>

            {{-- LOGO UPLOAD --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1.5 h-5 rounded-full" style="background:#f97316;"></div>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Logo Aplikasi</span>
                </div>
                <div class="flex items-start gap-5 p-4 bg-gray-50 rounded-xl border border-gray-100">
                    {{-- Current logo preview --}}
                    <div class="flex-shrink-0">
                        @if(!empty($settings['login_logo_path']))
                            <img id="logoPreview" src="{{ asset('storage/' . $settings['login_logo_path']) }}"
                                alt="Logo" class="w-16 h-16 object-contain rounded-xl border border-gray-200 bg-white p-1 shadow-sm">
                        @else
                            <div id="logoPreview" class="w-16 h-16 rounded-xl flex items-center justify-center shadow-sm" style="background:linear-gradient(135deg,#ea580c,#f97316);">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="white"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Unggah Logo Baru
                            <span class="font-normal text-gray-400">(JPG, PNG, SVG, WebP — maks 2 MB)</span>
                        </label>
                        <input type="file" name="login_logo" id="logoInput" accept="image/*"
                            class="block w-full text-xs text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:text-white cursor-pointer"
                            style="--tw-file-border:none;"
                            onchange="previewLogo(event)"
                            x-style="file:background:linear-gradient(135deg,#ea580c,#f97316)">
                        <style>
                            #logoInput::file-selector-button {
                                background: linear-gradient(135deg,#ea580c,#f97316);
                                color: #fff;
                                font-weight: 600;
                                font-size: 11px;
                                padding: 5px 12px;
                                border-radius: 7px;
                                border: none;
                                cursor: pointer;
                                margin-right: 10px;
                            }
                            #logoInput::file-selector-button:hover {
                                background: linear-gradient(135deg,#c2410c,#ea580c);
                            }
                        </style>
                        @if(!empty($settings['login_logo_path']))
                            <label class="inline-flex items-center gap-1.5 mt-2.5 cursor-pointer">
                                <input type="checkbox" name="remove_logo" value="1" class="rounded accent-red-500">
                                <span class="text-xs text-red-500 font-semibold">Hapus logo (gunakan ikon default)</span>
                            </label>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">Biarkan kosong jika tidak ingin mengubah logo saat ini.</p>
                    </div>
                </div>
            </div>

            {{-- BRAND PANEL --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1.5 h-5 rounded-full" style="background:#ea580c;"></div>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Panel Kiri (Brand)</span>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tagline Aplikasi <span class="text-gray-400 font-normal">(di bawah logo)</span></label>
                        <input type="text" name="login_app_tagline" id="f_app_tagline"
                            value="{{ old('login_app_tagline', $settings['login_app_tagline'] ?? 'Sistem Manajemen Properti') }}"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition"
                            oninput="syncPreview()">
                        @error('login_app_tagline')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Badge Teks <span class="text-gray-400 font-normal">(misal: "Platform Terpercaya")</span></label>
                        <input type="text" name="login_badge" id="f_badge"
                            value="{{ old('login_badge', $settings['login_badge'] ?? 'Platform Terpercaya') }}"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition"
                            oninput="syncPreview()">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Judul Utama</label>
                            <input type="text" name="login_heading" id="f_heading"
                                value="{{ old('login_heading', $settings['login_heading'] ?? 'Kelola Properti') }}"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition"
                                oninput="syncPreview()">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kata Sorotan <span class="text-gray-400">(oranye)</span></label>
                            <input type="text" name="login_heading_highlight" id="f_highlight"
                                value="{{ old('login_heading_highlight', $settings['login_heading_highlight'] ?? 'Lebih Mudah') }}"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition"
                                oninput="syncPreview()">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Deskripsi</label>
                        <textarea name="login_description" id="f_description" rows="3"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition resize-none"
                            oninput="syncPreview()">{{ old('login_description', $settings['login_description'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- FEATURES --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1.5 h-5 rounded-full" style="background:#f97316;"></div>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Fitur Unggulan (3 item)</span>
                </div>
                <div class="space-y-3">
                    @foreach([1,2,3] as $i)
                    <div class="bg-gray-50 rounded-xl p-3 space-y-2 border border-gray-100">
                        <span class="text-xs font-bold text-orange-500">Fitur {{ $i }}</span>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Judul</label>
                                <input type="text" name="login_feature_{{ $i }}_title" id="f_ft{{ $i }}t"
                                    value="{{ old('login_feature_'.$i.'_title', $settings['login_feature_'.$i.'_title'] ?? '') }}"
                                    class="w-full px-2.5 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition"
                                    oninput="syncPreview()">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Deskripsi</label>
                                <input type="text" name="login_feature_{{ $i }}_desc" id="f_ft{{ $i }}d"
                                    value="{{ old('login_feature_'.$i.'_desc', $settings['login_feature_'.$i.'_desc'] ?? '') }}"
                                    class="w-full px-2.5 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition"
                                    oninput="syncPreview()">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- FORM PANEL --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1.5 h-5 rounded-full bg-gray-400"></div>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Panel Kanan (Form Login)</span>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Judul Form <span class="text-gray-400">("Selamat Datang")</span></label>
                        <input type="text" name="login_welcome" id="f_welcome"
                            value="{{ old('login_welcome', $settings['login_welcome'] ?? 'Selamat Datang') }}"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition"
                            oninput="syncPreview()">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Subjudul Form</label>
                        <input type="text" name="login_welcome_sub" id="f_welcome_sub"
                            value="{{ old('login_welcome_sub', $settings['login_welcome_sub'] ?? 'Masuk ke akun GriyaOne Anda') }}"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition"
                            oninput="syncPreview()">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Nomor WhatsApp CS
                            <span class="font-normal text-gray-400">(format: 628123456789)</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <span class="flex items-center gap-1 px-3 py-2 bg-green-50 border border-green-200 rounded-lg text-xs font-semibold text-green-700 flex-shrink-0">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="#22c55e"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M11.984 0C5.375 0 0 5.373 0 11.997c0 2.12.554 4.107 1.523 5.832L.03 23.997l6.312-1.654A11.956 11.956 0 0011.984 24c6.608 0 11.984-5.373 11.984-11.997 0-6.625-5.376-12.003-11.984-12.003z"/></svg>
                                wa.me/
                            </span>
                            <input type="text" name="login_wa_number" id="f_wa_number"
                                value="{{ old('login_wa_number', $settings['login_wa_number'] ?? '') }}"
                                placeholder="628123456789"
                                class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-100 transition font-mono"
                                oninput="syncPreview()">
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin menampilkan tombol WhatsApp di halaman login.</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit" class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-bold shadow-md transition active:scale-95" style="background:linear-gradient(135deg,#ea580c,#f97316);"
                    onmouseover="this.style.background='linear-gradient(135deg,#c2410c,#ea580c)'"
                    onmouseout="this.style.background='linear-gradient(135deg,#ea580c,#f97316)'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
                <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Buka Login
                </a>
            </div>
        </form>
    </div>

    <!-- ========== RIGHT: LIVE PREVIEW ========== -->
    <div class="sticky top-24">
        <div class="flex items-center gap-2 mb-3">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Preview Langsung</span>
            <span class="ml-auto text-xs text-gray-400 italic">Diperbarui saat Anda mengetik</span>
        </div>

        <!-- Preview container — miniature login page -->
        <div class="rounded-2xl overflow-hidden shadow-xl border border-gray-200" style="transform-origin:top left;">
            <!-- Browser chrome bar -->
            <div class="flex items-center gap-1.5 px-3 py-2 bg-gray-800">
                <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                <div class="ml-3 flex-1 bg-gray-700 rounded px-2 py-0.5 text-xs text-gray-400 font-mono">griyaone.com/login</div>
            </div>

            <!-- Actual preview -->
            <div style="display:flex; width:100%; font-family:'Figtree',sans-serif; font-size:11px;">

                {{-- Brand panel --}}
                <div id="prev_panel" style="flex:0 0 46%; padding:20px 18px; display:flex; flex-direction:column; justify-content:space-between; min-height:420px; position:relative; overflow:hidden;" class="brand-panel-prev">
                    <style>
                        .brand-panel-prev { background: linear-gradient(160deg, #111 0%, #1c1c1c 40%, #2a1a0a 100%); }
                    </style>
                    <!-- Logo -->
                    <div style="display:flex; align-items:center; gap:8px;">
                        @if(!empty($settings['login_logo_path']))
                            <img id="prev_logo_img" src="{{ asset('storage/' . $settings['login_logo_path']) }}"
                                style="width:28px; height:28px; border-radius:7px; object-fit:contain; background:#fff;" alt="Logo">
                            <div id="prev_logo_default" style="width:28px; height:28px; border-radius:7px; background:linear-gradient(135deg,#ea580c,#f97316); display:none; align-items:center; justify-content:center; flex-shrink:0;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="white"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                            </div>
                        @else
                            <div id="prev_logo_default" style="width:28px; height:28px; border-radius:7px; background:linear-gradient(135deg,#ea580c,#f97316); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="white"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                            </div>
                            <img id="prev_logo_img" style="width:28px; height:28px; border-radius:7px; object-fit:contain; display:none;" alt="Logo">
                        @endif
                        <div>
                            <div style="font-size:11px; font-weight:800; color:#fff; letter-spacing:-0.01em;"><span id="prev_site_name">{{ $settings['site_name'] ?? 'GriyaOne' }}</span></div>
                            <div id="prev_tagline" style="font-size:8px; color:#f97316; font-weight:600; letter-spacing:0.04em; text-transform:uppercase; line-height:1.2;"></div>
                        </div>
                    </div>

                    <!-- Center -->
                    <div style="position:relative; z-index:1; margin:16px 0;">
                        <div id="prev_badge" style="display:inline-flex; align-items:center; gap:5px; background:rgba(234,88,12,0.15); border:1px solid rgba(234,88,12,0.3); border-radius:999px; padding:3px 9px; margin-bottom:10px;">
                            <span style="width:4px; height:4px; background:#f97316; border-radius:50%; display:inline-block;"></span>
                            <span style="font-size:8px; color:#fb923c; font-weight:600;"></span>
                        </div>
                        <div style="font-size:16px; font-weight:800; color:#fff; line-height:1.2; letter-spacing:-0.02em; margin-bottom:8px;">
                            <span id="prev_heading"></span><br>
                            <span id="prev_highlight" style="background:linear-gradient(90deg,#f97316,#ea580c); -webkit-background-clip:text; -webkit-text-fill-color:transparent;"></span>
                        </div>
                        <p id="prev_desc" style="font-size:8px; color:#9ca3af; line-height:1.6; margin:0 0 12px;"></p>

                        <div style="display:flex; flex-direction:column; gap:5px;">
                            @foreach([1,2,3] as $i)
                            <div class="prev_feature" style="display:flex; align-items:flex-start; gap:6px; padding:6px 8px; border-radius:7px; background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.07);">
                                <div style="width:20px; height:20px; border-radius:5px; background:rgba(234,88,12,0.15); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    @if($i===1)
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                    @elseif($i===2)
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                    @else
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <div id="prev_ft{{ $i }}t" style="font-size:8px; font-weight:700; color:#f3f4f6;"></div>
                                    <div id="prev_ft{{ $i }}d" style="font-size:7px; color:#6b7280; margin-top:1px;"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div style="font-size:7px; color:#4b5563;">&copy; 2026 <span id="prev_site_name_foot">{{ $settings['site_name'] ?? 'GriyaOne' }}</span></div>
                </div>

                {{-- Form panel --}}
                <div style="flex:1; padding:20px 16px; background:#f9fafb; display:flex; align-items:center;">
                    <div style="width:100%;">
                        <!-- Card -->
                        <div style="background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.08); border:1px solid #f3f4f6;">
                            <div style="height:3px; background:linear-gradient(90deg,#ea580c,#f97316,#ea580c);"></div>
                            <div style="padding:16px 14px;">
                                <div id="prev_welcome" style="font-size:14px; font-weight:800; color:#111827; margin-bottom:2px; letter-spacing:-0.01em;"></div>
                                <div id="prev_welcome_sub" style="font-size:8px; color:#6b7280; margin-bottom:14px;"></div>

                                {{-- Email field --}}
                                <div style="margin-bottom:10px;">
                                    <div style="font-size:8px; font-weight:700; color:#374151; margin-bottom:4px;">Alamat Email</div>
                                    <div style="padding:6px 10px 6px 28px; border:1.5px solid #e5e7eb; border-radius:7px; background:#fafafa; font-size:8px; color:#9ca3af; position:relative;">
                                        <span style="position:absolute; left:9px; top:50%; transform:translateY(-50%);">
                                            <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                        </span>
                                        nama@contoh.com
                                    </div>
                                </div>

                                {{-- Password field --}}
                                <div style="margin-bottom:12px;">
                                    <div style="font-size:8px; font-weight:700; color:#374151; margin-bottom:4px;">Password</div>
                                    <div style="padding:6px 10px 6px 28px; border:1.5px solid #e5e7eb; border-radius:7px; background:#fafafa; font-size:8px; color:#9ca3af; position:relative;">
                                        <span style="position:absolute; left:9px; top:50%; transform:translateY(-50%);">
                                            <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                                        </span>
                                        ••••••••
                                    </div>
                                </div>

                                {{-- Button --}}
                                <div style="padding:7px; border-radius:7px; text-align:center; font-size:8px; font-weight:700; color:#fff; background:linear-gradient(135deg,#ea580c,#f97316); box-shadow:0 2px 8px rgba(234,88,12,0.3);">
                                    Masuk ke Dashboard
                                </div>
                            </div>
                        </div>

                        <!-- Demo credentials mini -->
                        <div style="margin-top:8px; padding:8px 10px; background:#fff7ed; border:1px solid #fed7aa; border-radius:8px;">
                            <div style="font-size:7px; font-weight:800; color:#9a3412; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:4px;">Akun Demo</div>
                            <div style="font-size:7px; color:#78350f; font-family:monospace;">user@test.com / password</div>
                        </div>

                        <!-- WA button preview -->
                        <div id="prev_wa" style="margin-top:8px; display:{{ !empty($settings['login_wa_number']) ? 'flex' : 'none' }}; align-items:center; justify-content:center; gap:4px; padding:5px 10px; background:#dcfce7; border:1px solid #86efac; border-radius:8px;">
                            <svg width="9" height="9" viewBox="0 0 24 24" fill="#16a34a"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M11.984 0C5.375 0 0 5.373 0 11.997c0 2.12.554 4.107 1.523 5.832L.03 23.997l6.312-1.654A11.956 11.956 0 0011.984 24c6.608 0 11.984-5.373 11.984-11.997 0-6.625-5.376-12.003-11.984-12.003z"/></svg>
                            <span style="font-size:7px; font-weight:700; color:#15803d;">Hubungi CS via </span>
                            <span id="prev_wa_link" style="font-size:7px; font-family:monospace; color:#166534;">{{ !empty($settings['login_wa_number']) ? '+'.$settings['login_wa_number'] : '' }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <p class="text-xs text-gray-400 text-center mt-2">Preview tidak 100% identik — hanya gambaran konten teks</p>
    </div>

</div>

<script>
function val(id) {
    const el = document.getElementById(id);
    return el ? el.value : '';
}
function setText(id, text) {
    const el = document.getElementById(id);
    if (el) el.textContent = text;
}
function syncPreview() {
    const sn = val('f_site_name') || 'GriyaOne';
    setText('prev_site_name', sn);
    setText('prev_site_name_foot', sn);
    setText('prev_tagline',      val('f_app_tagline'));
    // badge inner span
    const badgeSpan = document.querySelector('#prev_badge span:last-child');
    if (badgeSpan) badgeSpan.textContent = val('f_badge');
    setText('prev_heading',      val('f_heading'));
    setText('prev_highlight',    val('f_highlight'));
    setText('prev_desc',         val('f_description'));
    setText('prev_ft1t',         val('f_ft1t'));
    setText('prev_ft1d',         val('f_ft1d'));
    setText('prev_ft2t',         val('f_ft2t'));
    setText('prev_ft2d',         val('f_ft2d'));
    setText('prev_ft3t',         val('f_ft3t'));
    setText('prev_ft3d',         val('f_ft3d'));
    setText('prev_welcome',      val('f_welcome'));
    setText('prev_welcome_sub',  val('f_welcome_sub'));

    // WhatsApp preview
    const waNum = val('f_wa_number').trim();
    const waPreview = document.getElementById('prev_wa');
    if (waPreview) {
        if (waNum) {
            waPreview.style.display = 'flex';
            const waLink = document.getElementById('prev_wa_link');
            if (waLink) waLink.textContent = '+' + waNum.replace(/^62/, '62');
        } else {
            waPreview.style.display = 'none';
        }
    }
}

// Live logo preview before upload
function previewLogo(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('logoPreview');
        if (preview) {
            // Replace whatever is in the preview with an img
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                const img = document.createElement('img');
                img.id = 'logoPreview';
                img.src = e.target.result;
                img.alt = 'Logo';
                img.className = 'w-16 h-16 object-contain rounded-xl border border-gray-200 bg-white p-1 shadow-sm';
                preview.parentNode.replaceChild(img, preview);
            }
        }
        // Also update preview panel logo
        const prevLogo = document.getElementById('prev_logo_img');
        if (prevLogo) {
            prevLogo.src = e.target.result;
            prevLogo.style.display = 'block';
            const prevLogoDefault = document.getElementById('prev_logo_default');
            if (prevLogoDefault) prevLogoDefault.style.display = 'none';
        }
    };
    reader.readAsDataURL(file);
}

// Init preview on page load
document.addEventListener('DOMContentLoaded', syncPreview);
</script>
@endsection
