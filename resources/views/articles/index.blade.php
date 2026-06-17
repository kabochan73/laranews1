<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zenn Laravel記事まとめ</title>
    <link rel="stylesheet" href="/css/articles.css">
</head>
<body>
    <header>
        <h1>Zenn Laravel記事まとめ</h1>
    </header>
    <main>
        @forelse ($articles as $article)
            <div class="article-card">
                <div class="emoji">{{ $article->emoji ?? '📝' }}</div>
                <div class="content">
                    <div class="title">
                        <a href="{{ $article->url }}" target="_blank" rel="noopener">
                            {{ $article->title }}
                        </a>
                    </div>
                    <div class="meta">
                        <span>{{ $article->author_name }}</span>
                        <span>{{ $article->published_at->format('Y/m/d') }}</span>
                        <span class="liked">♥ {{ $article->liked_count }}</span>
                    </div>
                </div>
            </div>
        @empty
            <p>記事がありません。<code>php artisan zenn:fetch</code> を実行してください。</p>
        @endforelse

        {{ $articles->links() }}
    </main>
</body>
</html>
