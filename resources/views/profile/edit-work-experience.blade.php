@extends('layouts.app')

@section('title', 'Edit Pengalaman Kerja')

@section('content')
<div class="container py-4">
    <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary mb-3">← Kembali</a>

    <div class="card shadow-sm">
        <div class="card-header fw-semibold">Edit Pengalaman Kerja</div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('work.update', $experience) }}">
                @csrf
                @method('PATCH')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Perusahaan</label>
                        <input type="text" name="company" class="form-control" value="{{ old('company', $experience->company) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Posisi</label>
                        <input type="text" name="position" class="form-control" value="{{ old('position', $experience->position) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', optional($experience->start_date)->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="edit_page_end" class="form-control" value="{{ old('end_date', optional($experience->end_date)->format('Y-m-d')) }}">
                        <input type="hidden" id="edit_page_end_hidden" name="end_date" value="{{ old('end_date', optional($experience->end_date)->format('Y-m-d')) }}">
                    </div>

                    <div class="col-12">
                        <input type="hidden" name="is_current" value="0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_current" value="1" id="edit_page_is_current" {{ old('is_current', $experience->is_current) ? 'checked' : '' }}>
                            <label class="form-check-label" for="edit_page_is_current">Masih bekerja di sini</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Deskripsi (opsional)</label>
                        <textarea name="description" rows="4" class="form-control">{{ old('description', $experience->description) }}</textarea>
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isCurrent = document.getElementById('edit_page_is_current');
    const endVisible = document.getElementById('edit_page_end');
    const endHidden = document.getElementById('edit_page_end_hidden');
    const form = endVisible.closest('form');
    form.addEventListener('submit', function() {
        endHidden.value = isCurrent.checked ? '' : (endVisible.value || '');
    });

    isCurrent.addEventListener('change', function() {
        if (this.checked) {
            endVisible.value = '';
            endVisible.readOnly = true;
            endHidden.value = '';
        } else {
            endVisible.readOnly = false;
        }
    });

    // initialise state
    if (isCurrent.checked) {
        endVisible.value = '';
        endVisible.readOnly = true;
    }
});
</script>
@endpush
