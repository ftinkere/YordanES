<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\CommonHelper;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Throwable;

class Language extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

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

    public function searchDictionary($search): HasMany
    {
        $query = $this->hasMany(DictionaryArticle::class, 'language_uuid', 'uuid');

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
        $language->uuid = CommonHelper::uuid();
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
            $description->uuid = CommonHelper::uuid();
            $description->language_uuid = $this->uuid;
            $description->title = $key;
        }
        $description->description = $text;
        $description->save();
    }

    /**
     * @throws Throwable
     */
    public function createArticle(string $vocabula, string $transcription, string $adaptation, string $article, array $lexemes = []): DictionaryArticle
    {
        DB::beginTransaction();
        try {
            $article = new DictionaryArticle();
            $article->uuid = CommonHelper::uuid();
            $article->language_uuid = $this->uuid;
            $article->vocabula = $vocabula;
            $article->transcription = $transcription;
            $article->adaptation = $adaptation;
            $article->article = $article;

            $article->save();

            foreach ($lexemes as $order => $lexemesOrder) {
                foreach ($lexemesOrder as $suborder => $lexemeArray) {
                    $lexeme = new Lexeme();
                    $lexeme->uuid = CommonHelper::uuid();
                    $lexeme->language_uuid = $this->uuid;
                    $lexeme->article_uuid = $article->uuid;
                    $lexeme->group = $lexemeArray['group'];
                    $lexeme->order = $order;
                    $lexeme->suborder = $suborder;
                    $lexeme->short = $lexemeArray['short'];
                    $lexeme->full = $lexemeArray['full'];

                    $lexeme->save();
                }
            }

            self::searchRefresh();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return $article;
    }

    public static function searchRefresh(): void
    {
        DB::statement("REFRESH MATERIALIZED VIEW articles_full_text_search;");
    }
}
