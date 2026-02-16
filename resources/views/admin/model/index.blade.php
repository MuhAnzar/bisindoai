@extends('layouts.admin')

@section('judul', 'Pengaturan Model AI')
@section('deskripsi', 'Upload model .keras dan file label .json untuk deteksi bahasa isyarat.')

@section('konten')
<div class="card">
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.model.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-2">
            <!-- Upload Model -->
            <div>
                <label class="block mb-2 font-bold text-gray-700">File Model (.keras)</label>
                <input type="file" name="model_file" accept=".keras,.h5" class="w-full p-2 border rounded">
                <p class="text-sm text-gray-500 mt-1">
                    Status: 
                    @if($modelExists)
                        <span class="text-green-600 font-bold">✓ Terinstall (models/best_abjad.keras)</span>
                    @else
                        <span class="text-red-600 font-bold">✗ Belum ada</span>
                    @endif
                </p>
            </div>

            <!-- Upload Label -->
            <div>
                <label class="block mb-2 font-bold text-gray-700">File Label (.json)</label>
                <input type="file" name="labels_file" accept=".json" class="w-full p-2 border rounded">
                <p class="text-sm text-gray-500 mt-1">
                    Status: 
                    @if($labelsExists)
                        <span class="text-green-600 font-bold">✓ Terinstall (models/class_names.json)</span>
                    @else
                        <span class="text-red-600 font-bold">✗ Belum ada</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="btn btn-primary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                Upload & Simpan
            </button>
        </div>
    </form>

    <div class="mt-8 p-4 bg-blue-50 text-blue-800 rounded-lg">
        <h4 class="font-bold mb-2">Instruksi:</h4>
        <ul class="list-disc list-inside text-sm">
            <li>Pastikan nama file model anda berformat <code>.keras</code>. Sistem akan menyimpannya sebagai <code>best_abjad.keras</code>.</li>
            <li>File label harus berupa JSON list (array) nama kelas, contoh: <code>["A", "B", "C", ...]</code>. Sistem akan menyimpannya sebagai <code>class_names.json</code>.</li>
            <li>File akan disimpan di folder <code>storage/app/public/models/</code> dan dapat diakses melalui <code>public/storage/models/</code>.</li>
        </ul>
    </div>
</div>
@endsection
