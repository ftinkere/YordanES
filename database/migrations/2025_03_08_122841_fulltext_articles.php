<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE MATERIALIZED VIEW articles_full_text_search AS
            SELECT uuid as article_uuid, dictionary_articles.uuid as uuid, 'dictionary_articles' AS source, to_tsvector('russian', dictionary_articles.vocabula || ' ' || COALESCE(dictionary_articles.adaptation, '') || ' ' || COALESCE(dictionary_articles.transcription, '') || ' ' || dictionary_articles.article) AS search_vector
            FROM dictionary_articles
            UNION ALL
            SELECT dictionary_articles.uuid as article_uuid, lexemes.uuid, 'lexemes' AS source, to_tsvector('russian', lexemes.short || ' ' || lexemes.full) AS search_vector
            FROM lexemes
            LEFT JOIN dictionary_articles ON lexemes.article_uuid = dictionary_articles.uuid;
        ");

        DB::statement("CREATE INDEX idx_articles_full_text_search ON articles_full_text_search USING GIN (search_vector);");
    }

    public function down(): void
    {
        DB::statement("DROP INDEX IF EXISTS idx_articles_full_text_searchON;");
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS articles_full_text_search;");
    }
};
