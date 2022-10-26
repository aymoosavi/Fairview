<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'autoloader.php';

use Fairview\lib\Classes\RSSFeedParser;

$rssFeedUrl = 'https://www.nu.nl/rss/Sport';

try {
    $rssFeedParser = new RSSFeedParser($rssFeedUrl);
    echo json_encode($rssFeedParser->parseRSSFeed());
} catch (Exception) {
    echo json_encode(['error' => 'Error(s) occurred while parsing RSS feed. See logs for the details.']);
}