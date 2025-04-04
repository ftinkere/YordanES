<?php

namespace App\Livewire\Languages\Dictionary;

use App\Models\DictionaryArticle;
use App\Models\File;
use App\Models\Lexeme;
use App\Services\FileService;
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

    public array $lexemes;

    public array $files;

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

        foreach ($article->lexemes as $lexeme) {
            $this->lexemes[$lexeme->order][$lexeme->suborder] = [
                'uuid' => $lexeme->uuid,
                'group' => $lexeme->group,
                'short' => $lexeme->short,
                'full' => $lexeme->full,
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
                    $lexeme->update([
                        'group' => $lexemeArray['group'],
                        'short' => $lexemeArray['short'],
                        'full' => $lexemeArray['full'],
                    ]);
                } else {
                    Lexeme::create([
                        'language_uuid' => $this->dictionaryArticle->language_uuid,
                        'article_uuid' => $this->dictionaryArticle->uuid,
                        'group' => $lexemeArray['group'],
                        'order' => $order,
                        'suborder' => $suborder,
                        'short' => $lexemeArray['short'],
                        'full' => $lexemeArray['full'],
                    ]);
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
