<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Task;

class TaskTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function 一覧取得()
    {
        $tasks = Task::factory()->count(10)->create();

        $responses = $this->getJson('api/tasks');

        $responses
            ->assertOk()
            ->assertJsonCount($tasks->count());

    }

    /**
     * @test
     */
    public function 登録()
    {
        $data = ['title' => '登録テスト', 'is_done' => 1];

        $responses = $this->postJson('api/tasks', $data);

        $responses
            ->assertCreated()
            ->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function 更新()
    {
        $task = Task::factory()->create();

        $task->title = "更新";

        $responses = $this->patchJson("api/tasks/{$task->id}", $task->toArray());

        $responses
            ->assertOk()
            ->assertJsonFragment($task->toArray());
    }

    /**
     * @test
     */
    public function 削除()
    {
        $tasks = Task::factory()->count(10)->create();

        $responses = $this->deleteJson("api/tasks/1");

        $responses->assertOk();

        $responses = $this->getJson("api/tasks");

        $responses->assertJsonCount($tasks->count() - 1);
    }

}
