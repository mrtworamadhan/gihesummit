<?php

namespace App\Filament\Resources\News;

use App\Filament\Resources\News\Pages\ManageNews;
use App\Models\News;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static string | UnitEnum | null $navigationGroup = 'Media';

    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('external_url')
                    ->label('Link Berita (Paste di sini lalu klik di luar kotak)')
                    ->url()
                    ->required()
                    ->columnSpanFull()
                    ->live(onBlur: true) // Aktifkan fitur live saat kursor keluar dari input
                    ->afterStateUpdated(function (?string $state, Set $set) {
                        if (blank($state)) return;

                        try {
                            // Ambil HTML dari website tujuan
                            $html = @file_get_contents($state);
                            if (! $html) return;

                            // Cari Meta Title (Judul Berita)
                            if (preg_match('/<title>([^<]+)<\/title>/i', $html, $matches)) {
                                $set('title', html_entity_decode($matches[1]));
                            }

                            // Cari Meta OG Image (Thumbnail Bawaan Berita)
                            if (preg_match('/<meta[^>]+property=[\'"]og:image[\'"][^>]+content=[\'"]([^\'"]+)[\'"]/i', $html, $matches) || 
                                preg_match('/<meta[^>]+content=[\'"]([^\'"]+)[\'"][^>]+property=[\'"]og:image[\'"]/i', $html, $matches)) {
                                $set('image_path', $matches[1]);
                            }
                        } catch (\Exception $e) {
                            // Abaikan jika website memblokir scraping
                        }
                    }),

                // 2. Judul Berita (Bisa diisi otomatis dari fungsi di atas)
                TextInput::make('title')
                    ->label('Judul Berita')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                // 3. Thumbnail URL (Berubah dari FileUpload jadi TextInput)
                TextInput::make('image_path')
                    ->label('URL Thumbnail (Otomatis)')
                    ->url()
                    ->columnSpanFull(),

                // 4. Publisher
                TextInput::make('publisher_name')
                    ->label('Nama Media (Cth: DetikNews)'),

                Toggle::make('is_active')
                    ->label('Tampilkan di Landing Page')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Thumbnail')
                    ->getStateUsing(fn ($record) => $record->image_path),
                TextColumn::make('title')->searchable()->limit(40),
                TextColumn::make('publisher_name'),
                ToggleColumn::make('is_active'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageNews::route('/'),
        ];
    }
}
