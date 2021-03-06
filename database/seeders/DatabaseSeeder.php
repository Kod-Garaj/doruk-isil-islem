<?php

namespace Database\Seeders;

use App\Models\BildirimTurleri;
use App\Models\Firmalar;
use App\Models\IslemTurleri;
use App\Models\Malzemeler;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $mesajlar = [];

        $kullaniciTabloAdi = (new User())->getTable();
        if (!Schema::hasColumn($kullaniciTabloAdi, "jwt")) {
            Schema::table($kullaniciTabloAdi, function ($table) {
                $table->text("jwt")->after("password")->nullable();
            });

            $mesajlar[] = "Kullanıcıların JWT bilgileri eklendi > " . date('Y-m-d H:i:s');
        }

        $firmaTabloAdi = (new Firmalar())->getTable();
        if (!Schema::hasColumn($firmaTabloAdi, "deleted_at")) {
            Schema::table($firmaTabloAdi, function ($table) {
                $table->softDeletes();
            });

            $mesajlar[] = "Firmalar tablosuna deleted_at sütunu eklendi > " . date('Y-m-d H:i:s');
        }

        $islemTurleriTabloAdi = (new IslemTurleri())->getTable();
        if (!Schema::hasColumn($islemTurleriTabloAdi, "deleted_at")) {
            Schema::table($islemTurleriTabloAdi, function ($table) {
                $table->softDeletes();
            });

            $mesajlar[] = "IslemTurleri tablosuna deleted_at sütunu eklendi > " . date('Y-m-d H:i:s');
        }

        $malzemeTabloAdi = (new Malzemeler())->getTable();
        if (!Schema::hasColumn($malzemeTabloAdi, "deleted_at")) {
            Schema::table($malzemeTabloAdi, function ($table) {
                $table->softDeletes();
            });

            $mesajlar[] = "Malzemeler tablosun deleted_at sütunu eklendi > " . date('Y-m-d H:i:s');
        }

        if (!Schema::hasTable(config('activitylog.table_name'))) {
            $baglantiBilgisi = config('activitylog.database_connection') ?? null;
            $tabloAdi = config('activitylog.table_name');
            Schema::connection($baglantiBilgisi)->create($tabloAdi, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('log_name')->nullable();
                $table->text('description');
                $table->nullableMorphs('subject', 'subject');
                $table->nullableMorphs('causer', 'causer');
                $table->json('properties')->nullable();
                $table->timestamps();
                $table->index('log_name');
            });
            Schema::connection($baglantiBilgisi)->table($tabloAdi, function (Blueprint $table) {
                $table->string('event')->nullable()->after('subject_type');
            });
            Schema::connection($baglantiBilgisi)->table($tabloAdi, function (Blueprint $table) {
                $table->uuid('batch_uuid')->nullable()->after('properties');
            });

            $mesajlar[] = 'Activity Log tablosu oluşturuldu > ' . date('Y-m-d H:i:s');
        }

        // Bildirim tablosu oluşturulması
        if (!Schema::hasTable("bildirimler")) {
            Schema::create("bildirimler", function (Blueprint $table) {
                $table->bigIncrements('id')->comment("Bildirimlerin tutulduğu tablo");
                $table->integer('btid')->comment("Bildirim türü idsi (bildirim_turleri tablosundan)");
                $table->integer('kullaniciId')->comment("Bildirimin alındığı kullanıcı idsi (users tablosundan)");
                $table->string('baslik', 100)->comment("Bildirimin başlığı");
                $table->text('icerik')->comment("Bildirimin içeriği");
                $table->text('json')->nullable()->comment("Bildirimin ekstra verileri (Örn: Bildirime tıklandığında gösterilecek veriler)");
                $table->timestamps();
            });

            $mesajlar[] = 'Bildirimler tablosu oluşturuldu > ' . date('Y-m-d H:i:s');
        }

        // Bildirim türleri tablosu oluşturulması
        if (!Schema::hasTable("bildirim_turleri")) {
            Schema::create("bildirim_turleri", function (Blueprint $table) {
                $table->increments('id')->comment("Bildirim türlerin tutulduğu tablo");
                $table->string('ad', 100)->comment("Bildirim türünün adı");
                $table->string('kod', 100)->nullable()->comment("Bildirim türünün kodu");
                $table->text('json')->nullable()->comment("Bildirim türünün ekstra verileri");
                $table->timestamps();
                $table->softDeletes();
            });

            $bildirimTurleri = [
                [
                    // Sipariş tamamlandı, başlandı vs.
                    "ad" => "Sipariş Bildirimi",
                    "kod" => "SIPARIS_BILDIRIMI",
                    "json" => json_encode([
                        "renk" => "primary",
                    ]),
                ],
                [
                    // Form tamamlandı, başlandı vs.
                    "ad" => "Form Bildirimi",
                    "kod" => "FORM_BILDIRIMI",
                    "json" => json_encode([
                        "renk" => "warning",
                    ]),
                ],
                [
                    // İşlem tekrarı vs.
                    "ad" => "İşlem Bildirimi",
                    "kod" => "ISLEM_BILDIRIMI",
                    "json" => json_encode([
                        "renk" => "info",
                    ]),
                ],
                [
                    // İşlem durumu değiştiğinde bilgilendirme
                    "ad" => "İşlem Durumu Bildirimi",
                    "kod" => "ISLEM_DURUMU_BILDIRIMI",
                    "json" => json_encode([
                        "renk" => "danger",
                    ]),
                ],
                [
                    // Genel bildirimler
                    "ad" => "Genel Bildirim",
                    "kod" => "GENEL_BILDIRIM",
                    "json" => json_encode([
                        "renk" => "secondary",
                    ]),
                ],
            ];

            foreach ($bildirimTurleri as $bildirimTur)
            {
                $bildirim = new BildirimTurleri();
                $bildirim->ad = $bildirimTur['ad'];
                $bildirim->kod = $bildirimTur['kod'];
                $bildirim->json = $bildirimTur['json'];
                $bildirim->save();
            }

            $mesajlar[] = 'Bildirim türleri tablosu oluşturuldu > ' . date('Y-m-d H:i:s');
        }

        // Okunmamış bildirimler tablosu oluşturulması
        if (!Schema::hasTable("okunmamis_bildirimler")) {
            Schema::create("okunmamis_bildirimler", function (Blueprint $table) {
                $table->bigIncrements('id')->comment("Okunmamış bildirimlerin tutulduğu tablo (kolay silebilmek için eklendi)");
                $table->bigInteger('bildirimId')->comment("Okunmamış bildirimlerin idsi (bildirimler tablosundan)");
                $table->integer('kullaniciId')->comment("Okunmamış bildirimlerin alındığı kullanıcı idsi (users tablosundan)");
            });

            $mesajlar[] = 'Okunmamış bildirimler tablosu oluşturuldu > ' . date('Y-m-d H:i:s');
        }


        $mesajlar[] = 'Veritabanı güncellendi > ' . date('Y-m-d H:i:s');

        return implode("<br />", $mesajlar);
    }
}
