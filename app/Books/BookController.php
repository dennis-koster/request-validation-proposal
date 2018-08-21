<?php

declare(strict_types=1);

namespace App\Books;

use App\Authors\AuthorRepository;

class BookController
{
    /**
     * @var BookRepository
     */
    protected $bookRepository;

    /**
     * @var AuthorRepository
     */
    protected $authorRepository;

    /**
     * @param BookRepository   $bookRepository
     * @param AuthorRepository $authorRepository
     */
    public function __construct(BookRepository $bookRepository, AuthorRepository $authorRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->authorRepository = $authorRepository;
    }


    /**
     * @param CreateBookRequest $request
     */
    public function create(CreateBookRequest $request)
    {
        $data = $request->getMappedData();

        // Create the author first
        if (isset($data['author'])) {
            $author = $this->authorRepository->create($data['author']);
            $data['author_id'] = $author->getId();
        }

        // Create the book
        $book = $this->bookRepository->create($data);

        // return Response::json(['data' => $transformer->transform($book), 'meta' => [pagination]])
    }

    public function update(PatchBookRequest $request, BookRepository $bookRepository, AuthorRepository $authorRepository, string $bookId)
    {
        $builderData = $request->getMappedData();
        $book = $bookRepository->findOrFail($bookId);

        if (isset($builderData['author'])) {
            $authorRepository->update($builderData['author'], $book->getAuthor());
        }

        $bookRepository->update($request->getMappedData(), $book);

        // return Response::json(['data' => $transformer->transform($book), 'meta' => [pagination]])
    }

}
