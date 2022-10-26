<?php

namespace Fairview\lib\Classes;

use Fairview\lib\Exceptions\ImageUrlNotProvidedException;
use Fairview\lib\Exceptions\RSSFeedNotAvailableException;
use Fairview\lib\Exceptions\RSSFeedURLNotProvidedException;

class RSSFeedParser
{
    private string $rssFeedUrl;

    public function __construct(string $rssFeedUrl)
    {
        $this->rssFeedUrl = $rssFeedUrl;
    }

    /**
     * @throws RSSFeedURLNotProvidedException
     */
    public function parseRSSFeed(): array
    {
        if (!$this->rssFeedUrl) {
            throw new RSSFeedURLNotProvidedException();
        }

        return $this->extractNewsItems();
    }

    private function extractNewsItems(): array
    {
        try {
            $contents = $this->readRSSFeed();
            $rssXML = new \SimpleXMLElement($contents);
            $newsItems = [];

            foreach ($rssXML->channel->item as $item) {
                $parsedItem = $this->parseRSSItem($item);
                if (!$parsedItem) {
                    continue;
                }
                $newsItems[] = $parsedItem;
            }

            return $newsItems;

        } catch (\Exception $e) {
            $this->logError('Exception: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * @throws RSSFeedNotAvailableException
     */
    private function readRSSFeed(): string
    {
        $contents = @file_get_contents($this->rssFeedUrl);

        if (!$contents) {
            throw new RSSFeedNotAvailableException();
        }

        return $contents;
    }

    private function parseRSSItem(\SimpleXMLElement $item): array
    {
        try {
            if (!$item->title || !$item->enclosure) {
                return [];
            }

            return [
                'title' => (string) $item->title,
                'image' => $this->processImage($item->enclosure['url']),
            ];
        } catch (ImageUrlNotProvidedException $e) {
            $this->logError($e->getMessage() . ': ' . $item->asXML());
            return [];
        }
    }

    /**
     * @throws ImageUrlNotProvidedException
     */
    private function processImage(string $url): string
    {
        $imageUploader = new ImageUploader($url);
        return $imageUploader->upload();
    }

    private function logError(string $error): void
    {
        error_log(
            date('Y-m-d H:i:s') . ' ' . $error . PHP_EOL,
            3,
            $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'fairview' . DIRECTORY_SEPARATOR . Config::$LOG_FILE_PATH
        );
    }
}