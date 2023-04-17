<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $katakunci = $request->katakunci;
        $jumlahbaris = 5;
        if (strlen($katakunci)) {
            $data_new = Note::where('note', 'like', "%$katakunci%")->paginate($jumlahbaris);
        } else {
            $data_new = User::leftJoin('notes', 'users.id', '=', 'notes.user_id')->where('users.id', Auth::user()->id)->paginate(10);
        }
        return view('notes.index')->with('data', $data_new);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Session::flash('note', $request->note);
        $request->validate([
            'note' => 'required',
        ], [
            'note.required' => 'Note wajib diisi'
        ]);
        $data = [
            'note' => $request->note,
            'user_id' => Auth::user()->id,
        ];
        Note::create($data);
        return redirect()->to('notes')->with('success', 'Berhasil menambahkan data');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Note::where('id', $id)->first();
        return view('notes.edit')->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'note' => 'required',
        ], [
            'note.required' => 'Note wajib diisi'
        ]);
        $data = [
            'note' => $request->note,
        ];
        Note::where('id', $id)->update($data);
        return redirect()->to('notes')->with('success', 'Berhasil melakukan update data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Note::where('id', $id)->delete();
        return redirect()->to('notes')->with('success', 'Berhasil menghapus data');
    }
}
