$(document).ready(function(){
    console.log('Akun.js loaded!'); // Debug
    
    // Add CSRF token to all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Load kategori berdasarkan tipe
    $('#tipeAkun').on('change', function() {
        const type = $(this).val();
        const $kategori = $('#kategoriAkun');
        
        $kategori.html('<option value="">Memuat...</option>');
        
        if (type) {
            $.get('/akun/categories/by-type', { type: type }, function(categories) {
                $kategori.html('<option value="">Pilih kategori</option>');
                categories.forEach(function(cat) {
                    $kategori.append(`<option value="${cat.id}">${cat.code} - ${cat.name}</option>`);
                });
            }).fail(function() {
                $kategori.html('<option value="">Gagal memuat kategori</option>');
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Tidak dapat memuat daftar kategori'
                });
            });
        } else {
            $kategori.html('<option value="">Pilih kategori</option>');
        }
    });

    // Initialize DataTable
    const table = $('#akunTable').DataTable({
        columnDefs: [
            { orderable: false, targets: [0, 5] } // No dan Tindakan tidak bisa diurut
        ],
        order: [[1, 'asc']], // default: urut berdasarkan kode
        pageLength: 10,
        lengthMenu: [5,10,25,50],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        },
        drawCallback: function() {
            // isi kolom nomor otomatis setelah table digambar
            this.api().column(0, {search:'applied', order:'applied'}).nodes().each(function(cell, i){
                cell.innerHTML = i + 1;
            });
        }
    });

    // Handle tambah akun (save to database)
    $('#formAkun').on('submit', function(e){
        e.preventDefault();
        
        // Check if we're editing or creating
        const isEditing = $(this).data('editing');
        const url = isEditing ? `/akun/${isEditing}` : '/akun';
        const method = isEditing ? 'PUT' : 'POST';

        // Manually build data object to ensure all values are captured
        const formData = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            account_category_id: $('#kategoriAkun').val(),
            code: $('#kodeAkun').val(),
            name: $('#namaAkun').val(),
            type: $('#tipeAkun').val(),
            description: $('#deskripsi').val()
        };
        
        if (method === 'PUT') {
            formData._method = 'PUT';
        }

        console.log('Sending data:', formData);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                // Reset form & close modal
                $('#formAkun')[0].reset();
                $('#formAkun').removeData('editing');
                bootstrap.Modal.getInstance(document.getElementById('buatAkunModal')).hide();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: isEditing ? 'Akun berhasil diperbarui' : 'Akun berhasil ditambahkan',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let errorMsg = 'Terjadi kesalahan saat menyimpan akun';
                
                if (xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMsg = Object.values(errors).flat().join('<br>');
                } else if (xhr.responseJSON?.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    html: errorMsg
                });
            }
        });
    });

    // Event handlers for edit and delete are below

    // Handle Edit button click
    $('#akunTable').on('click', '.edit-row', function(){
        const tr = $(this).closest('tr');
        const row = table.row(tr);
        const rowData = row.data();
        
        // Column 1 contains code like [1-1-1100], extract the full code after dash
        const fullCode = rowData[1]; // [1-1-1100]
        const codeMatch = fullCode.match(/\[.*?-(\d+)\]/);
        const code = codeMatch ? codeMatch[1] : fullCode.replace(/[\[\]]/g, '').split('-').pop();

        console.log('Edit clicked, code:', code);

        // Load account data for editing
        $.get('/akun/' + code + '/edit', function(account) {
            console.log('Account data:', account);
            
            // Set tipe first
            $('#tipeAkun').val(account.type);
            
            // Load categories for this type, then set the selected category
            $.get('/akun/categories/by-type', { type: account.type }, function(categories) {
                const $kategori = $('#kategoriAkun');
                $kategori.html('<option value="">Pilih kategori</option>');
                categories.forEach(function(cat) {
                    $kategori.append(`<option value="${cat.id}">${cat.code} - ${cat.name}</option>`);
                });
                
                // Set selected category after options are loaded
                $kategori.val(account.account_category_id);
            });
            
            $('#kodeAkun').val(account.code);
            $('#namaAkun').val(account.name);
            $('#deskripsi').val(account.description || '');
            
            // Store original code for update
            $('#formAkun').data('editing', code);
            
            // Change modal title
            $('.modal-title').text('Edit Akun');
            
            const modal = new bootstrap.Modal(document.getElementById('buatAkunModal'));
            modal.show();
        }).fail(function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: xhr.responseJSON?.message || 'Gagal memuat data akun'
            });
        });
    });

    // Handle Delete button click
    $('#akunTable').on('click', '.delete-row', function(){
        const tr = $(this).closest('tr');
        const row = table.row(tr);
        const rowData = row.data();
        
        // Column 1 contains code like [1-1-1100], extract the full code after dash
        const fullCode = rowData[1];
        const codeMatch = fullCode.match(/\[.*?-(\d+)\]/);
        const code = codeMatch ? codeMatch[1] : fullCode.replace(/[\[\]]/g, '').split('-').pop();
        
        console.log('Delete clicked, code:', code);
        
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus akun ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/akun/' + code,
                    type: 'DELETE',
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Akun berhasil dihapus',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: xhr.responseJSON?.message || 'Gagal menghapus akun'
                        });
                    }
                });
            }
        });
    });
    
    // Reset form when modal is closed
    $('#buatAkunModal').on('hidden.bs.modal', function() {
        $('#formAkun')[0].reset();
        $('#formAkun').removeData('editing');
        $('.modal-title').text('Tambah Akun');
        $('#kategoriAkun').html('<option value="">Pilih kategori</option>');
    });
});