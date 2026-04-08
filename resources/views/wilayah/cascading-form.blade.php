@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-4">Form Cascading Select Wilayah Indonesia</h2>
            <p class="text-muted">Studi Kasus: Cascading Select dengan Axios</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Pilih Wilayah Administrasi Indonesia</h5>
                </div>
                <div class="card-body">
                    <form id="wilayahForm">
                        <div class="mb-3">
                            <label for="provinsi" class="form-label">Provinsi <span class="text-danger">*</span></label>
                            <select class="form-select" id="provinsi" name="provinsi" required>
                                <option value="">Pilih Provinsi</option>
                            </select>
                            <div class="invalid-feedback" id="provinsi-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="kota" class="form-label">Kota <span class="text-danger">*</span></label>
                            <select class="form-select" id="kota" name="kota" required disabled>
                                <option value="">Pilih Kota</option>
                            </select>
                            <div class="invalid-feedback" id="kota-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="kecamatan" class="form-label">Kecamatan <span class="text-danger">*</span></label>
                            <select class="form-select" id="kecamatan" name="kecamatan" required disabled>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                            <div class="invalid-feedback" id="kecamatan-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="kelurahan" class="form-label">Kelurahan <span class="text-danger">*</span></label>
                            <select class="form-select" id="kelurahan" name="kelurahan" required disabled>
                                <option value="">Pilih Kelurahan</option>
                            </select>
                            <div class="invalid-feedback" id="kelurahan-error"></div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Simpan</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </form>

                    <!-- Display Selected Data -->
                    <div id="selectedDataDisplay" style="display: none;" class="mt-4 alert alert-info">
                        <h6>Data yang dipilih:</h6>
                        <table class="table table-sm mb-0">
                            <tr>
                                <td><strong>Provinsi:</strong></td>
                                <td id="displayProvinsi">-</td>
                            </tr>
                            <tr>
                                <td><strong>Kota:</strong></td>
                                <td id="displayKota">-</td>
                            </tr>
                            <tr>
                                <td><strong>Kecamatan:</strong></td>
                                <td id="displayKecamatan">-</td>
                            </tr>
                            <tr>
                                <td><strong>Kelurahan:</strong></td>
                                <td id="displayKelurahan">-</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Axios Script -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Store untuk menyimpan nama untuk display
    let provinsiNames = {};
    let kotaNames = {};
    let kecamatanNames = {};
    let kelurahanNames = {};

    // Load Provinsi on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadProvinsi();
    });

    /**
     * Load semua Provinsi
     */
    function loadProvinsi() {
        const provinsiSelect = document.getElementById('provinsi');
        
        axios.get('{{ route("wilayah.api.provinsi") }}')
            .then(response => {
                if (response.data.data && Array.isArray(response.data.data)) {
                    provinsiSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                    
                    // Store nama untuk display
                    response.data.data.forEach(item => {
                        provinsiNames[item.id] = item.nama_provinsi;
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.nama_provinsi;
                        provinsiSelect.appendChild(option);
                    });
                } else {
                    showAlert('Gagal memuat data Provinsi', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error: ' + error.message, 'danger');
            });
    }

    /**
     * Event: Ketika Provinsi berubah
     * - Load Kota berdasarkan Provinsi
     * - Reset Kecamatan dan Kelurahan
     */
    document.getElementById('provinsi').addEventListener('change', function(e) {
        const provinsiId = this.value;
        const kotaSelect = document.getElementById('kota');
        const kecamatanSelect = document.getElementById('kecamatan');
        const kelurahanSelect = document.getElementById('kelurahan');

        if (!provinsiId) {
            kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
            kotaSelect.disabled = true;
            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            kecamatanSelect.disabled = true;
            kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            kelurahanSelect.disabled = true;
            return;
        }

        // Load Kota dari backend API
        axios.get(`{{ route('wilayah.api.kota') }}?id_provinsi=${provinsiId}`)
            .then(response => {
                if (response.data.data && Array.isArray(response.data.data)) {
                    kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
                    kotaSelect.disabled = false;
                    
                    // Store nama untuk display
                    response.data.data.forEach(item => {
                        kotaNames[item.id] = item.nama_kota;
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.nama_kota;
                        kotaSelect.appendChild(option);
                    });
                } else {
                    showAlert('Gagal memuat data Kota', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error: ' + error.message, 'danger');
            });

        // Reset Kecamatan dan Kelurahan (sesuai poin d)
        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        kecamatanSelect.disabled = true;
        kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
        kelurahanSelect.disabled = true;
    });

    /**
     * Event: Ketika Kota berubah
     * - Load Kecamatan berdasarkan Kota
     * - Reset Kelurahan (sesuai poin e)
     */
    document.getElementById('kota').addEventListener('change', function(e) {
        const kotaId = this.value;
        const kecamatanSelect = document.getElementById('kecamatan');
        const kelurahanSelect = document.getElementById('kelurahan');

        if (!kotaId) {
            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            kecamatanSelect.disabled = true;
            kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            kelurahanSelect.disabled = true;
            return;
        }

        // Load Kecamatan dari backend API
        axios.get(`{{ route('wilayah.api.kecamatan') }}?id_kota=${kotaId}`)
            .then(response => {
                if (response.data.data && Array.isArray(response.data.data)) {
                    kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                    kecamatanSelect.disabled = false;
                    
                    // Store nama untuk display
                    response.data.data.forEach(item => {
                        kecamatanNames[item.id] = item.nama_kecamatan;
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.nama_kecamatan;
                        kecamatanSelect.appendChild(option);
                    });
                } else {
                    showAlert('Gagal memuat data Kecamatan', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error: ' + error.message, 'danger');
            });

        // Reset Kelurahan (sesuai poin e)
        kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
        kelurahanSelect.disabled = true;
    });

    /**
     * Event: Ketika Kecamatan berubah
     * - Load Kelurahan berdasarkan Kecamatan
     */
    document.getElementById('kecamatan').addEventListener('change', function(e) {
        const kecamatanId = this.value;
        const kelurahanSelect = document.getElementById('kelurahan');

        if (!kecamatanId) {
            kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            kelurahanSelect.disabled = true;
            return;
        }

        // Load Kelurahan dari backend API
        axios.get(`{{ route('wilayah.api.kelurahan') }}?id_kecamatan=${kecamatanId}`)
            .then(response => {
                if (response.data.data && Array.isArray(response.data.data)) {
                    kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                    kelurahanSelect.disabled = false;
                    
                    // Store nama untuk display
                    response.data.data.forEach(item => {
                        kelurahanNames[item.id] = item.nama_kelurahan;
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.nama_kelurahan;
                        kelurahanSelect.appendChild(option);
                    });
                } else {
                    showAlert('Gagal memuat data Kelurahan', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error: ' + error.message, 'danger');
            });
    });

    /**
     * Event: Submit Form
     */
    document.getElementById('wilayahForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const provinsiId = document.getElementById('provinsi').value;
        const kotaId = document.getElementById('kota').value;
        const kecamatanId = document.getElementById('kecamatan').value;
        const kelurahanId = document.getElementById('kelurahan').value;

        if (!provinsiId || !kotaId || !kecamatanId || !kelurahanId) {
            showAlert('Silahkan lengkapi semua pilihan wilayah', 'warning');
            return;
        }

        // Display selected data
        document.getElementById('displayProvinsi').textContent = provinsiNames[provinsiId];
        document.getElementById('displayKota').textContent = kotaNames[kotaId];
        document.getElementById('displayKecamatan').textContent = kecamatanNames[kecamatanId];
        document.getElementById('displayKelurahan').textContent = kelurahanNames[kelurahanId];
        document.getElementById('selectedDataDisplay').style.display = 'block';

        showAlert('Data wilayah berhasil disimpan!', 'success');
    });

    /**
     * Function: Show Alert
     */
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        const container = document.querySelector('.container');
        container.insertBefore(alertDiv, container.firstChild);

        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alertDiv);
            bsAlert.close();
        }, 5000);
    }
</script>
@endsection
