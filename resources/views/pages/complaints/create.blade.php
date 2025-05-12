@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-exclamation-circle me-2"></i>Buat Pengaduan Baru</h3>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Terjadi kesalahan!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('customer.complaints.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-1">
                            <label for="description" class="form-label fw-bold">Judul Pengaduan</label>
                        </div>

                        <div class="mb-4">
                            <input type="text" name="subject" id="subject"
                                class="form-control form-control-lg @error('subject') is-invalid @enderror"
                                style="width: 100%; border: 1px solid #000; padding: 5px;"
                                placeholder="Masukkan judul pengaduan"
                                value="{{ old('subject') }}" required>
                            @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-1">
                            <label for="description" class="form-label fw-bold">Deskripsi Lengkap</label>
                        </div>

                        <div class="mb-4">
                            <textarea name="description" id="description"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Jelaskan pengaduan Anda secara detail"
                                rows="6" style="width: 100%; border: 1px solid #000; padding: 5px;" required>{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Bukti Pendukung</label>
                            <div class="file-upload-area border border-2 border-dashed rounded-3 p-5 text-center" id="fileUploadArea">
                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                <h5 class="mb-2">Seret dan lepas file di sini atau klik untuk memilih</h5>
                                <p class="text-muted mb-0">Format yang didukung: JPG dan PNG (Maks. 5MB)</p>
                                <input type="file" name="evidence_file" id="fileInput"
                                    class="d-none" required accept=".jpg,.jpeg,.png,.pdf">
                                <div id="fileName" class="mt-3 fw-bold text-primary"></div>
                                @error('evidence_file')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <!-- <button type="reset" class="btn btn-outline-secondary me-3">
                                <i class="fas fa-undo me-1"></i> Reset
                            </button> -->
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Kirim Pengaduan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .file-upload-area {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .file-upload-area:hover {
        border-color: #0d6efd !important;
        background-color: rgba(13, 110, 253, 0.05);
    }

    .card-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileUploadArea = document.getElementById('fileUploadArea');
        const fileInput = document.getElementById('fileInput');
        const fileName = document.getElementById('fileName');

        fileUploadArea.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileName.textContent = this.files[0].name;
                fileUploadArea.style.borderColor = '#198754';
                fileUploadArea.style.backgroundColor = 'rgba(25, 135, 84, 0.05)';
            }
        });

        fileUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUploadArea.style.borderColor = '#0d6efd';
            fileUploadArea.style.backgroundColor = 'rgba(13, 110, 253, 0.1)';
        });

        fileUploadArea.addEventListener('dragleave', () => {
            fileUploadArea.style.borderColor = fileInput.files.length > 0 ? '#198754' : '#dee2e6';
            fileUploadArea.style.backgroundColor = fileInput.files.length > 0 ? 'rgba(25, 135, 84, 0.05)' : 'transparent';
        });

        fileUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                fileName.textContent = e.dataTransfer.files[0].name;
                fileUploadArea.style.borderColor = '#198754';
                fileUploadArea.style.backgroundColor = 'rgba(25, 135, 84, 0.05)';
            }
        });
    });
</script>
@endsection