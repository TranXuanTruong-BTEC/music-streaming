<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Artist;
use Illuminate\Support\Facades\Storage;

class ClearArtistImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'artists:clear-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all artist images';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $artists = Artist::whereNotNull('image_url')->get();

        foreach ($artists as $artist) {
            if (Storage::disk('public')->exists($artist->image_url)) {
                Storage::disk('public')->delete($artist->image_url);
            }
            $artist->image_url = null;
            $artist->save();
        }

        $this->info('All artist images have been cleared.');
    }
}
