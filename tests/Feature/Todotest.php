<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class Todotest extends TestCase
{
    use DatabaseMigrations;
    /**
     * Test to see if user can see all the todos
     *
     * @return void
     */
    public function testCanSeeAllTodos()
    {
        factory(\App\Todo::class, 30)->create();
        $response = $this->call('GET', 'api/v1/todos');

        $todos = json_decode($response->getContent(), true);

        $this->assertEquals(30, count($todos));
    }

    public function testCanSeeOneTodo()
    {
        $oneTodo = factory(\App\Todo::class)->create([
                        "title" => "new todo",
                        "description" => "new todo description",
                        "due_date" => "2017-05-11"
                    ]);

        $response = $this->call('GET', 'api/v1/todos/1');

        $todo = json_decode($response->getContent(), true);

        $this->assertEquals($oneTodo->title, $todo['title']);

    }

    public function testCanAddOneTodo()
    {
        $response = $this->call('POST', 'api/v1/todos', [
            "title" => "new todo todo",
            "description" => "new todo todo description",
            "due_date" => "2017-05-11"
        ]);

        $this->assertDatabaseHas('todos', [
           "title" => "new todo todo", 
        ]);
    }

    // Test whether one can edit a todo
    public function testCanEditATodo()
    {
        $oneTodo = factory(\App\Todo::class)->create([
                        "title" => "new todo",
                        "description" => "new todo description",
                        "due_date" => "2017-05-11"
                    ]);
        $response = $this->call('PUT', 'api/v1/todos/1', [
            "title" => "another new todo todo",
        ]);

        $this->assertDatabaseHas('todos', [
           "title" => "another new todo todo", 
        ]);
    }

    // Test whether one can delete a todo
    public function testCanDeleteATodo()
    {
        $oneTodo = factory(\App\Todo::class)->create([
                        "title" => "new todo",
                        "description" => "new todo description",
                        "due_date" => "2017-05-11"
                    ]);
        $response = $this->call('DELETE', 'api/v1/todos/1');

        $this->assertDatabaseMissing('todos', [
           "title" => "another new todo todo", 
        ]);
    }
}
