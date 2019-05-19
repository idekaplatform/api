<?php

namespace App\Entity;

interface PublishableInterface
{
    public function publish(): self;

    public function unpublish(): self;

    public function isPublished(): bool;
}