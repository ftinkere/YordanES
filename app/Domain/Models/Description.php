<?php

namespace App\Domain\Models;

use App\Domain\Vos\RichText;
use Ramsey\Uuid\UuidInterface;

class Description {
    public UuidInterface $id;
    public RichText $content;
    public UuidInterface $descriptedId;
    public string $descriptedClass;
}