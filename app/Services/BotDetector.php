<?php

namespace App\Services;

use Jenssegers\Agent\Agent;

/**
 * Classifies a request's User-Agent as human or automated traffic.
 *
 * Detection is purely User-Agent based (no external IP intelligence) and runs
 * on every tracked page view. The result drives the "Human vs Filtered Bot
 * Traffic" split on the visitor analytics dashboard.
 */
class BotDetector
{
    /**
     * Substrings that, when present in a User-Agent, identify a crawler/spider.
     * Kept lowercase — matching is case-insensitive.
     */
    private const CRAWLER_SIGNATURES = [
        'bot', 'crawl', 'spider', 'slurp', 'mediapartners', 'feedfetcher',
        'facebookexternalhit', 'embedly', 'quora link preview', 'pinterest',
        'developers.google.com', 'google-inspectiontool', 'apis-google',
        'ahrefs', 'semrush', 'mj12', 'dotbot', 'petalbot', 'bytespider',
        'gptbot', 'ccbot', 'claudebot', 'anthropic', 'perplexitybot',
        'amazonbot', 'applebot', 'yandex', 'baiduspider', 'sogou',
        'archive.org_bot', 'ia_archiver', 'duckduckbot', 'censys', 'masscan',
    ];

    /**
     * Substrings that identify scripted HTTP clients / headless tooling.
     * These are not "crawlers" in the SEO sense but are still non-human.
     */
    private const HTTP_CLIENT_SIGNATURES = [
        'curl', 'wget', 'python-requests', 'python-urllib', 'aiohttp', 'httpx',
        'go-http-client', 'java/', 'okhttp', 'libwww-perl', 'guzzlehttp',
        'axios', 'node-fetch', 'phantomjs', 'headlesschrome', 'puppeteer',
        'playwright', 'selenium', 'scrapy', 'httpclient', 'postmanruntime',
        'insomnia', 'restsharp', 'apache-httpclient', 'winhttp', 'zgrab',
    ];

    /**
     * Classify a User-Agent string.
     *
     * @return array{is_bot: bool, reason: string|null}
     */
    public static function classify(?string $userAgent): array
    {
        $ua = trim((string) $userAgent);

        // No User-Agent at all — overwhelmingly scanners / scripts.
        if ($ua === '') {
            return ['is_bot' => true, 'reason' => 'empty-user-agent'];
        }

        $haystack = strtolower($ua);

        foreach (self::CRAWLER_SIGNATURES as $needle) {
            if (str_contains($haystack, $needle)) {
                return ['is_bot' => true, 'reason' => 'crawler-signature'];
            }
        }

        foreach (self::HTTP_CLIENT_SIGNATURES as $needle) {
            if (str_contains($haystack, $needle)) {
                return ['is_bot' => true, 'reason' => 'http-client'];
            }
        }

        // Fall back to jenssegers/agent's own robot list for anything we missed.
        $agent = new Agent();
        $agent->setUserAgent($ua);
        if ($agent->isRobot()) {
            return ['is_bot' => true, 'reason' => 'crawler-signature'];
        }

        return ['is_bot' => false, 'reason' => null];
    }

    public static function isBot(?string $userAgent): bool
    {
        return self::classify($userAgent)['is_bot'];
    }
}
