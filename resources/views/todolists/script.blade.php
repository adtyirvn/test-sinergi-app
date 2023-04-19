<script type='text/javascript'>
    // 01_PROSES GET DATA
    $(document).ready(function() {
        let path = window.location.pathname;
            if(path === '/todolists'){
                $("#dua").addClass('active')
            }

        $('#myTable').DataTable({
             processing:true,
             serverside:true,
             ajax:"{{url('todolistAjax')}}",
             columns:[{
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
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        // 02_PROSES TAMBAH DATA
        $('body').on('click','.tombol-tambah', function(e){
            e.preventDefault();
            $('.tombol-simpan-edit').hide()
            $('.tombol-simpan').show()
            $('#exampleModal').modal('show');
            $('.tombol-simpan').click(function(e){
                e.stopImmediatePropagation();
                simpan();
            })
        })

        // 03_PROSES EDIT DATA
        $('body').on('click', '.tombol-edit', function(e){
            e.preventDefault();
            $('.tombol-simpan-edit').show()
            $('.tombol-simpan').hide()
            const id = $(this).data('id');
            console.log(`edit ${id}`)
            $.ajax({
                url:'todolistAjax/' + id + '/edit',
                type: 'GET',
                success: function(response){
                    $('#exampleModal').modal('show');
                    $('#note').val(response.result.note);
                    $('input[name="gridRadios"][value='+response.result.complete+']').prop('checked', true);
                    $('.tombol-simpan-edit').on('click', function(e){
                        simpan(id)
                        e.stopImmediatePropagation();

                    })
                }
            })
        })
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

        function simpan(id = ''){
            function checkUrlType(id) {
                if (id === ''){
                    return {
                        url : 'todolistAjax/',
                        type : 'POST'
                    }

                }
                return {
                    url : 'todolistAjax/'+id,
                    type : 'PUT'
                }
            }
            const note = $('#note').val();
            const radios = $('[name="gridRadios"]');
            function checkOption(radios) {
                for (var i = 0, length = radios.length; i < length; i++) {
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
                success: function(response){
                    if (response.errors) {
                        $('.alert-danger').addClass('d-none');
                        $('.alert-danger').html('');
                        console.log(response.errors);
                        $('.alert-danger').removeClass('d-none');
                        $('.alert-danger').append("<ul>");
                        $.each(response.errors, function(key, value){
                            $('.alert-danger').find("ul").append('<li>'+value+'</li>')
                        });
                        $('.alert-danger').append("</ul>");
                    } else {
                        $('.alert-success').addClass('d-none');
                        $('.alert-success').html('');
                        $('.alert-success').removeClass('d-none');
                        $('.alert-success').html(response.success);
                        $('#myTable').DataTable().ajax.reload();
                    }
                }
            })
        }


        $('#exampleModal').on('hidden.bs.modal',function(){
            $('.alert-danger').addClass('d-none');
            $('.alert-danger').html('');
            $('.alert-success').addClass('d-none');
            $('.alert-success').html('');
            $('#note').val('');
        })
    })
</script>
