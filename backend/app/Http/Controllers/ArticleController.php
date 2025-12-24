<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        return Article::paginate(10);
    }

    public function show($id)
    {
        return Article::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        // SECURITY CHECK
        if ($request->header('X-API-Key') !== env('API_SECRET')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $article = Article::findOrFail($id);
        $article->update($request->all());
        return $article;
    }
}
