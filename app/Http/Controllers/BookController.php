<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
class BookController extends Controller
{
    #[OA\Get(
        path: '/api/books',
        summary: 'Get all books',
        tags: ['Books'],
        responses: [
            new OA\Response(response: 200, description: 'Books list')
        ]
    )]
    public function index()
    {
        $books = Book::all();
        return response()->json([
            'success' => true,
            'data' => $books,
        ], 200);
    }

    #[OA\Post(
        path: '/api/books',
        summary: 'Create new book',
        tags: ['Books'],
        security: [
            ['sanctum' => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title','author'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Clean Code'),
                    new OA\Property(property: 'publishedDate', type: 'string', format: 'date', example: '2024-01-15'),
                    new OA\Property(property: 'author', type: 'string', example: 'Robert C. Martin'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Book created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error')
        ]
    )]
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


    #[OA\Get(
        path: '/api/books/{id}',
        summary: 'Get single book',
        tags: ['Books'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Book details'),
            new OA\Response(response: 404, description: 'Book not found')
        ]
    )]
    public function show(string $id)
    {
        $book = Book::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $book
        ], 200);
    }

    #[OA\Put(
        path: '/api/books/{id}',
        summary: 'Update book',
        tags: ['Books'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Updated Book Title'),
                    new OA\Property(property: 'publishedDate', type: 'string', format: 'date', example: '2024-02-20'),
                    new OA\Property(property: 'author', type: 'string', example: 'New Author'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Book updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Book not found'),
            new OA\Response(response: 422, description: 'Validation error')
        ]
    )]
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

    #[OA\Delete(
        path: '/api/books/{id}',
        summary: 'Delete book',
        tags: ['Books'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Book deleted'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Book not found')
        ]
    )]
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
