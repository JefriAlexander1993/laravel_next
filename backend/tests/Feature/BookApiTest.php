<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_get_all_books()
    {
        $books = Book::factory(4)->create();

        $response = $this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title' => $books[0]->title
        ]);
    }

    /** @test */
    public function can_get_one_book()
    {
        $book = Book::factory()->create();

        $response = $this->getJson(route('books.show', $book));

        $response->assertJsonFragment([
            'title' => $book->title
        ]);
    }


    /** @test */
    public function can_create_book()
    {


        $this->postJson(route('books.store', []))
            ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store', ['title' => 'My new book']))->assertJsonFragment([
            'title' => 'My new book'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'My new book'
        ]);
    }

    /** @test */
    public function can_update_book()
    {

        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book),[])
        ->assertJsonValidationErrorFor('title');

        $this->patchJson(route('books.update', $book),['title'=>'Libro editado'])->assertJsonFragment([
            'title' => 'Libro editado'
        ]);
    }

        /** @test */
        public function can_delete_book()
        {

            $book = Book::factory()->create();

            $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();
            $this->assertDatabaseCount('books',0);
        }
}
