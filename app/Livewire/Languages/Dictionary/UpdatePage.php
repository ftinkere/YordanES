<?php

namespace App\Livewire\Languages\Dictionary;

use App\Models\DictionaryArticle;
use App\Models\File;
use App\Models\GrammaticPosSet;
use App\Models\GrammaticSet;
use App\Models\Lexeme;
use App\Models\Tag;
use App\Services\FileService;
use Arr;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\LivewireFilepond\WithFilePond;
use Str;

class UpdatePage extends Component
{
    use WithFilePond;

    #[Locked]
    public DictionaryArticle $dictionaryArticle;

    #[Validate('required')]
    public string $vocabula;
    public ?string $transcription = null;
    public ?string $adaptation = null;

    public string $article;

    #[Validate('required|boolean')]
    public bool $public = true;

    public array $lexemes;

    public array $files;

    public string $tag;
    public string $lexeme;

    protected function rules(): array
    {
        return [
            'files.*' => 'file|image|max:4096',
        ];
    }

    public function mount(DictionaryArticle $article)
    {
        $this->dictionaryArticle = $article;

        $this->vocabula = $article->vocabula;
        $this->transcription = $article->transcription;
        $this->adaptation = $article->adaptation;

        $this->article = $article->article;

        $this->public = $article->is_published;

        foreach ($article->lexemes as $lexeme) {
            $this->lexemes[$lexeme->order][$lexeme->suborder] = [
                'uuid' => $lexeme->uuid,
                'group' => $lexeme->group,
                'short' => $lexeme->short,
                'full' => $lexeme->full,
                'tags' => $lexeme->tags->toArray(),
                'pos_uuid' => $lexeme->partOfSpeech?->uuid,
                'gramset' => $lexeme->gramSet()->pluck('value_id')->toArray(),
                'gramset_variable' => $lexeme->gramSet()->where('is_changeable', true)->pluck('value_id')->toArray(),
                'gramset_show' => $lexeme->gramSet()
                    ->leftJoin('grammatic_values', 'grammatic_set.value_id', '=', 'grammatic_values.uuid')
                    ->leftJoin('grammatic_categories', 'grammatic_values.category_id', '=', 'grammatic_categories.uuid')
                    ->orderBy('grammatic_categories.order')
                    ->orderBy('grammatic_values.order')
                    ->with('value.category')
                    ->get()
                    ->map(function ($set) {
                    return [
                        'is_changeable' => $set->is_changeable,
                        'value' => [
                            'name' => $set->value->name,
                            'code' => $set->value->code,
                        ],
                        'category' => [
                            'name' => $set->value->category->name,
                            'code' => $set->value->category->code,
                        ],
                    ];
                }),
            ];
        }
    }

    public function updateArticle(FileService $fileService)
    {
        $this->validate();

        $article = DictionaryArticle::findOrFail($this->dictionaryArticle->uuid);

        $article->update([
            'vocabula' => $this->vocabula,
            'transcription' => $this->transcription,
            'adaptation' => $this->adaptation,
            'article' => $this->article,
            'is_published' => $this->public,
        ]);

        foreach ($this->files as $file) {
            /** @var TemporaryUploadedFile $file */
            [$width, $height] = getimagesize($file->getRealPath());
            $path = $fileService->uploadImageToArticle($file, $article);
            File::create([
                'parent_id' => $article->uuid,
                'parent_type' => DictionaryArticle::class,
                'path' => $path,
                'width' => $width,
                'height' => $height,
            ]);
        }

        foreach ($this->lexemes as $order => $lexemesOrder) {
            foreach ($lexemesOrder as $suborder => $lexemeArray) {
                if (isset($lexemeArray['uuid'])) {
                    $lexeme = Lexeme::findOrFail($lexemeArray['uuid']);

                    if (empty($lexemeArray['short']) && empty($lexemeArray['full'])) {
                        $lexeme->delete();
                    } else {
                        $lexeme->update([
                            'group' => $lexemeArray['group'],
                            'short' => $lexemeArray['short'],
                            'full' => $lexemeArray['full'],
                        ]);
                    }
                } elseif (! empty($lexemeArray['short']) || ! empty($lexemeArray['full'])) {
                    $lexeme = Lexeme::create([
                        'language_uuid' => $this->dictionaryArticle->language_uuid,
                        'article_uuid' => $this->dictionaryArticle->uuid,
                        'group' => $lexemeArray['group'],
                        'order' => $order,
                        'suborder' => $suborder,
                        'short' => $lexemeArray['short'],
                        'full' => $lexemeArray['full'],
                    ]);
                }

                foreach ($lexeme->tags as $tag) {
                    if (! in_array($tag->uuid, Arr::pluck($lexemeArray['tags'], 'uuid'))) {
                        $tag->delete();
                    }
                }
                foreach ($lexemeArray['tags'] as $tagArr) {
                    $tag = Tag::find($tagArr['uuid'] ?? null);
                    if (! $tag) {
                        $tag = Tag::create([
                            'name' => $tagArr['name'],
                            'color' => 'auto',
                            'taggable_id' => $lexeme->uuid,
                            'taggable_type' => Lexeme::class,
                        ]);
                    }
                }

                if ($lexemeArray['pos_uuid']) {
                    if ($lexeme->partOfSpeech && $lexemeArray['pos_uuid'] != $lexeme->partOfSpeech->uuid) {
                        GrammaticPosSet::where('parent_id', $lexeme->uuid)
                            ->where('parent_type', Lexeme::class)
                            ->delete();
                    }
                    if (! $lexeme->partOfSpeech || $lexeme['pos_uuid'] != $lexeme->partOfSpeech->uuid) {
                        $posSet = new GrammaticPosSet;
                        $posSet->parent_id = $lexeme->uuid;
                        $posSet->parent_type = Lexeme::class;
                        $posSet->pos_id = $lexemeArray['pos_uuid'];
                        $posSet->save();
                    }
                }

                $set = $lexeme->gramSet;
                foreach ($set as $setValue) {
                    if (! in_array($setValue->value_id, $lexemeArray['gramset'])) {
                        $setValue->delete();
                    }
                }
                foreach ($lexemeArray['gramset'] as $value) {
                    if (! $set->where('value_id', $value)->first()) {
                        $setValue = new GrammaticSet;
                        $setValue->parent_id = $lexeme->uuid;
                        $setValue->parent_type = Lexeme::class;
                        $setValue->value_id = $value;
                        $setValue->is_changeable = in_array($value, $lexemeArray['gramset_variable']);
                        $setValue->save();
                    } else {
                        $setValue = $set->where('value_id', $value)->first();
                        $setValue->is_changeable = in_array($value, $lexemeArray['gramset_variable']);
                        $setValue->save();
                    }
                }
            }
        }

        return redirect()->route('languages.dictionary.view', ['language' => $this->dictionaryArticle->language, 'article' => $this->dictionaryArticle]);
    }

    public function validateUploadedFile()
    {
        $this->validateOnly('files');

        return true;
    }

    public function deleteImage($uuid)
    {
        $file = File::findOrFail($uuid);
        $path = Str::replaceFirst('/storage', '', $file->path);
        Storage::disk('public')->delete($path);
        $file->delete();

        $this->dispatch('deleted');
    }

    public function render()
    {
        return view('livewire.languages.dictionary.update-page')
            ->layout('components.layouts.language', ['language' => $this->dictionaryArticle->language]);
    }
}
