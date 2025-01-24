<?php

namespace App\Interfaces;

interface NewsApiInterface
{
    public function fetchNewsArticles(): array;
}
