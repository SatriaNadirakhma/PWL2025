<?php

namespace App\Http\Controllers;

use App\Models\Item; // Perbaikan nama model
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('items.index', compact('items'));
    }

    //Fungsi Index digunakan untuk menampilkan semua data dalam bentuk daftar.

    public function create()
    {
        return view('items.create');
    }

    //Fungsi Create digunakan untuk menampilkan halaman form untuk menambahkan data.

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        // Hanya menyimpan atribut yang diizinkan
        Item::create($request->only(['name', 'description']));

        return redirect()->route('items.index')->with('success', 'Item added successfully');
    }

    //Fungsi store digunakan untuk menyimpan data yang sudah dibuat

    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    //Fungsi Show untuk menampilkan data

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    //Fungsi edit untuk mengedit item pada form

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $item->update($request->only(['name', 'description']));

        return redirect()->route('items.index')->with('success', 'Item updated successfully');
    }

    //Fungsi update digunakan untuk menyimpan hasil update ke dalam form

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully');
    }

    //Fungsi destroy untuk menghapus item pada form
}

