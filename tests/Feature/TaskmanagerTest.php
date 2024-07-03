<?php

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{postJson, getJson, putJson, deleteJson};

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});


it('can get all tasks', function () {
    $task = [
        'title'=>'task title',
        'description' => 'description',
        'slug'=>time(),
    ];

    $task = Task::factory()->create();
    $attributes = Task::factory()->raw();
    

    // Act & Assert
    $this->actingAs($this->user)
        ->get(route('fetch-task', ['request' => $task, 'user' => $this->user]))
        ->assertOk();
});





