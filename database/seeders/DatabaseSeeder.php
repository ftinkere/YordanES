<?php

namespace Database\Seeders;

use App\Aggregates\LanguageAggregate;
use App\Aggregates\UserAggregate;
use App\Aggregates\UserRepositoryAggregate;
use App\Helpers\CommonHelper;
use App\Models\DictionaryArticle;
use App\Models\Language;
use App\Models\Lexeme;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! User::where('username', 'admin')->exists()) {
            $admin = new User;
            $admin->uuid = CommonHelper::uuid();
            $admin->username = 'admin';
            $admin->name = 'Админ';
            $admin->email = 'admin@yordan.ru';
            $admin->email_verified_at = Carbon::now();
            $admin->password_hash = Hash::make('password');
            $admin->save();
        }

        $language = Language::where('name', 'Админский')->first();
        if (! $language) {
            $language = new Language;
            $language->uuid = CommonHelper::uuid();
            $language->creator_uuid = User::where('username', 'admin')->first()->uuid;
            $language->name = 'Админский';
            $language->autoname = 'Rootell';
            $language->autoname_transcription = 'rutel';
            $language->is_published = true;
            $language->save();
        }

        DB::beginTransaction();
        try {
            for ($i = 0; $i < 100; $i++) {
                $article = new DictionaryArticle;
                $article->uuid = CommonHelper::uuid();
                $article->language_uuid = $language->uuid;
                $article->article = Str::random(200);
                $article->vocabula = Str::random(8);
                $article->save();

                for ($j = 0; $j < 10; $j++) {
                    $lexeme = new Lexeme;
                    $lexeme->uuid = CommonHelper::uuid();
                    $lexeme->language_uuid = $language->uuid;
                    $lexeme->article_uuid = $article->uuid;
                    $lexeme->short = Str::random(20);
                    $lexeme->full = Str::random(200);
                    $lexeme->group = (int)($j / 3) + 1;
                    $lexeme->order = (int)($j / 2);
                    $lexeme->suborder = (int)($j % 2);
                    $lexeme->save();
                }
            }

            DB::statement("REFRESH MATERIALIZED VIEW articles_full_text_search;");

            Db::commit();
        } catch (Exception $e) {
            Db::rollBack();
            throw $e;
        }
    }
}
