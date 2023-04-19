@extends('layout.template')
@section('konten')
 <!-- START FORM -->
 <form action='{{url('notes/'.$data->id)}}' method='post'>
    @csrf
    @method('PUT')
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <div class="mb-3 row">
            <div class="col-sm-2">
                <a href="{{ url('notes')}}" class="btn btn-secondary"><< Kembali</a>
            </div>
        </div>
         <div class="mb-3 row">
             <label for="note" class="col-sm-2 col-form-label">Note</label>
             <div class="col-sm-10">
                 <input type="text" class="form-control" name='note' value="{{$data->note}}" id="note">
             </div>
         </div>
         <div class="mb-3 row">
             <label for="tombol" class="col-sm-2 col-form-label"></label>
             <div class="col-sm-10"><button type="submit" class="btn btn-primary" name="submit">SIMPAN</button></div>
         </div>
    </div>
</form>
 <!-- AKHIR FORM -->
@endsection()
