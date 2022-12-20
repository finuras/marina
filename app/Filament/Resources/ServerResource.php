<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServerResource\Pages;
use App\Filament\Resources\ServerResource\RelationManagers;
use App\Jobs\ServerDeleteJob;
use App\Models\Server;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServerResource extends Resource
{
    protected static ?string $model = Server::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Card::make()
                    ->label('Server Details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->placeholder('Ex: Server for Friends')
                            ->helperText('A friendly name for this server.')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('ip')
                            ->label('IP Address')
                            ->placeholder('Ex: 192.168.1.1')
                            ->helperText('IP address of the server without the port. Ex: 192.168.1.2')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('port')
                            ->label('Port')
                            ->placeholder('Ex: 22')
                            ->default('22')
                            ->helperText('The SSH port, usually the 22')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('username')
                            ->label('Root Username')
                            ->placeholder('Ex: root')
                            ->default('root')
                            ->helperText('The username of the root user on the server.')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('password')
                            ->label('Root Password')
                            ->placeholder('Password given by your server provider')
                            ->helperText('The password of the root user on the server.')
                            ->password()
                            ->maxLength(255),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Id'),
                Tables\Columns\TextColumn::make('name')->label('Name'),
                Tables\Columns\TextColumn::make('ip')->label('IP')->copyable(),
                Tables\Columns\TextColumn::make('port')->label('Port'),
                Tables\Columns\TextColumn::make('username')->label('Username'),
                Tables\Columns\BadgeColumn::make('provisioned')
                    ->label('Ready')
                    ->colors([
                        'success' => fn ($state): bool => $state === 1,
                        'danger' => fn ($state): bool => $state !== 1,
                    ])
                    ->formatStateUsing(fn ($record): string => $record->provisioned === 1 ? 'Ready' : 'Not ready'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
            ])
            ->bulkActions([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('manageServer')
                    ->label('Manage')
                    ->icon('heroicon-o-desktop-computer')
                    ->color('info')
                    ->action(function(Server $record, array $data) {
                        return redirect("/admin/servers/{$record->id}/remote-command");
                    }),
                Tables\Actions\Action::make('deleteServer')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->action(function(Server $record) {
                        if(0 >= 1){
                            Notification::make()
                                ->title('You cannot delete a server with instances.')
                                ->danger()
                                ->send();

                            return;
                        }

                        // Do stuff....

                        Notification::make()
                            ->title('The server will be deleted in a few minutes.')
                            ->success()
                            ->send();
                    }),
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
            'index' => Pages\ListServers::route('/'),
            'create' => Pages\CreateServer::route('/create'),
            'edit' => Pages\EditServer::route('/{record}/edit'),
            'manage' => Pages\RemoteCommand::route('/{record}/remote-command'),
        ];
    }
}
