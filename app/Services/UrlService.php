<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\Url;

class UrlService
{
    // Recebe a URL e gera um código curto
    public static function shorten(string $url)
    {
        // Verificando se o código já existe
        do {
            // Gerando uma sequência aleatória de 6 caracteres
            $code = Str::random(6);

        } while (Url::where("short_code", $code)->exists());

        // Inserindo no banco de dados
        return Url::create([
            "original_url" => $url,
            "short_code" => $code
        ]);
    }
}