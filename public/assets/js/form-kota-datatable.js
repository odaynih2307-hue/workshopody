// Form Kota DataTables - AJAX Implementation
let kotaTable;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    initDataTable();
    setupEventListeners();
});

// Initialize DataTable
function initDataTable() {
    kotaTable = $('#tabel-kota-dt').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": {
            "url": "/api/kota/all",
            "dataSrc": function(json) {
                if (json.status && json.data) {
                    return json.data;
                }
                return [];
            },
            "error": function(xhr, error, thrown) {
                console.error('Error loading DataTable:', error);
                showAlert('Error', 'Gagal memuat data', 'error');
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "nama_kota" },
            { "data": "provinsi" }
        ],
        "columnDefs": [
            {
                "targets": -1,
                "orderable": false,
                "searchable": false
            }
        ],
        "order": [[0, "asc"]],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        }
    });

    // Click row to edit
    $('#tabel-kota-dt tbody').on('click', 'tr', function() {
        const data = kotaTable.row(this).data();
        if (data) {
            openModalEditDt(data.id, data.nama_kota, data.provinsi);
        }
    });
}

// Setup Event Listeners
function setupEventListeners() {
    // Submit Form Button
    const btnSubmit = document.getElementById('btn-submit-dt');
    if (btnSubmit) {
        btnSubmit.addEventListener('click', submitFormDt);
    }

    // Form Enter Key
    const formDt = document.getElementById('form-kota-dt');
    if (formDt) {
        formDt.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitFormDt();
            }
        });
    }

    // Modal Delete Button
    const btnHapus = document.getElementById('btn-hapus-dt');
    if (btnHapus) {
        btnHapus.addEventListener('click', deleteKotaDt);
    }

    // Modal Save Button
    const btnSimpan = document.getElementById('btn-simpan-dt');
    if (btnSimpan) {
        btnSimpan.addEventListener('click', updateKotaDt);
    }

    // Modal Close Button (Clear Form)
    const modalDt = document.getElementById('modal-kota-dt');
    if (modalDt) {
        modalDt.addEventListener('hidden.bs.modal', function() {
            document.getElementById('form-modal-dt').reset();
        });
    }
}

// Submit Form - Add New Kota
function submitFormDt() {
    const namaKota = document.getElementById('nama_kota_dt').value.trim();
    const provinsi = document.getElementById('provinsi_kota_dt').value.trim();

    if (!namaKota || !provinsi) {
        showAlert('Validation', 'Semua field harus diisi', 'warning');
        return;
    }

    // Show loading state
    const btnSubmitText = document.getElementById('btn-submit-dt-text');
    const btnSubmitSpinner = document.getElementById('btn-submit-dt-spinner');
    const btnSubmit = document.getElementById('btn-submit-dt');
    
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
            document.getElementById('form-kota-dt').reset();
            kotaTable.ajax.reload();
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
function openModalEditDt(id, nama, provinsi) {
    document.getElementById('modal-id-dt').value = id;
    document.getElementById('modal-nama-dt').value = nama;
    document.getElementById('modal-provinsi-dt').value = provinsi;
    
    const modal = new bootstrap.Modal(document.getElementById('modal-kota-dt'));
    modal.show();
}

// Update Kota
function updateKotaDt() {
    const id = document.getElementById('modal-id-dt').value;
    const nama = document.getElementById('modal-nama-dt').value.trim();
    const provinsi = document.getElementById('modal-provinsi-dt').value.trim();

    if (!nama || !provinsi) {
        showAlert('Validation', 'Semua field harus diisi', 'warning');
        return;
    }

    const btnSimpan = document.getElementById('btn-simpan-dt');
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
            bootstrap.Modal.getInstance(document.getElementById('modal-kota-dt')).hide();
            kotaTable.ajax.reload();
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
function deleteKotaDt() {
    const id = document.getElementById('modal-id-dt').value;
    
    if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        return;
    }

    const btnHapus = document.getElementById('btn-hapus-dt');
    const originalText = btnHapus.innerHTML;
    btnHapus.disabled = true;
    btnHapus.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...';

    axios.delete(`/api/kota/delete/${id}`)
    .then(response => {
        if (response.data.status) {
            showAlert('Success', response.data.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('modal-kota-dt')).hide();
            kotaTable.ajax.reload();
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
