<?php

namespace App\Projectors;

use App\Events\Articles\ArticleCreated;
use App\Events\Articles\ArticleLexemeAdded;
use App\Models\DictionaryArticle;
use App\Models\Lexeme;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class ArticleProjector extends Projector
{
    public function onArticleCreated(ArticleCreated $articleCreated)
    {
        $article = new DictionaryArticle()->writeable();
        $article->uuid = $articleCreated->uuid;
        $article->language_uuid = $articleCreated->language_uuid;
        $article->short = $articleCreated->short;
        $article->full = $articleCreated->full;
        $article->vocabula = $articleCreated->vocabula;
        $article->transcription = $articleCreated->transcription;
        $article->adaptation = $articleCreated->adaptation;
        $article->save();
    }

    public function onArticleLexemeAdded(ArticleLexemeAdded $articleLexemeAdded)
    {
        $lexeme = new Lexeme()->writeable();
        $lexeme->uuid = $articleLexemeAdded->uuid;
        $lexeme->short = $articleLexemeAdded->short;
        $lexeme->full = $articleLexemeAdded->full;
        $lexeme->group = $articleLexemeAdded->group;
        $lexeme->order = $articleLexemeAdded->order;
        $lexeme->suborder = $articleLexemeAdded->suborder;
        $lexeme->save();
    }
}
