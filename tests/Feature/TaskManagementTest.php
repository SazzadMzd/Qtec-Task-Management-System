<?php

namespace Tests\Feature;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    private const INDEX_ROUTE = '/tasks';
    private const CREATE_ROUTE = '/tasks/create';

    public function test_task_index_page_loads_successfully(): void
    {
        $response = $this->get(self::INDEX_ROUTE);

        $response->assertOk();
        $response->assertSee('Task Overview');
    }

    public function test_task_can_be_created_with_valid_data(): void
    {
        $response = $this->post(self::INDEX_ROUTE, $this->validPayload([
            'title' => 'Prepare project handover',
            'assigned_to' => 'Sazzad',
        ]));

        $response->assertRedirect(self::INDEX_ROUTE);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Prepare project handover',
            'assigned_to' => 'Sazzad',
            'status' => Task::STATUS_PENDING,
        ]);
    }

    public function test_task_creation_requires_the_expected_fields(): void
    {
        $response = $this->from(self::CREATE_ROUTE)->post(self::INDEX_ROUTE, [
            'title' => '',
            'assigned_to' => '',
            'status' => 'invalid-status',
            'start_time' => '',
            'end_time' => '',
        ]);

        $response->assertRedirect(self::CREATE_ROUTE);
        $response->assertSessionHasErrors([
            'title',
            'assigned_to',
            'status',
        ]);
    }

    public function test_task_can_be_created_without_schedule_fields(): void
    {
        $response = $this->post(self::INDEX_ROUTE, $this->validPayload([
            'title' => 'Create task without schedule',
            'start_time' => '',
            'end_time' => '',
        ]));

        $response->assertRedirect(self::INDEX_ROUTE);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Create task without schedule',
            'start_time' => null,
            'end_time' => null,
        ]);
    }

    public function test_task_can_be_updated(): void
    {
        $task = Task::create($this->validPayload([
            'title' => 'Initial task',
            'assigned_to' => 'Initial owner',
        ]));

        $response = $this->put("/tasks/{$task->id}", $this->validPayload([
            'title' => 'Updated task',
            'assigned_to' => 'Updated owner',
            'status' => Task::STATUS_IN_PROGRESS,
        ]));

        $response->assertRedirect(self::INDEX_ROUTE);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated task',
            'assigned_to' => 'Updated owner',
            'status' => Task::STATUS_IN_PROGRESS,
        ]);
    }

    public function test_task_schedule_can_be_cleared_on_update(): void
    {
        $task = Task::create($this->validPayload());

        $response = $this->put("/tasks/{$task->id}", $this->validPayload([
            'title' => 'Task without schedule',
            'start_time' => '',
            'end_time' => '',
        ]));

        $response->assertRedirect(self::INDEX_ROUTE);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Task without schedule',
            'start_time' => null,
            'end_time' => null,
        ]);
    }

    public function test_task_can_be_deleted(): void
    {
        $task = Task::create($this->validPayload());

        $response = $this->delete("/tasks/{$task->id}");

        $response->assertRedirect(self::INDEX_ROUTE);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_tasks_can_be_filtered_by_status(): void
    {
        $pendingTask = Task::create($this->validPayload([
            'title' => 'Pending task',
            'status' => Task::STATUS_PENDING,
        ]));

        $completedTask = Task::create($this->validPayload([
            'title' => 'Completed task',
            'status' => Task::STATUS_COMPLETED,
        ]));

        $response = $this->get('/tasks?status=' . Task::STATUS_PENDING);

        $response->assertOk();
        $response->assertSee($pendingTask->title);
        $response->assertDontSee($completedTask->title);
    }

    public function test_tasks_can_be_filtered_by_search_text_in_title_or_assignee(): void
    {
        $titleMatch = Task::create($this->validPayload([
            'title' => 'Prepare deployment checklist',
            'assigned_to' => 'Nadia',
        ]));

        $assigneeMatch = Task::create($this->validPayload([
            'title' => 'General QA review',
            'assigned_to' => 'Deploy Team',
        ]));

        $nonMatch = Task::create($this->validPayload([
            'title' => 'Write release summary',
            'assigned_to' => 'Sazzad',
        ]));

        $response = $this->get('/tasks?search=deploy');

        $response->assertOk();
        $response->assertSee($titleMatch->title);
        $response->assertSee($assigneeMatch->title);
        $response->assertDontSee($nonMatch->title);
    }

    public function test_tasks_can_be_filtered_as_overdue(): void
    {
        Carbon::setTestNow('2026-04-10 18:00:00');

        $overdueTask = Task::create($this->validPayload([
            'title' => 'Overdue task',
            'status' => Task::STATUS_IN_PROGRESS,
            'start_time' => '2026-04-10 08:00:00',
            'end_time' => '2026-04-10 12:00:00',
        ]));

        $upcomingTask = Task::create($this->validPayload([
            'title' => 'Upcoming task',
            'status' => Task::STATUS_IN_PROGRESS,
            'start_time' => '2026-04-10 19:00:00',
            'end_time' => '2026-04-10 21:00:00',
        ]));

        $response = $this->get('/tasks?focus=overdue');

        $response->assertOk();
        $response->assertSee($overdueTask->title);
        $response->assertDontSee($upcomingTask->title);

        Carbon::setTestNow();
    }

    public function test_nearing_end_filter_excludes_overdue_and_sorts_by_end_time(): void
    {
        Carbon::setTestNow('2026-04-10 18:00:00');

        $secondSoonest = Task::create($this->validPayload([
            'title' => 'Ends later',
            'status' => Task::STATUS_IN_PROGRESS,
            'start_time' => '2026-04-10 17:00:00',
            'end_time' => '2026-04-10 20:00:00',
        ]));

        $firstSoonest = Task::create($this->validPayload([
            'title' => 'Ends sooner',
            'status' => Task::STATUS_PENDING,
            'start_time' => '2026-04-10 17:30:00',
            'end_time' => '2026-04-10 19:00:00',
        ]));

        $overdueTask = Task::create($this->validPayload([
            'title' => 'Already overdue',
            'status' => Task::STATUS_PENDING,
            'start_time' => '2026-04-10 09:00:00',
            'end_time' => '2026-04-10 10:00:00',
        ]));

        $response = $this->get('/tasks?focus=ending_soon');

        $response->assertOk();
        $response->assertSee($firstSoonest->title);
        $response->assertSee($secondSoonest->title);
        $response->assertDontSee($overdueTask->title);
        $response->assertSeeInOrder([$firstSoonest->title, $secondSoonest->title]);

        Carbon::setTestNow();
    }

    public function test_tasks_can_be_filtered_as_newly_created(): void
    {
        Carbon::setTestNow('2026-04-10 18:00:00');

        $newTask = Task::create($this->validPayload([
            'title' => 'New task',
        ]));
        $newTask->forceFill([
            'created_at' => Carbon::parse('2026-04-10 12:00:00'),
            'updated_at' => Carbon::parse('2026-04-10 12:00:00'),
        ])->save();

        $oldTask = Task::create($this->validPayload([
            'title' => 'Old task',
        ]));
        $oldTask->forceFill([
            'created_at' => Carbon::parse('2026-04-08 12:00:00'),
            'updated_at' => Carbon::parse('2026-04-08 12:00:00'),
        ])->save();

        $response = $this->get('/tasks?focus=newly_created');

        $response->assertOk();
        $response->assertSee($newTask->title);
        $response->assertDontSee($oldTask->title);

        Carbon::setTestNow();
    }

    public function test_task_status_can_be_updated_from_the_index_page(): void
    {
        $task = Task::create($this->validPayload([
            'status' => Task::STATUS_PENDING,
        ]));

        $response = $this->patch("/tasks/{$task->id}/status", [
            'status' => Task::STATUS_COMPLETED,
        ]);

        $response->assertRedirect(self::INDEX_ROUTE);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => Task::STATUS_COMPLETED,
        ]);
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Review task implementation',
            'description' => 'Validate the main task management workflow.',
            'assigned_to' => 'QA Engineer',
            'status' => Task::STATUS_PENDING,
            'start_time' => '2026-04-10 09:00:00',
            'end_time' => '2026-04-10 17:00:00',
        ], $overrides);
    }
}
