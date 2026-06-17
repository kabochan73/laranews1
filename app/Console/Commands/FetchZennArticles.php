<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

#[Signature('zenn:fetch')]
#[Description('Zennからlaravelタグの記事を10件取得して保存する')]
class FetchZennArticles extends Command
{
    public function handle(): int
    {
        $this->info('Zennから記事を取得中...');

        $response = Http::get('https://zenn.dev/api/articles', [
            'topicname' => 'laravel',
            'order' => 'latest',
            'count' => 30,
        ]);

        if ($response->failed()) {
            $this->error('Zenn APIの取得に失敗しました。');
            return Command::FAILURE;
        }

        $articles = $response->json('articles', []);
        $saved = 0;

        foreach ($articles as $data) {
            Article::updateOrCreate(
                ['zenn_id' => (string) $data['id']],
                [
                    'title'           => $data['title'],
                    'slug'            => $data['slug'],
                    'emoji'           => $data['emoji'] ?? null,
                    'author_name'     => $data['user']['name'],
                    'author_username' => $data['user']['username'],
                    'liked_count'     => $data['liked_count'] ?? 0,
                    'published_at'    => $data['published_at'],
                ]
            );
            $saved++;
        }

        $this->info("{$saved}件の記事を保存しました。");

        $total = Article::count();
        if ($total > 50) {
            $deleteCount = $total - 50;
            Article::oldest('published_at')->take($deleteCount)->delete();
            $this->info("古い記事を{$deleteCount}件削除しました。");
        }

        return Command::SUCCESS;
    }
}
