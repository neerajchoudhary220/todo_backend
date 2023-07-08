<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Image;



class Media extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'user_id', 'slug', 'path', 'mime_type', 'extension', 'size', 'tags', 'thumbnail'];

    protected $casts = [
        'tags' => 'array',
        'thumbnail' => 'array',
    ];

    public function getFullPathAttribure(): string
    {
        return Storage::url($this->path);
    }



    public static function save_media($file, string $dir = 'all', array $tags = [], int $user_id = null, int $role_id = null, $store_as = 'file')
    {
        $path = $file->storePublicly("public/" . $dir);

        $thumbnail = [];
        if ($store_as == 'image') {
            $thumbnail = Media::create_thumbnails($file, $path);
        }

        $image_slug = \App\Helpers\Slug::create($file->getClientOriginalName(), Media::class);

        return Media::create([
            "user_id" => $user_id,
            // "role_id" => $role_id,
            "slug" => $image_slug,
            "path" => $path,
            "name" => $file->getClientOriginalName(),
            "mime_type" => $file->getMimeType(),
            "extension" => $file->getClientOriginalExtension(),
            "size" => $file->getSize(),
            "tags" => $tags,
            "thumbnail" => $thumbnail,
        ]);
    }

    public static function create_thumbnails($file, $path)
    {
        $img = Image::make($file);

        $img->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $path1 = '1' . $path;
        Storage::put($path1, $img->encode("jpg", 75), 'public');

        $img->resize(120, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $path2 = '2' . $path;
        Storage::put($path2, $img->encode("jpg", 75), 'public');

        return ["md" =>  $path1,  "sm" => $path2];
    }

    function get_size($dec = 2)
    {
        $size   = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = floor((strlen($this->size) - 1) / 3);

        return sprintf("%.{$dec}f", $this->size / pow(1024, $factor)) . @$size[$factor];
    }

    public function get_thumbnails()
    {
        $urls = [];
        foreach ($this->thumbnail as $k => $t) {
            $urls[$k] = url(Storage::url($t));
        }
        return $urls;
    }



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
