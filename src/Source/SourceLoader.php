<?php declare(strict_types=1);

namespace App\Source;

use GuzzleHttp\Client;
use Symfony\Contracts\Cache\CacheInterface;

class SourceLoader implements SourceLoaderInterface
{
    /** @var CacheInterface $cache */
    protected $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function load(string $url): string
    {
        $hash = md5($url);

        $cacheItem = $this->cache->getItem(self::ADAPTER_KEY);

        if (!$cacheItem->isHit()) {
            $cacheList = [];
        } else {
            $cacheList = $cacheItem->get();
        }

        if (array_key_exists($hash, $cacheList)) {
            return $cacheList[$hash];
        }

        $content = $this->loadContent($url);

        $cacheList[$hash] = $content;

        $cacheItem->set($cacheList);
        $this->cache->save($cacheItem);

        return $content;
    }

    protected function loadContent(string $url): string
    {
        $client = new Client();
        $response = $client->get($url);

        return $response->getBody()->getContents();
    }
}