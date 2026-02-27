@extends('admin.layouts.app')

@section('title', 'Tambah Informasi Baru - GriyaOne')
@section('role', 'Tambah Informasi')

@section('content')
    <!-- Header -->
    <div class="mb-8 fade-in">
        <a href="{{ route('informasi.index') }}" class="text-red-600 hover:text-red-700 font-semibold text-sm mb-4 inline-flex items-center gap-1">
            ← Kembali
        </a>
        <h2 class="text-3xl font-bold text-gray-900">Tambah Informasi Baru</h2>
        <p class="text-gray-600 mt-2">Buat informasi terbaru yang akan ditampilkan kepada pengguna</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md p-8 fade-in">
        <form action="{{ route('informasi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul Informasi <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Masukkan judul informasi..." class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Photo -->
            <div>
                <label for="photo" class="block text-sm font-semibold text-gray-700 mb-2">Foto</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-red-500 hover:bg-red-50 transition" id="photoDropZone">
                    <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png,.gif,.webp,image/*" class="hidden">
                    <div id="photoDropContent">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-700 font-medium">Klik atau drag foto di sini</p>
                        <p class="text-gray-500 text-xs mt-1">PNG, JPG, GIF (Maks. 5MB)</p>
                    </div>
                    <img id="photoPreview" src="" alt="Preview" class="hidden w-full h-48 object-cover rounded-lg">
                </div>
                <div id="photoActions" class="hidden mt-3 flex gap-2">
                    <button type="button" onclick="clearPhoto()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">Ganti Foto</button>
                    <button type="button" onclick="removePhoto()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">Hapus</button>
                </div>
                <p id="photoError" class="text-red-600 text-xs mt-2 hidden"></p>
                @error('photo')
                    <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                <div class="flex gap-2">
                    <select id="category_id" name="category_id" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition @error('category_id') border-red-500 @enderror">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" onclick="openCategoryModal()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition h-full">+ Kategori Baru</button>
                    <button type="button" id="deleteCategoryBtn" onclick="deleteSelectedCategory()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition h-full hidden" title="Hapus kategori yang dipilih">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
                @error('category_id')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Published Date -->
            <div>
                <label for="published_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Publikasi <span class="text-red-500">*</span></label>
                <input type="datetime-local" id="published_date" name="published_date" value="{{ old('published_date', now()->format('Y-m-d\TH:i')) }}" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 bg-gray-100 cursor-not-allowed focus:outline-none transition @error('published_date') border-red-500 @enderror">
                <p class="text-gray-500 text-xs mt-2">Waktu otomatis saat ini (tidak dapat diubah)</p>
                @error('published_date')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">Konten</label>
                <textarea id="content" name="content" rows="8" placeholder="Masukkan konten informasi (opsional)..." class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                <div class="space-y-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="status" value="active" {{ old('status', 'active') == 'active' ? 'checked' : '' }} class="w-4 h-4 text-red-600">
                        <span class="text-sm text-gray-700">Aktif - Tampilkan ke pengguna</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="status" value="archived" {{ old('status') == 'archived' ? 'checked' : '' }} class="w-4 h-4 text-red-600">
                        <span class="text-sm text-gray-700">Arsip - Sembunyikan dari pengguna</span>
                    </label>
                </div>
                @error('status')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('informasi.index') }}" class="flex-1 text-center px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" id="submitBtn" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold py-2 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    Simpan Informasi
                </button>
            </div>
        </form>
    </div>

<script>
// Form submission validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');

    if (form) {
        form.addEventListener('submit', function(e) {
            const titleInput = document.getElementById('title').value.trim();
            const categoryInput = document.getElementById('category_id').value;
            const publishedDateInput = document.getElementById('published_date').value;

            // Validate required fields
            if (!titleInput) {
                e.preventDefault();
                showNotification('Judul tidak boleh kosong', 'error');
                return false;
            }

            if (!categoryInput) {
                e.preventDefault();
                showNotification('Kategori harus dipilih', 'error');
                return false;
            }

            if (!publishedDateInput) {
                e.preventDefault();
                showNotification('Tanggal publikasi harus diisi', 'error');
                return false;
            }

            // Form is valid, disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Menyimpan...';
        });
    }
});
const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB in bytes

function validateFile(file) {
    const errorEl = document.getElementById('photoError');
    const validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    const fileExtension = file.name.split('.').pop().toLowerCase();

    if (!validExtensions.includes(fileExtension)) {
        errorEl.textContent = 'Format file tidak didukung. Gunakan JPG, PNG, GIF, atau WebP';
        errorEl.classList.remove('hidden');
        return false;
    }

    if (file.size > MAX_FILE_SIZE) {
        errorEl.textContent = `Ukuran file terlalu besar. Maksimal 5MB, file Anda ${(file.size / 1024 / 1024).toFixed(2)}MB`;
        errorEl.classList.remove('hidden');
        return false;
    }

    errorEl.classList.add('hidden');
    return true;
}

function previewPhoto(file) {
    if (!validateFile(file)) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('photoPreview');
        const dropContent = document.getElementById('photoDropContent');
        const photoActions = document.getElementById('photoActions');

        preview.src = e.target.result;
        preview.classList.remove('hidden');
        dropContent.classList.add('hidden');
        photoActions.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}

function clearPhoto() {
    document.getElementById('photo').value = '';
    document.getElementById('photoPreview').classList.add('hidden');
    document.getElementById('photoDropContent').classList.remove('hidden');
    document.getElementById('photoActions').classList.add('hidden');
    document.getElementById('photoError').classList.add('hidden');
}

function removePhoto() {
    clearPhoto();
}

document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('photo');
    const dropZone = document.getElementById('photoDropZone');

    // Click to select file
    dropZone.addEventListener('click', () => photoInput.click());

    // File input change
    photoInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            previewPhoto(e.target.files[0]);
        }
    });

    // Drag and drop functionality
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.add('border-red-500', 'bg-red-50');
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('border-red-500', 'bg-red-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('border-red-500', 'bg-red-50');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            // Set file directly to input
            photoInput.files = files;
            previewPhoto(files[0]);
        }
    });
});

function openCategoryModal() {
    document.getElementById('categoryModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.add('hidden');
    document.body.style.overflow = '';
    document.getElementById('categoryForm').reset();
}

// Toggle delete button visibility based on category selection
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const deleteBtn = document.getElementById('deleteCategoryBtn');

    function updateDeleteButtonVisibility() {
        if (categorySelect.value) {
            deleteBtn.classList.remove('hidden');
        } else {
            deleteBtn.classList.add('hidden');
        }
    }

    categorySelect.addEventListener('change', updateDeleteButtonVisibility);
    updateDeleteButtonVisibility();
});

// Event listeners will be attached at the end of the script

function deleteSelectedCategory() {
    const categorySelect = document.getElementById('category_id');
    const categoryId = categorySelect.value;

    if (!categoryId) {
        showNotification('Pilih kategori terlebih dahulu', 'error');
        return;
    }

    const selectedOption = categorySelect.querySelector(`option[value="${categoryId}"]`);
    const categoryName = selectedOption.textContent;

    // Show confirmation modal instead of alert
    showDeleteConfirmationModal(categoryId, categoryName, selectedOption);
}

function showDeleteConfirmationModal(categoryId, categoryName, selectedOption) {
    const modal = document.getElementById('deleteConfirmationModal');
    const categoryNameDisplay = document.getElementById('deleteConfirmationCategoryName');
    const confirmDeleteBtn = document.getElementById('confirmDeleteCategoryBtn');

    categoryNameDisplay.textContent = categoryName;

    // Update the confirm button click handler
    confirmDeleteBtn.onclick = function() {
        performDeleteCategory(categoryId, selectedOption);
        closeDeleteConfirmationModal();
    };

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteConfirmationModal() {
    document.getElementById('deleteConfirmationModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function performDeleteCategory(categoryId, selectedOption) {
    fetch(`/informasi-categories/${categoryId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value ||
                           document.querySelector('meta[name="csrf-token"]')?.content,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the option from select
            selectedOption.remove();
            document.getElementById('category_id').value = '';
            document.getElementById('deleteCategoryBtn').classList.add('hidden');
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat menghapus kategori', 'error');
    });
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    const bgColor = type === 'error' ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200';
    const textColor = type === 'error' ? 'text-red-800' : 'text-green-800';
    notification.className = `fixed top-4 right-4 ${bgColor} border rounded-lg p-4 ${textColor} font-medium z-50`;
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 5000);
}

document.getElementById('categoryModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeCategoryModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeCategoryModal();
});

// Attach categoryForm submit listener after DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    const categoryForm = document.getElementById('categoryForm');
    if (categoryForm) {
        categoryForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const categoryName = document.getElementById('categoryName').value;
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Menambahkan...';

            try {
                // Get CSRF token from the modal form itself
                const csrfToken = document.querySelector('#categoryForm input[name="_token"]')?.value ||
                                 document.querySelector('meta[name="csrf-token"]')?.content ||
                                 document.querySelector('input[name="_token"]')?.value;

                if (!csrfToken) {
                    throw new Error('CSRF token tidak ditemukan');
                }

                console.log('Mengirim kategori:', categoryName);
                console.log('CSRF Token:', csrfToken.substring(0, 10) + '...');

                const response = await fetch('{{ route("informasi.storeCategory") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ name: categoryName }),
                });

                console.log('Response status:', response.status);
                const data = await response.json();
                console.log('Response data:', data);

                if (!response.ok) {
                    throw new Error(data.message || `HTTP error! status: ${response.status}`);
                }

                if (data.success) {
                    const select = document.getElementById('category_id');
                    const option = document.createElement('option');
                    option.value = data.category.id;
                    option.textContent = data.category.name;
                    option.selected = true;
                    select.appendChild(option);

                    closeCategoryModal();
                    showNotification('Kategori berhasil ditambahkan!');
                } else {
                    throw new Error(data.message || 'Gagal menambahkan kategori');
                }
            } catch (error) {
                console.error('Error detail:', error);
                showNotification('Error: ' + error.message, 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }
});
</script>

<!-- Category Modal -->
<div id="categoryModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">+ Tambah Kategori Baru</h2>
            <button onclick="closeCategoryModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="categoryForm" class="p-6 space-y-4">
            @csrf
            <div>
                <label for="categoryName" class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori</label>
                <input type="text" id="categoryName" required placeholder="Masukkan nama kategori..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeCategoryModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">Tambah</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmationModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-900">Hapus Kategori</h2>
            <button onclick="closeDeleteConfirmationModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-700">Apakah Anda yakin ingin menghapus kategori:</p>
                    <p class="text-sm font-semibold text-gray-900" id="deleteConfirmationCategoryName"></p>
                </div>
            </div>
            <p class="text-xs text-gray-500 mb-6">Jika kategori ini digunakan oleh informasi lain, penghapusan akan ditolak.</p>
        </div>
        <div class="flex gap-3 p-6 border-t border-gray-200">
            <button type="button" onclick="closeDeleteConfirmationModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">Batal</button>
            <button type="button" id="confirmDeleteCategoryBtn" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">Hapus</button>
        </div>
    </div>
</div>

<!-- Close modal when clicking outside -->
<script>
document.getElementById('deleteConfirmationModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeDeleteConfirmationModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteConfirmationModal();
    }
});

// Auto-update published date and time to current real-time
document.addEventListener('DOMContentLoaded', function() {
    const publishedDateInput = document.getElementById('published_date');

    function updatePublishedDate() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');

        const datetimeValue = `${year}-${month}-${day}T${hours}:${minutes}`;
        publishedDateInput.value = datetimeValue;
    }

    // Set initial value
    updatePublishedDate();

    // Update every second for real-time display
    setInterval(updatePublishedDate, 1000);
});
</script>
@endsection


