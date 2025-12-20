<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();
        return response()->json([
            'success' => 'true',
            'data' => $books,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255|unique:books,title',
            'publishedDate' => 'date_format:Y-m-d|before_or_equal:today',
            'author' => 'required|string|min:3|max:255',
        ]);
        $book = Book::create($validated);
        return response()->json([
            'success' => true,
            'data' => $book,
            'message' => 'Book Created Successfully.'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $book
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = Book::findOrFail($id);
        $validated= $request->validate([
           'title' => 'sometimes|required|string|min:3|max:255|unique:books,title,' . $book->id,
            'publishedDate' => 'sometimes|required|date_format:Y-m-d|before_or_equal:today',
            'author' => 'sometimes|required|string|min:3|max:255',
        ]);
        $book->update($validated);
        return response()->json([
            'success'=> true,
            'data'=> $book,
            'message'=> 'Book Updated Successfully.'
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id)->delete();
        return response()->json([
            'success'=> true,
            'data'=> $book,
            'message'=> 'Book Deleted Successfully.'
        ],200);
    }
}
