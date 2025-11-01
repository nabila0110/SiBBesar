
;(function($){
    // Ensure code runs after DOM ready
    $(function(){
        // Currency formatting function
        function formatCurrency($input) {
            // Remove all non-digits
            let value = ($input.val() || '').replace(/\D/g, '');
            // Format as currency
            if (value !== '') {
                value = parseInt(value);
                $input.val('Rp ' + value.toLocaleString('id-ID'));
            }
        }

        // Format currency inputs on page load and on input
        $('.currency-input').each(function() {
            formatCurrency($(this));
        }).on('input', function() {
            formatCurrency($(this));
        });

        // Initialize DataTable
        var table = $('#tabelAset').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
            }
        });

        // Handle form submission (use form action attribute as URL)
        $('#formAset').on('submit', function(e) {
            e.preventDefault();

            let form = this;
            let formData = new FormData(form);

            // Clean currency values before sending
            ['purchase_price', 'accumulated_depreciation'].forEach(field => {
                let value = formData.get(field);
                if (value) {
                    formData.set(field, value.replace(/\D/g, ''));
                }
            });

            // Determine URL from form action; fallback to current location
            let url = $(form).attr('action') || window.location.href;

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        $('#modalTambahAset').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Terjadi kesalahan'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan saat menyimpan data'
                    });
                }
            });
        });

        // Click handler for modal save button to submit the form
        $(document).on('click', '#simpanAset', function(e){
            e.preventDefault();
            $('#formAset').submit();
        });

        // Delegated handler for Delete (works for dynamic rows)
        $(document).on('click', '.delete-asset', function(e){
            e.preventDefault();
            var id = $(this).data('id');
            if (!id) return;

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data asset akan dihapus permanent!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/asset/' + id,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val()
                        },
                        success: function(response){
                            Swal.fire('Terhapus!', response.message || 'Berhasil dihapus', 'success').then(function(){
                                location.reload();
                            });
                        },
                        error: function(xhr){
                            Swal.fire('Error!', (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal menghapus', 'error');
                        }
                    });
                }
            });
        });

        // Edit action: navigate to edit page
        $(document).on('click', '.edit-asset', function(e){
            e.preventDefault();
            var id = $(this).data('id');
            if (!id) return;
            // Navigate to resource edit route
            window.location.href = '/asset/' + id + '/edit';
        });
    });
})(jQuery);