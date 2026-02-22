@php
    $s = \App\Models\Setting::getAllAsArray();
    $ls = function(string $key, string $default = '') use ($s): string {
        return htmlspecialchars($s[$key] ?? $default, ENT_QUOTES, 'UTF-8');
    };
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name', 'GriyaOne') }}</title>
    @if(!empty($s['login_logo_path']))
    <link rel="icon" href="{{ asset('storage/' . $s['login_logo_path']) }}">
    <link rel="shortcut icon" href="{{ asset('storage/' . $s['login_logo_path']) }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/' . $s['login_logo_path']) }}">
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Figtree', sans-serif; margin: 0; }

        .brand-panel {
            background: linear-gradient(160deg, #111111 0%, #1c1c1c 40%, #2a1a0a 100%);
            position: relative;
            overflow: hidden;
        }
        .brand-panel::before {
            content: '';
            position: absolute;
            top: -120px; left: -120px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(234,88,12,0.25) 0%, transparent 70%);
            pointer-events: none;
        }
        .brand-panel::after {
            content: '';
            position: absolute;
            bottom: -80px; right: -80px;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(249,115,22,0.2) 0%, transparent 70%);
            pointer-events: none;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 12px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
            transition: background 0.2s;
        }
        .feature-item:hover {
            background: rgba(234,88,12,0.08);
            border-color: rgba(234,88,12,0.2);
        }

        .input-field {
            width: 100%;
            padding: 11px 16px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fafafa;
            color: #111827;
        }
        .input-field:focus {
            border-color: #ea580c;
            box-shadow: 0 0 0 3px rgba(234,88,12,0.12);
            background: #fff;
        }
        .input-field::placeholder { color: #9ca3af; }

        .btn-primary {
            width: 100%;
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);
            box-shadow: 0 4px 15px rgba(234,88,12,0.35);
            transition: transform 0.15s, box-shadow 0.15s, background 0.2s;
            letter-spacing: 0.01em;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #c2410c 0%, #ea580c 100%);
            box-shadow: 0 6px 20px rgba(234,88,12,0.45);
            transform: translateY(-1px);
        }
        .btn-primary:active { transform: scale(0.98); }

        .show-pass-btn {
            position: absolute;
            right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer; color: #9ca3af;
            padding: 4px;
            display: flex; align-items: center;
            transition: color 0.2s;
        }
        .show-pass-btn:hover { color: #ea580c; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease both; }
        .fade-up-1 { animation-delay: 0.05s; }
        .fade-up-2 { animation-delay: 0.15s; }
        .fade-up-3 { animation-delay: 0.25s; }
    </style>
</head>
<body style="min-height:100vh; display:flex; background:#f3f4f6;">

    <!-- ===== TWO-PANEL LAYOUT ===== -->
    <div style="display:flex; width:100%; min-height:100vh;">

        <!-- LEFT: Brand Panel (hidden on small screens) -->
        <div class="brand-panel" style="flex:1; display:none; flex-direction:column; justify-content:space-between; padding:48px 44px;" id="brandPanel">

            <!-- Logo -->
            <div style="display:flex; align-items:center; gap:14px;">
                @if(!empty($s['login_logo_path']))
                    <img src="{{ asset('storage/' . $s['login_logo_path']) }}"
                        alt="Logo GriyaOne"
                        style="height:46px; width:auto; max-width:120px;border-radius: 12px; object-fit:contain; filter:drop-shadow(0 2px 8px rgba(0,0,0,0.35));">
                @else
                    <div style="width:46px; height:46px; border-radius:12px; background:linear-gradient(135deg,#ea580c,#f97316); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 14px rgba(255, 255, 255, 0.4);">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="white"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    </div>
                @endif
                <div>
                    <div style="font-size:20px; font-weight:800; color:#fff; letter-spacing:-0.02em;">{{ $s['site_name'] ?? 'GriyaOne' }}</div>
                    <div style="font-size:11px; color:#f97316; font-weight:600; letter-spacing:0.04em; text-transform:uppercase;">{!! $ls('login_app_tagline', 'Sistem Manajemen Properti') !!}</div>
                </div>
            </div>

            <!-- Center content -->
            <div style="position:relative; z-index:1;">
                <div style="display:inline-flex; align-items:center; gap:8px; background:rgba(234,88,12,0.15); border:1px solid rgba(234,88,12,0.3); border-radius:999px; padding:6px 14px; margin-bottom:28px;">
                    <span style="width:6px; height:6px; background:#f97316; border-radius:50%; display:inline-block;"></span>
                    <span style="font-size:12px; color:#fb923c; font-weight:600;">{!! $ls('login_badge', 'Platform Terpercaya') !!}</span>
                </div>
                <h1 style="font-size:38px; font-weight:800; color:#fff; line-height:1.15; letter-spacing:-0.03em; margin:0 0 16px;">
                    {!! $ls('login_heading', 'Kelola Properti') !!}<br>
                    <span style="background:linear-gradient(90deg,#f97316,#ea580c); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">{!! $ls('login_heading_highlight', 'Lebih Mudah') !!}</span>
                </h1>
                <p style="font-size:15px; color:#9ca3af; line-height:1.7; margin:0 0 36px;">
                    {!! $ls('login_description', 'Sistem manajemen properti terintegrasi untuk tim Anda — listing aset, materi pelatihan, dan informasi terkini dalam satu platform.') !!}
                </p>

                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div class="feature-item">
                        <div style="width:36px; height:36px; border-radius:9px; background:rgba(234,88,12,0.15); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </div>
                        <div>
                            <div style="font-size:13px; font-weight:700; color:#f3f4f6;">{!! $ls('login_feature_1_title', 'Listing Aset Properti') !!}</div>
                            <div style="font-size:12px; color:#6b7280; margin-top:2px;">{!! $ls('login_feature_1_desc', 'Kelola semua listing secara real-time') !!}</div>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div style="width:36px; height:36px; border-radius:9px; background:rgba(234,88,12,0.15); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                        </div>
                        <div>
                            <div style="font-size:13px; font-weight:700; color:#f3f4f6;">{!! $ls('login_feature_2_title', 'Multi-Role Dashboard') !!}</div>
                            <div style="font-size:12px; color:#6b7280; margin-top:2px;">{!! $ls('login_feature_2_desc', 'Admin, Marketing, dan User dalam satu sistem') !!}</div>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div style="width:36px; height:36px; border-radius:9px; background:rgba(234,88,12,0.15); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        </div>
                        <div>
                            <div style="font-size:13px; font-weight:700; color:#f3f4f6;">{!! $ls('login_feature_3_title', 'Laporan & Notifikasi') !!}</div>
                            <div style="font-size:12px; color:#6b7280; margin-top:2px;">{!! $ls('login_feature_3_desc', 'Pantau aktivitas dan info terbaru') !!}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer brand -->
            <div style="font-size:12px; color:#4b5563;">
                &copy; 2026 {{ $s['site_name'] ?? 'GriyaOne' }} &mdash; Semua hak dilindungi.
            </div>
        </div>

        <!-- RIGHT: Login Form -->
        <div style="flex:1; display:flex; align-items:center; justify-content:center; padding:32px 24px; background:#f9fafb; min-height:100vh;">
            <div style="width:100%; max-width:420px;">

                <!-- Mobile logo (visible only on small screens) -->
                <div style="text-align:center; margin-bottom:32px;" class="fade-up fade-up-1" id="mobileLogo">
                    @if(!empty($s['login_logo_path']))
                        <img src="{{ asset('storage/' . $s['login_logo_path']) }}"
                            alt="Logo GriyaOne"
                            style="display:inline-block; height:64px; width:auto; max-width:180px; object-fit:contain; margin-bottom:12px; filter:drop-shadow(0 2px 10px rgba(0,0,0,0.12));">
                    @else
                        <div style="display:inline-flex; align-items:center; justify-content:center; width:60px; height:60px; border-radius:16px; background:linear-gradient(135deg,#ea580c,#f97316); box-shadow:0 8px 24px rgba(234,88,12,0.35); margin-bottom:12px;">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="white"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                        </div>
                    @endif
                    <div style="font-size:22px; font-weight:800; color:#111827; letter-spacing:-0.02em;">{{ $s['site_name'] ?? 'GriyaOne' }}</div>
                    <div style="font-size:12px; color:#ea580c; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-top:2px;">{!! $ls('login_app_tagline', 'Sistem Manajemen Properti') !!}</div>
                </div>

                <!-- Form Card -->
                <div class="fade-up fade-up-2" style="background:#fff; border-radius:20px; box-shadow:0 4px 24px rgba(0,0,0,0.08); overflow:hidden; border:1px solid #f3f4f6;">

                    <!-- Top stripe -->
                    <div style="height:4px; background:linear-gradient(90deg, #ea580c 0%, #f97316 50%, #ea580c 100%);"></div>

                    <div style="padding:36px 32px;">
                        <h2 style="font-size:24px; font-weight:800; color:#111827; margin:0 0 4px; letter-spacing:-0.02em;">{!! $ls('login_welcome', 'Selamat Datang') !!}</h2>
                        <p style="font-size:14px; color:#6b7280; margin:0 0 28px;">{!! $ls('login_welcome_sub', 'Masuk ke akun GriyaOne Anda') !!}</p>

                        <form method="POST" action="/login">
                            @csrf

                            <!-- Alert error -->
                            @if ($errors->any() && !$errors->has('email') && !$errors->has('password'))
                                <div style="padding:12px 14px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; margin-bottom:20px; font-size:13px; color:#dc2626;">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <!-- Email -->
                            <div style="margin-bottom:18px;">
                                <label for="email" style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:7px; letter-spacing:0.01em;">
                                    Alamat Email
                                </label>
                                <div style="position:relative;">
                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        class="input-field"
                                        style="padding-left:42px;"
                                        placeholder="nama@contoh.com"
                                        required
                                        autofocus
                                    >
                                    <span style="position:absolute; left:13px; top:50%; transform:translateY(-50%); color:#9ca3af; pointer-events:none; display:flex;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                    </span>
                                </div>
                                @error('email')
                                    <p style="font-size:12px; color:#dc2626; margin-top:5px;">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div style="margin-bottom:18px;">
                                <label for="password" style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:7px; letter-spacing:0.01em;">
                                    Password
                                </label>
                                <div style="position:relative;">
                                    <input
                                        id="password"
                                        type="password"
                                        name="password"
                                        class="input-field"
                                        style="padding-left:42px; padding-right:42px;"
                                        placeholder="Masukkan password"
                                        required
                                    >
                                    <span style="position:absolute; left:13px; top:50%; transform:translateY(-50%); color:#9ca3af; pointer-events:none; display:flex;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                                    </span>
                                    <button type="button" class="show-pass-btn" onclick="togglePassword()" title="Tampilkan/sembunyikan password">
                                        <svg id="eyeIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p style="font-size:12px; color:#dc2626; margin-top:5px;">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remember & Forgot -->
                            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                                    <input type="checkbox" name="remember" id="remember" style="width:15px; height:15px; accent-color:#ea580c; cursor:pointer;" {{ old('remember') ? 'checked' : '' }}>
                                    <span style="font-size:13px; color:#6b7280; user-select:none;">Ingat saya</span>
                                </label>

                            </div>

                            <!-- Submit -->
                            <button type="submit" class="btn-primary">
                                Masuk ke Dashboard
                            </button>
                        </form>


                        @if(!empty($s['login_wa_number']))
                        <!-- WhatsApp CS Button -->
                        <div style="margin-top:14px; text-align:center;">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $s['login_wa_number']) }}"
                                target="_blank" rel="noopener noreferrer"
                                style="display:inline-flex; align-items:center; gap:8px; padding:9px 20px; background:#dcfce7; border:1.5px solid #86efac; border-radius:50px; text-decoration:none; transition:all 0.2s;"
                                onmouseover="this.style.background='#bbf7d0'; this.style.borderColor='#4ade80';"
                                onmouseout="this.style.background='#dcfce7'; this.style.borderColor='#86efac';">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="#16a34a"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M11.984 0C5.375 0 0 5.373 0 11.997c0 2.12.554 4.107 1.523 5.832L.03 23.997l6.312-1.654A11.956 11.956 0 0011.984 24c6.608 0 11.984-5.373 11.984-11.997 0-6.625-5.376-12.003-11.984-12.003z"/></svg>
                                <span style="font-size:12px; font-weight:700; color:#15803d;">Hubungi Customer Service</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Demo credentials: HANYA ditampilkan di environment non-production (local/staging) --}}
                @if(!app()->isProduction())
                <div class="fade-up fade-up-3" style="margin-top:16px; padding:14px 18px; background:#fff7ed; border:1px solid #fed7aa; border-radius:14px;">
                    <div style="display:flex; align-items:center; gap:7px; margin-bottom:10px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span style="font-size:11px; font-weight:800; color:#9a3412; text-transform:uppercase; letter-spacing:0.05em;">Akun Demo</span>
                    </div>
                    <div style="display:grid; gap:5px; font-size:12px; color:#78350f;">
                        <div style="display:flex; justify-content:space-between;">
                            <span style="font-weight:600; color:#92400e;">User</span>
                            <span style="font-family:monospace; letter-spacing:0.02em;">user@test.com / password</span>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <span style="font-weight:600; color:#92400e;">Marketing</span>
                            <span style="font-family:monospace; letter-spacing:0.02em;">marketing@test.com / password</span>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <span style="font-weight:600; color:#92400e;">Admin</span>
                            <span style="font-family:monospace; letter-spacing:0.02em;">admin@test.com / password</span>
                        </div>
                    </div>
                </div>
                @endif

                <p style="text-align:center; font-size:11px; color:#d1d5db; margin-top:20px;">&copy; 2026 {{ $s['site_name'] ?? 'GriyaOne' }}. Semua hak dilindungi.</p>
            </div>
        </div>
    </div>

    <script>
        // Show brand panel on md+ screens
        function handleResize() {
            const bp = document.getElementById('brandPanel');
            const ml = document.getElementById('mobileLogo');
            if (window.innerWidth >= 768) {
                bp.style.display = 'flex';
                if (ml) ml.style.display = 'none';
            } else {
                bp.style.display = 'none';
                if (ml) ml.style.display = 'block';
            }
        }
        handleResize();
        window.addEventListener('resize', handleResize);

        // Toggle password visibility
        function togglePassword() {
            const inp = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (inp.type === 'password') {
                inp.type = 'text';
                icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>';
            } else {
                inp.type = 'password';
                icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            }
        }
    </script>
</body>
</html>





