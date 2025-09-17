<?php

namespace App\Filament\Resources\Tasks;

use App\Filament\Resources\Tasks\Pages\CreateTask;
use App\Filament\Resources\Tasks\Pages\EditTask;
use App\Filament\Resources\Tasks\Pages\ListTasks;
use App\Filament\Resources\Tasks\Schemas\TaskForm;
use App\Filament\Resources\Tasks\Tables\TasksTable;
use App\Models\Task;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class TaskResource extends Resource
{
    protected static ?string $model = \App\Models\Task::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $recordTitleAttribute = 'Task List';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Task Details')
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    Textarea::make('description'),
                    DatePicker::make('due_date')
                        ->native(false)
                        ->label('Due Date')
                        ->nullable(),
                    Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'in-progress' => 'In Progress',
                            'completed' => 'Completed',
                    ])
                        ->default('pending')
                        ->required()
                        ->native(false),
                     Select::make('priority')
                            ->options([
                                'low' => 'Low',
                                'normal' => 'Normal',
                                'high' => 'High',
                                'urgent' => 'Urgent',
                            ])
                            ->default('normal')
                            ->required()
                            ->native(false)
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable()
                    ->label('Due Date'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'in-progress' => 'warning',
                        'completed' => 'success',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'urgent' => 'danger',
                        'high' => 'warning',
                        'normal' => 'info',
                        'low' => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date_countdown')
                ->label('Sisa Waktu')
                ->state(function ($record) {
                    if (!$record->due_date) {
                        return 'Tidak ada deadline';
                    }

                    $now = Carbon::now();
                    $dueDate = Carbon::parse($record->due_date);
                    
                    // Hitung selisih hari dan pastikan hasilnya bilangan bulat
                    $diffInDays = (int) $now->diffInDays($dueDate, false);

                    if ($diffInDays < 0) {
                        $diffInDays = abs($diffInDays);
                        return "Terlambat $diffInDays hari";
                    } elseif ($diffInDays === 0) {
                        return 'Hari ini';
                    } else {
                        return "$diffInDays hari lagi";
                    }
                })
                ->badge()
                ->color(function ($record) {
                    if (!$record->due_date) {
                        return 'gray';
                    }
                    $now = Carbon::now();
                    $dueDate = Carbon::parse($record->due_date);
                    $diffInDays = (int) $now->diffInDays($dueDate, false);

                    if ($diffInDays > 7) {
                        return 'info';
                    } elseif ($diffInDays > 0) {
                        return 'warning';
                    } elseif ($diffInDays === 0) {
                        return 'warning';
                    } else {
                        return 'danger';
                    }
                })
                ->sortable(query: fn (Builder $query, string $direction) => $query->orderBy('due_date', $direction)),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTasks::route('/'),
            'create' => CreateTask::route('/create'),
            'edit' => EditTask::route('/{record}/edit'),
        ];
    }
}
