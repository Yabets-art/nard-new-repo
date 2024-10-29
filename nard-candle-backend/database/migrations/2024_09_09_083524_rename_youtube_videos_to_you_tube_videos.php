<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameYoutubeVideosToYouTubeVideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Rename the table youtube_videos to you_tube_videos
        Schema::rename('youtube_videos', 'you_tube_videos');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rename the table back from you_tube_videos to youtube_videos
        Schema::rename('you_tube_videos', 'youtube_videos');
    }
}
