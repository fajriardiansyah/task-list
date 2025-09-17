<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class CalendarPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    protected string $view = 'filament.pages.calendar-page';

    protected static ?string $navigationLabel = 'Calendar';

    protected static ?string $title = 'Task Calendar';

    public function getTasks()
    {
        return \App\Models\Task::all()->map(function ($task) {
            return [
                'title' => $task->title,
                'start' => $task->due_date,
                'allDay' => true,
                'url' => route('filament.admin.resources.tasks.edit', $task->id),
                'color' => match ($task->status) {
                'completed' => '#4ade80', // hijau
                'in-progress' => '#facc15', // kuning
                default => '#f87171', // merah
                }
            ];
        })->toJson();
    }
}
