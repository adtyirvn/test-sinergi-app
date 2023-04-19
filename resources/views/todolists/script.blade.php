<script type='text/javascript'>
    // 01_PROSES GET DATA
    $(document).ready(function() {
        let path = window.location.pathname;
            if(path === '/todolists'){
                $("#dua").addClass('active')
            }

        $('#myTable').DataTable({
             processing: true,
             serverside: true,
             ajax: "{{url('todolistAjax')}}",
             columns: [{
                    data:'DT_RowIndex',
                    name:'DT_RowIndex',
                    orderable:false,
                    searchable: false
                },{
                    data:'note',
                    name:'Note'
                },{
                    data:'complete',
                    name:'Complete'
                },{
                    data:'aksi',
                    name:'Aksi'
                }
            ]
        })

        $('#testForm').submit(function(e){
            console.log(testi)
        })
        // AJAX GLOBAL SETUP
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        // 02_PROSES TAMBAH DATA
        $('body').on('click','.tombol-tambah', function(e){
            e.preventDefault();
            $('#input-form').show();
            $('#exampleModalLabel').html('Tambah Data');
            $('#exampleModal').modal('show');
        })

        // 03_PROSES EDIT DATA
        $('body').on('click', '.tombol-edit', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            $.ajax({
                url: `todolistAjax/${id}/edit`,
                type: 'GET',
                success: function(response) {
                    $('#input-form').show();
                    $('#exampleModalLabel').html('Edit Data');
                    $('#exampleModal').modal('show');
                    $('#note').val(response.result.note);
                    $(`input[name="gridRadios"][value="${String(response.result.complete)}"]`).prop('checked', true);
                    $('.tombol-simpan').attr('data-id', id);
                }
            });
        });

        $('body').on('click', '.tombol-simpan', function(e) {
            const id = $(this).attr('data-id');
            simpan(id)
            $('.tombol-simpan').attr('data-id', '');
        });

        // 04_PROSES DELETE DATA
        $('body').on('click', '.tombol-del', function(e){
            e.preventDefault();
            if(confirm('Yakin mau hapus data ini?') == true) {
                const id = $(this).data('id');
                console.log(id)
                $.ajax({
                    url: 'todolistAjax/'+id,
                    type: 'DELETE',
                });
                $('#myTable').DataTable().ajax.reload();
            }
        })

        function simpan(id = '') {
            const checkUrlType = (id) => {
                return id === '' ? { url: 'todolistAjax/', type: 'POST' } : { url: `todolistAjax/${id}`, type: 'PUT' }
            }
            const note = $('#note').val();
            const radios = $('[name="gridRadios"]');
            const checkOption = (radios) => {
                for (let i = 0; i < radios.length; i++) {
                    if (radios[i].checked) {
                        return radios[i].value;
                    }
                }
            }
            const address = checkUrlType(id);
            const option = checkOption(radios);
            $.ajax({
                url: address.url,
                type: address.type,
                data: {
                    note,
                    option,
                },
                success: function(response) {
                    const $dangerAlert = $('.alert-danger');
                    const $successAlert = $('.alert-success');

                    $dangerAlert.addClass('d-none').html('');
                    $successAlert.addClass('d-none').html('');

                    if (response.errors) {
                        $dangerAlert.removeClass('d-none').append('<ul>');
                        $.each(response.errors, function(key, value) {
                            $dangerAlert.find('ul').append(`<li>${value}</li>`);
                        });
                        $dangerAlert.append('</ul>');
                    } else {
                        $successAlert.removeClass('d-none').html(response.success);
                        $('#input-form').hide();
                        $('#myTable').DataTable().ajax.reload();
                        }
                },
            });
         }

        $('#exampleModal').on('hidden.bs.modal', function() {
                const $dangerAlert = $('.alert-danger');
                const $successAlert = $('.alert-success');

                $dangerAlert.addClass('d-none').html('');
                $successAlert.addClass('d-none').html('');

                $('#note').val('');
                $('.tombol-simpan').removeAttr('data-id');
            });
        })
</script>
