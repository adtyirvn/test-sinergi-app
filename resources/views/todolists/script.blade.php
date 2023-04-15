<script>
    $(document).ready(function() {
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
    })

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    $('body').on('click','.tombol-tambah', function(e){
     e.preventDefault();
     $('#exampleModal').modal('show');
     $('.tombol-simpan').click(function(){
        simpan();
        // console.log(note, option);
     })
    })
    $('body').on('click', '.tombol-edit', function(e){
        e.preventDefault();
        let id = $(this).data('id');
        // alert(id);
        $.ajax({
            url:'todolistAjax/' + id + '/edit',
            type: 'GET',
            success: function(response){
                console.log(response.result);
                $('#exampleModal').modal('show');
                $('#note').val(response.result.note);
                $('input[name="gridRadios"][value='+response.result.complete+']').prop('checked', true);
                $('.tombol-simpan').click(function(){
                    simpan(id);
                })
            }
    })
    })
    function simpan(id = ''){
        let var_url;
        let var_type;
        if(id == ''){
            var_url = 'todolistAjax';
            var_type = 'POST';
        }else{
            var_url = 'todolistAjax/'+id;
            var_type = 'PUT';
        }
        console.log(var_type);
        let note = $('#note').val();
         let radios = $('[name="gridRadios"]');
         let option;
        for (var i = 0, length = radios.length; i < length; i++) {
        if (radios[i].checked) {
            option = radios[i].value;
            break;
            }
        }
        $.ajax({
            url: var_url,
            type: var_type,
            data: {
                note,
                option
            },
            success:function(response){

                if(response.errors){
                    console.log(response.errors);
                    $('.alert-danger').removeClass('d-none');
                    $('.alert-danger').append("<ul>");
                    $.each(response.errors, function(key, value){
                        $('.alert-danger').find("ul").append('<li>'+value+'</li>')
                    });
                    $('.alert-danger').append("</ul>");
                }else{
                    $('.alert-success').removeClass('d-none');
                    $('.alert-success').html(response.success);

                }
                $('#myTable').DataTable().ajax.reload();
            }

        })
    }
    $('#exampleModal').on('hidden.bs.modal',function(){
        $('#note').val('');
        $('.alert-danger').addClass('d-none');
        $('.alert-danger').html('');
        $('.alert-success').addClass('d-none');
        $('.alert-success').html('');

    })

 </script>
