<?php

namespace App\Http\Controllers;

use App\Models\Todolist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class TodolistAjaxContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Todolist::orderBy('note', 'asc');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($data) {
                return view('todolists.tombol')->with('data', $data);
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'note' => 'required',
            'option' => 'required|numeric|min:0|max:1'
        ], [
            'note.required' => 'Note wajib diisi',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'note' => $request->note,
                'complete' => $request->option
            ];
            Todolist::create($data);
            return response()->json(['success' => "Berhasil menyimpan data"]);
        }
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
    public function edit(string $id)
    {
        $data = Todolist::where('id', $id)->first();
        return response()->json(['result' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'note' => 'required',
            'option' => 'required|numeric|min:0|max:1'
        ], [
            'note.required' => 'Note wajib diisi',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'note' => $request->note,
                'complete' => $request->option
            ];
            Todolist::where('id', $id)->update($data);
            return response()->json(['success' => "Berhasil melakukan update data"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
