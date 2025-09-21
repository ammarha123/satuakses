@extends('layouts.app')

@section('title', 'Profil')

@section('content')
    <div class="container py-4">
        <h3 class="fw-bold mb-4">Profil</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold">Ganti Kata Sandi</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.password') }}">
                            @csrf @method('PATCH')
                            <div class="mb-3">
                                <label class="form-label">Kata Sandi Saat Ini</label>
                                <input type="password" name="current_password" class="form-control" required>
                                @error('current_password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kata Sandi Baru</label>
                                <input type="password" name="password" class="form-control" required>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <button class="btn btn-primary w-100">Simpan Password</button>
                        </form>
                    </div>
                </div>
                <div class="card shadow-sm mt-4">
                    <div class="card-header text-danger fw-semibold">Hapus Akun</div>
                    <div class="card-body">
                        <p class="small text-muted">Tindakan ini permanen dan tidak bisa dibatalkan.</p>
                        <form method="POST" action="{{ route('profile.destroy') }}">
                            @csrf @method('DELETE')
                            <div class="mb-2">
                                <input type="text" name="confirm" class="form-control"
                                    placeholder='Ketik "HAPUS" untuk konfirmasi'>
                                @error('confirm')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <button class="btn btn-outline-danger w-100">Hapus Akun</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold">Detail Profil</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf @method('PATCH')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                        class="form-control" required>
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                        class="form-control" required>
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">No. HP</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                        class="form-control">
                                    @error('phone')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="gender" class="form-select">
                                        <option value="" {{ old('gender', $user->gender) == '' ? 'selected' : '' }}>
                                            Pilih
                                        </option>
                                        <option value="Laki-laki"
                                            {{ old('gender', $user->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                        </option>
                                        <option value="Perempuan"
                                            {{ old('gender', $user->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                        </option>
                                    </select>
                                    @error('gender')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Provinsi</label>
                                    <input type="text" name="province" value="{{ old('province', $user->province) }}"
                                        class="form-control">
                                    @error('province')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Kota/Kab</label>
                                    <input type="text" name="city" value="{{ old('city', $user->city) }}"
                                        class="form-control">
                                    @error('city')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Bio / Ringkasan</label>
                                    <textarea name="bio" rows="4" class="form-control" placeholder="Ceritakan dirimu singkat...">{{ old('bio', $user->bio) }}</textarea>
                                    @error('bio')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <button class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- START: Work Experience Card --}}
        <div class="card shadow-sm mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="fw-semibold">Pengalaman Kerja</div>
                <div>
                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse"
                        data-bs-target="#addWorkCollapse" aria-expanded="false" aria-controls="addWorkCollapse">
                        <i class="bi bi-plus-lg"></i> Tambah Pengalaman
                    </button>
                </div>
            </div>

            <div class="card-body">
                {{-- List --}}
                <div class="mb-3">
                    @forelse ($user->workExperiences->sortByDesc('start_date') as $exp)
                        <div class="border rounded p-3 mb-3 bg-white d-flex gap-3 align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1 fw-semibold">{{ $exp->position }} <small class="text-muted">@
                                                {{ $exp->company }}</small></h6>
                                        <div>
                                            <small class="text-muted">
                                                {{ $exp->start_date->format('M Y') }}
                                                -
                                                {{ $exp->is_current ? 'Sekarang' : ($exp->end_date ? $exp->end_date->format('M Y') : '-') }}
                                            </small>
                                            <span class="badge bg-{{ $exp->is_current ? 'success' : 'secondary' }} ms-2">
                                                {{ $exp->is_current ? 'Aktif' : 'Selesai' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                       <a href="{{ route('work.edit', $exp) }}" class="btn btn-sm btn-outline-secondary mb-1">
    <i class="bi bi-pencil"></i> Edit
</a>


                                        <form method="POST" action="{{ route('work.destroy', $exp) }}"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Yakin ingin menghapus pengalaman ini?')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                @if ($exp->description)
                                    <p class="mt-2 mb-0 text-muted" style="white-space:pre-line;">{{ $exp->description }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada pengalaman kerja. Tambahkan pengalaman pertama kamu.</p>
                    @endforelse
                </div>

                {{-- Collapsible Add Form --}}
                <div class="collapse" id="addWorkCollapse">
                    <div class="card card-body mb-3">
                        <form method="POST" action="{{ route('work.store') }}" id="addWorkForm">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Perusahaan</label>
                                    <input type="text" name="company" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Posisi</label>
                                    <input type="text" name="position" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <input type="date" name="start_date" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Selesai</label>
                                    <input type="date" name="end_date" class="form-control" id="add_end_date">
                                    <input type="hidden" name="end_date" id="add_end_date_hidden" value="">
                                </div>

                                <div class="col-12">
                                    <input type="hidden" name="is_current" value="0">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_current" value="1"
                                            id="is_current_add">
                                        <label class="form-check-label" for="is_current_add">Masih bekerja di sini</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Deskripsi (opsional)</label>
                                    <textarea name="description" rows="3" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="mt-3 d-flex gap-2">
                                <button class="btn btn-primary">Simpan</button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse"
                                    data-bs-target="#addWorkCollapse">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Edit Modal --}}
        <div class="modal fade" id="editWorkModal" tabindex="-1" aria-labelledby="editWorkModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                {{-- route template with placeholder to be replaced by JS --}}
                <form method="POST" id="editWorkForm"
                    data-route-template="{{ route('work.update', ['experience' => 'ID_PLACEHOLDER']) }}">
                    @csrf
                    @method('PATCH')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Pengalaman Kerja</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Perusahaan</label>
                                <input type="text" name="company" id="edit_company" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Posisi</label>
                                <input type="text" name="position" id="edit_position" class="form-control" required>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <input type="date" name="start_date" id="edit_start" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Selesai</label>
                                    <input type="date" name="end_date" id="edit_end" class="form-control">
                                    <input type="hidden" name="end_date" id="edit_end_hidden" value="">
                                </div>
                            </div>

                            <div class="form-check mt-2">
                                <input type="hidden" name="is_current" value="0">
                                <input class="form-check-input" type="checkbox" name="is_current" value="1"
                                    id="edit_is_current">
                                <label class="form-check-label" for="edit_is_current">Masih bekerja di sini</label>
                            </div>

                            <div class="mb-3 mt-2">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" id="edit_description" rows="3" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
                            <button class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ----- ADD form handling -----
            const isCurrentAdd = document.getElementById('is_current_add');
            const addEnd = document.getElementById('add_end_date');
            const addEndHidden = document.getElementById('add_end_date_hidden');

            if (isCurrentAdd) {
                isCurrentAdd.addEventListener('change', function() {
                    if (this.checked) {
                        // make readonly and clear visible, keep hidden empty so server get null
                        addEnd.value = '';
                        addEnd.readOnly = true;
                        addEndHidden.value = '';
                    } else {
                        addEnd.readOnly = false;
                    }
                });

                // on submit sync visible -> hidden (if not current)
                const addForm = document.getElementById('addWorkForm');
                addForm.addEventListener('submit', function() {
                    addEndHidden.value = isCurrentAdd.checked ? '' : (addEnd.value || '');
                });
            }

            // ----- EDIT modal handling -----
            const editModal = document.getElementById('editWorkModal');
            if (editModal) {
                editModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const company = button.getAttribute('data-company') || '';
                    const position = button.getAttribute('data-position') || '';
                    const start = button.getAttribute('data-start') || '';
                    const end = button.getAttribute('data-end') || '';
                    const isCurrent = button.getAttribute('data-is_current') === '1';
                    const description = button.getAttribute('data-description') || '';

                    const form = document.getElementById('editWorkForm');
                    const template = form.dataset
                    .routeTemplate; // e.g. "/profile/work-experience/ID_PLACEHOLDER"
                    form.action = template.replace('ID_PLACEHOLDER', id);

                    // set form fields
                    document.getElementById('edit_company').value = company;
                    document.getElementById('edit_position').value = position;
                    document.getElementById('edit_start').value = start;
                    document.getElementById('edit_end').value = end;
                    document.getElementById('edit_description').value = description;
                    document.getElementById('edit_is_current').checked = isCurrent;

                    // initial readonly state & hidden sync
                    const editEnd = document.getElementById('edit_end');
                    const editEndHidden = document.getElementById('edit_end_hidden');
                    if (isCurrent) {
                        editEnd.value = '';
                        editEnd.readOnly = true;
                        editEndHidden.value = '';
                    } else {
                        editEnd.readOnly = false;
                        editEndHidden.value = editEnd.value || '';
                    }
                });

                // toggle behavior when user checks/unchecks in modal
                const editIsCurrent = document.getElementById('edit_is_current');
                editIsCurrent.addEventListener('change', function() {
                    const editEnd = document.getElementById('edit_end');
                    const editEndHidden = document.getElementById('edit_end_hidden');

                    if (this.checked) {
                        editEnd.value = '';
                        editEnd.readOnly = true;
                        editEndHidden.value = '';
                    } else {
                        editEnd.readOnly = false;
                        editEndHidden.value = editEnd.value || '';
                    }
                });

                // on modal form submit, ensure hidden end_date synced
                const editForm = document.getElementById('editWorkForm');
                editForm.addEventListener('submit', function() {
                    const editEnd = document.getElementById('edit_end');
                    const editEndHidden = document.getElementById('edit_end_hidden');
                    editEndHidden.value = document.getElementById('edit_is_current').checked ? '' : (editEnd
                        .value || '');
                });
            }
        });
    </script>
@endpush
