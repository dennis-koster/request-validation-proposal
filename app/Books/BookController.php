<?php

declare(strict_types=1);

namespace App\Books;

class BookController
{
    /**
     * @param CreateBookRequest $request
     * @param BookBuilder       $builder
     */
    public function create(CreateBookRequest $request, BookBuilder $builder)
    {
        $book = $builder->build($request->getBuilderData());

        // $repository->persist($book)

        // return Response::json(['data' => $transformer->transform($book), 'meta' => [pagination]])
        // return $transformer->transform($book)
    }

    public function update(PatchBookRequest $request, BookRepository $bookRepository, string $bookId)
    {
        $builderData = $request->getBuilderData();
        $book = $bookRepository->findOrFail($bookId);

        if (isset($builderData['author'])) {
            $authorRepository->update($builderData['author'], $book->getAuthor());
        }

        $bookRepository->update($request->getBuilderData(), $book);

        // return Response::json(['data' => $transformer->transform($book), 'meta' => [pagination]])
    }

}
