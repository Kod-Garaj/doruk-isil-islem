<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class BildirimTurleri extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'bildirim_turleri';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(["*"])
        ->setDescriptionForEvent(fn (string $eventName) => $this->aciklamaOlustur($eventName));
        // Chain fluent methods for configuration options
    }

    public function aciklamaOlustur($eventName)
    {
        $aciklama = "";
        switch ($eventName) {
            case "created":
                $aciklama = "Bildirim türü oluşturuldu.";
                break;
            case "updated":
                $aciklama = "Bildirim türü güncellendi.";
                break;
            case "deleted":
                $aciklama = "Bildirim türü silindi.";
                break;
        }
        return $aciklama;
    }
}
