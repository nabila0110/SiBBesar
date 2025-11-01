$(document).ready(function(){
    // Add CSRF token to all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize DataTable
    const table = $('#akunTable').DataTable({
        columnDefs: [
            { orderable: false, targets: [0, 4] } // No dan Tindakan tidak bisa diurut
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
        
        const formData = new FormData(this);
        
        // Check if we're editing or creating
        const isEditing = $(this).data('editing');
        const url = isEditing ? `/akun/${isEditing}` : '/akun';
        const method = isEditing ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Reset form & close modal
                $('#formAkun')[0].reset();
                $(this).removeData('editing');
                bootstrap.Modal.getInstance(document.getElementById('buatAkunModal')).hide();
                
                // Refresh the DataTable
                location.reload();
                
                alert(isEditing ? 'Akun berhasil diperbarui!' : 'Akun berhasil ditambahkan!');
            }.bind(this),
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan akun';
                alert('Error: ' + message);
            }
        });
    });

    // Event handlers for edit and delete are below

    // Handle Edit button click
    $('#akunTable').on('click', '.edit-row', function(){
        const tr = $(this).closest('tr');
        const data = table.row(tr).data();
        const code = data[1].replace(/[\[\]]/g, '');

        // Load account data for editing
        $.get('/akun/' + code + '/edit', function(account) {
            $('#kodeAkun').val(account.code);
            $('#namaAkun').val(account.name);
            $('#kelompok').val(account.group);
            $('#tipe').val(account.type);
            $('#jenisBeban').val(account.expense_type || '');
            
            // Store original code for update
            $('#formAkun').data('editing', code);
            
            const modal = new bootstrap.Modal(document.getElementById('buatAkunModal'));
            modal.show();
        });
    });

    // Handle Delete button click
    $('#akunTable').on('click', '.delete-row', function(){
        if (!confirm('Yakin ingin menghapus akun ini?')) return;
        
        const tr = $(this).closest('tr');
        const data = table.row(tr).data();
        const code = data[1].replace(/[\[\]]/g, '');
        
        $.ajax({
            url: '/akun/' + code,
            type: 'DELETE',
            success: function(response) {
                table.row(tr).remove().draw();
                alert('Akun berhasil dihapus!');
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Gagal menghapus akun'));
            }
        });
    });
});