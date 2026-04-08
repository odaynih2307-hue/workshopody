// Form Kota HTML Table - AJAX Implementation
document.addEventListener('DOMContentLoaded', function() {
    // Initialize
    loadKotaData();
    setupEventListeners();
});

// Load Kota Data
function loadKotaData() {
    axios.get('/api/kota/all')
        .then(response => {
            const tbody = document.getElementById('tbody-kota-html');
            tbody.innerHTML = '';
            
            if (response.data.status && response.data.data.length > 0) {
                response.data.data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.id}</td>
                        <td>${item.nama_kota}</td>
                        <td>${item.provinsi}</td>
                    `;
                    row.style.cursor = 'pointer';
                    row.addEventListener('click', function() {
                        openModalEditHtml(item.id, item.nama_kota, item.provinsi);
                    });
                    tbody.appendChild(row);
                });
            } else {
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="3" class="text-center text-muted">Data tidak ditemukan</td>';
                tbody.appendChild(row);
            }
        })
        .catch(error => {
            console.error('Error loading data:', error);
            showAlert('Error', 'Gagal memuat data', 'error');
        });
}

// Setup Event Listeners
function setupEventListeners() {
    // Submit Form Button
    const btnSubmit = document.getElementById('btn-submit-html');
    if (btnSubmit) {
        btnSubmit.addEventListener('click', submitFormHtml);
    }

    // Form Enter Key
    const formHtml = document.getElementById('form-kota-html');
    if (formHtml) {
        formHtml.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitFormHtml();
            }
        });
    }

    // Modal Delete Button
    const btnHapus = document.getElementById('btn-hapus-html');
    if (btnHapus) {
        btnHapus.addEventListener('click', deleteKotaHtml);
    }

    // Modal Save Button
    const btnSimpan = document.getElementById('btn-simpan-html');
    if (btnSimpan) {
        btnSimpan.addEventListener('click', updateKotaHtml);
    }

    // Modal Close Button (Clear Form)
    const modalHtml = document.getElementById('modal-kota-html');
    if (modalHtml) {
        modalHtml.addEventListener('hidden.bs.modal', function() {
            document.getElementById('form-modal-html').reset();
        });
    }
}

// Submit Form - Add New Kota
function submitFormHtml() {
    const namaKota = document.getElementById('nama_kota_html').value.trim();
    const provinsi = document.getElementById('provinsi_kota_html').value.trim();

    if (!namaKota || !provinsi) {
        showAlert('Validation', 'Semua field harus diisi', 'warning');
        return;
    }

    // Show loading state
    const btnSubmitText = document.getElementById('btn-submit-html-text');
    const btnSubmitSpinner = document.getElementById('btn-submit-html-spinner');
    const btnSubmit = document.getElementById('btn-submit-html');
    
    btnSubmit.disabled = true;
    btnSubmitText.classList.add('d-none');
    btnSubmitSpinner.classList.remove('d-none');

    axios.post('/api/kota/store', {
        nama_kota: namaKota,
        provinsi: provinsi
    })
    .then(response => {
        if (response.data.status) {
            showAlert('Success', response.data.message, 'success');
            document.getElementById('form-kota-html').reset();
            loadKotaData();
        }
    })
    .catch(error => {
        if (error.response && error.response.data.errors) {
            const errors = error.response.data.errors;
            let errorMsg = '';
            for (let field in errors) {
                errorMsg += errors[field][0] + '\n';
            }
            showAlert('Validation Error', errorMsg, 'error');
        } else {
            showAlert('Error', 'Gagal menambahkan data', 'error');
        }
        console.error('Error:', error);
    })
    .finally(() => {
        btnSubmit.disabled = false;
        btnSubmitText.classList.remove('d-none');
        btnSubmitSpinner.classList.add('d-none');
    });
}

// Open Modal Edit
function openModalEditHtml(id, nama, provinsi) {
    document.getElementById('modal-id-html').value = id;
    document.getElementById('modal-nama-html').value = nama;
    document.getElementById('modal-provinsi-html').value = provinsi;
    
    const modal = new bootstrap.Modal(document.getElementById('modal-kota-html'));
    modal.show();
}

// Update Kota
function updateKotaHtml() {
    const id = document.getElementById('modal-id-html').value;
    const nama = document.getElementById('modal-nama-html').value.trim();
    const provinsi = document.getElementById('modal-provinsi-html').value.trim();

    if (!nama || !provinsi) {
        showAlert('Validation', 'Semua field harus diisi', 'warning');
        return;
    }

    const btnSimpan = document.getElementById('btn-simpan-html');
    const originalText = btnSimpan.innerHTML;
    btnSimpan.disabled = true;
    btnSimpan.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';

    axios.put(`/api/kota/update/${id}`, {
        nama_kota: nama,
        provinsi: provinsi
    })
    .then(response => {
        if (response.data.status) {
            showAlert('Success', response.data.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('modal-kota-html')).hide();
            loadKotaData();
        }
    })
    .catch(error => {
        if (error.response && error.response.data.errors) {
            const errors = error.response.data.errors;
            let errorMsg = '';
            for (let field in errors) {
                errorMsg += errors[field][0] + '\n';
            }
            showAlert('Validation Error', errorMsg, 'error');
        } else {
            showAlert('Error', error.response?.data?.message || 'Gagal mengupdate data', 'error');
        }
        console.error('Error:', error);
    })
    .finally(() => {
        btnSimpan.disabled = false;
        btnSimpan.innerHTML = originalText;
    });
}

// Delete Kota
function deleteKotaHtml() {
    const id = document.getElementById('modal-id-html').value;
    
    if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        return;
    }

    const btnHapus = document.getElementById('btn-hapus-html');
    const originalText = btnHapus.innerHTML;
    btnHapus.disabled = true;
    btnHapus.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...';

    axios.delete(`/api/kota/delete/${id}`)
    .then(response => {
        if (response.data.status) {
            showAlert('Success', response.data.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('modal-kota-html')).hide();
            loadKotaData();
        }
    })
    .catch(error => {
        showAlert('Error', error.response?.data?.message || 'Gagal menghapus data', 'error');
        console.error('Error:', error);
    })
    .finally(() => {
        btnHapus.disabled = false;
        btnHapus.innerHTML = originalText;
    });
}

// Show Alert Message
function showAlert(title, message, type) {
    // Using SweetAlert2 if available, otherwise alert
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: title,
            text: message,
            icon: type,
            confirmButtonText: 'OK'
        });
    } else {
        alert(`${title}\n${message}`);
    }
}
