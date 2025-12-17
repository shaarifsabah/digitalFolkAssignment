<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = ['locale_id', 'key', 'content'];

    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopeFilter($query, $filters)
    {
        if (isset($filters['tag'])) {
            $query->whereHas('tags', function ($q) use ($filters) {
                $q->where('name', $filters['tag']);
            });
        }

        if (isset($filters['key'])) {
            $query->where('key', 'like', '%' . $filters['key'] . '%');
        }

        if (isset($filters['content'])) {
            $query->where('content', 'like', '%' . $filters['content'] . '%');
        }

        return $query;
    }
}
