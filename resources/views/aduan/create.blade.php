<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Help Desk - Aduan</title>
    <!-- Contoh menggunakan Tailwind CSS / Bootstrap sesuai preferensi proyek -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Buat Aduan / Help Desk</h4>
                    </div>
                    <div class="card-body">
                        
                        <!-- Notifikasi Sukses -->
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Form Pengiriman Aduan -->
                        <form action="{{ route('aduan.store') }}" method="POST">
                            @csrf
                            
                            <!-- Jenis Aduan -->
                            <div class="mb-3">
                                <label for="jenis_aduan" class="form-label">Jenis Aduan</label>
                                <input type="text" name="jenis_aduan" id="jenis_aduan" class="form-control" placeholder="Contoh: Kendala Pembayaran / Akun" required>
                            </div>

                            <!-- Judul / Ringkasan Aduan -->
                            <div class="mb-3">
                                <label for="aduan" class="form-label">Judul Aduan</label>
                                <input type="text" name="aduan" id="aduan" class="form-control" placeholder="Ringkasan singkat kendala Anda" required>
                            </div>

                            <!-- Prioritas -->
                            <div class="mb-3">
                                <label for="prioritas" class="form-label">Prioritas</label>
                                <select name="prioritas" id="prioritas" class="form-select" required>
                                    <option value="" disabled selected>Pilih Prioritas</option>
                                    <option value="Low">Low (Rendah)</option>
                                    <option value="Middle">Middle (Sedang)</option>
                                    <option value="High">High (Tinggi)</option>
                                </select>
                            </div>

                            <!-- Teks / Detail Pesan -->
                            <div class="mb-3">
                                <label for="teks" class="form-label">Detail Pesan (Teks)</label>
                                <textarea name="teks" id="teks" class="form-control" rows="5" placeholder="Jelaskan kendala Anda secara lengkap di sini..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Kirim Aduan</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>