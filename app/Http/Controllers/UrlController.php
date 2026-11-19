<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\UrlService;

use App\Models\Url;

class UrlController extends Controller{

    //redirecionar navegação:
    public function store(Request $request){
        //validando a URL
        $request->validate(
            ["url"=>"required|url|max:2048"],
            [
                "url.required"=>"A URL é obrigatória",
                "url.url"=>"A URL deve ser um link válido (ex: www.meusite.com)",
                "url.max"=>"A URL deve ter no máximo 2048 caracteres"
            ]
        );//fim da requisição do validate

        //Criando um link curto
         $url = UrlService::shorten($request->url);

        //Monta a URL curta baseada no domínio local
        //Retorna a resposta estruturada em JSON (Padrão de API)
        return response()->json([
            'id' => $url->id,
            'original' => $url->original_url,
            'shortened' => url('/' . $url->short_code) //Monta a URL curta
        ], 201); //Status HTTP 201: Criado com Sucesso
        
    }//fim do store

    public function redirect($code){
        //Busca a Url curta pelo código gerado. Se
        //achar, dá um erro 404 automaticamente
        $url = Url::where('short_code', $code)->firstOrFail();

        //Incrementa o contador de cliques no banco
        $url->increment('clicks');

        //Faz o redirecionamento padrão do navegador
        //para o site de destino
        return redirect($url->original_url);
    }//fim do redirect

}//fim da class
