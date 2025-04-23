<?php

declare(strict_types=1);

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Throwable;

class Language extends Model
{
    use SoftDeletes;
    use HasUuids;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'name',
        'autoname',
        'autoname_transcription',
        'flag',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
        ];
    }

    public function isAuthor(?User $user): bool
    {
        return $this->creator_uuid === $user?->uuid;
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_uuid', 'uuid');
    }

    public function grammaticCategories(): HasMany
    {
        return $this->hasMany(GrammaticCategory::class, 'language_uuid', 'uuid');
    }

    public function lexemeBlocksScheme(): HasMany
    {
        return $this->hasMany(LexemeBlocksScheme::class, 'language_uuid', 'uuid');
    }

    public function description(string $title): ?Description
    {
        return $this->hasOne(Description::class, 'language_uuid', 'uuid')
            ->where('title', $title)
            ->first();
    }

    public function dictionary(): HasMany
    {
        return $this->hasMany(DictionaryArticle::class, 'language_uuid', 'uuid');
    }

    public function searchDictionary($search, User|null $user = null): HasMany
    {
        $query = $this->hasMany(DictionaryArticle::class, 'language_uuid', 'uuid')
            ->orderBy('vocabula')
            ->orderBy('adaptation')
        ;

        if (! $user || (! $user->isAdmin() && ! $this->isAuthor($user))) {
            $query->where('is_published', true);
        }

        if (! empty($search)) {
            $uuids = DB::table('articles_full_text_search')
                ->whereRaw('search_vector @@ plainto_tsquery(\'russian\', ?)', [$search])
                ->pluck('uuid');

            $articles = DictionaryArticle::whereIn('dictionary_articles.uuid', $uuids->toArray())
                ->leftJoin('lexemes', 'dictionary_articles.uuid', '=', 'lexemes.article_uuid')
                ->whereIn('lexemes.uuid', $uuids->toArray(), 'or')
                ->pluck('dictionary_articles.uuid as uuid');

            $query->whereIn('dictionary_articles.uuid', $articles->toArray());
        }
        return $query;
    }

    public static function create(User $user, string $name): self
    {
        $language = new self();
        $language->name = $name;
        $language->creator_uuid = $user->uuid;

        return $language;
    }

    public function setAutoname(string $autoname, string $transcription)
    {
        $this->autoname = $autoname;
        $this->autoname_transcription = $transcription;

        return $this;
    }

    public function setDescription(string $key, string $text)
    {
        $description = $this->description($key);
        if (! $description) {
            $description = new Description();
            $description->language_uuid = $this->uuid;
            $description->title = $key;
        }
        $description->description = $text;
        $description->save();
    }

    /**
     * @throws Throwable
     */
    public function createArticle(string $vocabula, ?string $transcription, ?string $adaptation, string $article, array $lexemes = [], bool $public = false): DictionaryArticle
    {
        return DB::transaction(function () use ($public, $vocabula, $transcription, $adaptation, $article, $lexemes) {
            $dArticle = new DictionaryArticle();
            $dArticle->language_uuid = $this->uuid;
            $dArticle->vocabula = $vocabula;
            $dArticle->transcription = $transcription;
            $dArticle->adaptation = $adaptation;
            $dArticle->article = $article;
            $dArticle->is_published = $public;

            $dArticle->save();

            foreach ($lexemes as $order => $lexemesOrder) {
                foreach ($lexemesOrder as $suborder => $lexemeArray) {
                    $lexeme = new Lexeme();
                    $lexeme->language_uuid = $this->uuid;
                    $lexeme->article_uuid = $dArticle->uuid;
                    $lexeme->group = $lexemeArray['group'];
                    $lexeme->order = $order;
                    $lexeme->suborder = $suborder;
                    $lexeme->short = $lexemeArray['short'];
                    $lexeme->full = $lexemeArray['full'];

                    $lexeme->save();
                }
            }

            self::searchRefresh();

            return $dArticle;
        });
    }

    public static function searchRefresh(): void
    {
        DB::statement("REFRESH MATERIALIZED VIEW articles_full_text_search;");
    }
}
