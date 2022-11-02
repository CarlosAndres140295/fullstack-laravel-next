<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */

        function test_can_get_all_books(){
            $books = Book::factory(10)->create();

            $response = $this->getJson(route('books.index'));

            $response->assertJsonFragment([
                'name'=>$books[0]->name
            ]);
        }

        function test_can_get_one_book(){

            $book = Book::factory()->create();

            $response = $this->getJson(route('books.show', $book));

            $response->assertJsonFragment([
                'name' => $book->name
            ]);
        }

        function test_can_create_book(){

            $this->postJson(route('books.store'), [])
                ->assertJsonValidationErrorFor('name');

            $response = $this->postJson(route('books.store'),[
                'name'=>'mybook',
                'description' => 'mybook description'
            ]);

            $response -> assertJsonFragment([
                'name' => 'mybook'
            ]);

            $this->assertDatabaseHas('books', [
               'name' => 'mybook'
            ]);

        }

    function test_can_update_book(){

        $book = Book::factory()->create();

        $response = $this->patchJson(route('books.update',$book), [
            'name' => 'my-book updated',
            'description' => 'description updated'
        ])->assertJsonFragment([
            'name' => 'my-book updated'
        ]);

        $this->assertDatabaseHas('books', [
            'name'=>'my-book updated'
        ]);

    }

    function test_can_delete_book(){
        $book = Book::factory()->create();

        $response = $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();


        $this->assertDatabaseCount('books', 0);
    }
}
