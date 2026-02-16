@extends('komponen.tata_letak')

@section('judul', 'Pengaturan Profil')

@section('konten')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden mt-10">
        <!-- Header -->
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h1 class="text-2xl font-bold text-gray-800">Informasi Pribadi</h1>
            @if(session('sukses'))
                <span class="text-green-600 font-medium flex items-center gap-2 bg-green-50 px-3 py-1 rounded-full text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('sukses') }}
                </span>
            @endif
        </div>

        <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            @method('PATCH')

            <!-- Profile Photo Section -->
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-8 mb-10">
                <div class="relative group">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg bg-gray-100 ring-2 ring-gray-100">
                        <div id="image-preview-container" class="w-full h-full">
                            @if($user->foto_profil)
                                <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="{{ $user->nama }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400">
                                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Upload Button Overlay -->
                    <label for="foto_profil" class="absolute bottom-1 right-1 bg-gray-900 text-white p-2 rounded-full cursor-pointer hover:bg-gray-700 transition shadow-md hover:scale-110 duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </label>
                    <input type="file" name="foto_profil" id="foto_profil" class="hidden" accept="image/*" onchange="previewImage(this)">
                </div>

                <div class="text-center sm:text-left pt-2">
                    <h3 class="text-xl font-bold text-gray-900">{{ $user->nama }}</h3>
                    <p class="text-gray-500 font-medium">{{ $user->email }}</p>
                    <p class="text-xs text-gray-400 mt-2 bg-gray-100 inline-block px-2 py-1 rounded">Format: JPG, PNG, GIF (Max 2MB)</p>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Nama -->
                <div class="space-y-2">
                    <label for="nama" class="text-sm font-semibold text-gray-700">Nama Depan / Lengkap</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $user->nama) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition @error('nama') border-red-500 @enderror">
                    @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="text-sm font-semibold text-gray-700">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition @error('email') border-red-500 @enderror">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2 pt-4 pb-2">
                    <hr class="border-gray-100">
                </div>

                <!-- Password Lama -->
                <div class="space-y-2">
                    <label for="password_lama" class="text-sm font-semibold text-gray-700">Password Saat Ini <span class="text-gray-400 font-normal italic ml-1">(Kosongkan jika tidak ubah)</span></label>
                    <input type="password" name="password_lama" id="password_lama" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition @error('password_lama') border-red-500 @enderror" placeholder="••••••••">
                    @error('password_lama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Spacer -->
                <div class="hidden md:block"></div>

                <!-- Password Baru -->
                <div class="space-y-2">
                    <label for="password_baru" class="text-sm font-semibold text-gray-700">Password Baru</label>
                    <input type="password" name="password_baru" id="password_baru" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition @error('password_baru') border-red-500 @enderror" placeholder="••••••••">
                    @error('password_baru') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            
                <!-- Konfirmasi Password -->
                <div class="space-y-2">
                     <label for="password_baru_confirmation" class="text-sm font-semibold text-gray-700">Ulangi Password Baru</label>
                     <input type="password" name="password_baru_confirmation" id="password_baru_confirmation" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition" placeholder="••••••••">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end gap-4">
                <a href="{{ route('halaman-utama') }}" class="px-6 py-3 text-gray-500 hover:text-gray-900 font-medium transition flex items-center gap-2">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 bg-teal-600 text-white rounded-xl font-bold shadow-lg shadow-teal-600/20 hover:bg-teal-700 hover:shadow-teal-600/30 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                    <span>Simpan Perubahan</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Delete Account Section -->
    <div class="mt-8 bg-white rounded-2xl p-8 border border-gray-200/50 shadow-sm opacity-80 hover:opacity-100 transition">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Zona Bahaya</h3>
                <p class="text-gray-500 text-sm">Menghapus akun akan menghilangkan semua data progres latihan Anda secara permanen.</p>
            </div>
            <button type="button" class="text-red-600 font-semibold border border-red-200 bg-red-50 hover:bg-red-100 hover:border-red-300 px-6 py-2.5 rounded-xl transition text-sm">
                Hapus Akun Saya
            </button>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var container = document.getElementById('image-preview-container');
            container.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover animate-fade-in">';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
