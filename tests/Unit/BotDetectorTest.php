<?php

namespace Tests\Unit;

use App\Services\BotDetector;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class BotDetectorTest extends TestCase
{
    #[DataProvider('botUserAgents')]
    public function test_detects_bots(string $ua, string $expectedReason): void
    {
        $result = BotDetector::classify($ua);

        $this->assertTrue($result['is_bot'], "Expected bot for: {$ua}");
        $this->assertEquals($expectedReason, $result['reason']);
    }

    public static function botUserAgents(): array
    {
        return [
            'empty'        => ['', 'empty-user-agent'],
            'whitespace'   => ['   ', 'empty-user-agent'],
            'googlebot'    => ['Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'crawler-signature'],
            'bingbot'      => ['Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)', 'crawler-signature'],
            'facebook'     => ['facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)', 'crawler-signature'],
            'ahrefs'       => ['Mozilla/5.0 (compatible; AhrefsBot/7.0; +http://ahrefs.com/robot/)', 'crawler-signature'],
            'gptbot'       => ['Mozilla/5.0 AppleWebKit/537.36 (compatible; GPTBot/1.0; +https://openai.com/gptbot)', 'crawler-signature'],
            'curl'         => ['curl/8.1.2', 'http-client'],
            'wget'         => ['Wget/1.21.3', 'http-client'],
            'python'       => ['python-requests/2.31.0', 'http-client'],
            'go'           => ['Go-http-client/2.0', 'http-client'],
            'headless'     => ['Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 HeadlessChrome/120.0.0.0', 'http-client'],
            'postman'      => ['PostmanRuntime/7.36.0', 'http-client'],
        ];
    }

    #[DataProvider('humanUserAgents')]
    public function test_allows_real_browsers(string $ua): void
    {
        $result = BotDetector::classify($ua);

        $this->assertFalse($result['is_bot'], "Expected human for: {$ua}");
        $this->assertNull($result['reason']);
    }

    public static function humanUserAgents(): array
    {
        return [
            'chrome-win'   => ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'],
            'safari-iphone' => ['Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1'],
            'firefox-mac'  => ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:121.0) Gecko/20100101 Firefox/121.0'],
            'edge-win'     => ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36 Edg/120.0.0.0'],
            'ipad-safari'  => ['Mozilla/5.0 (iPad; CPU OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1'],
        ];
    }

    public function test_is_bot_helper_matches_classify(): void
    {
        $this->assertTrue(BotDetector::isBot('curl/8.0'));
        $this->assertFalse(BotDetector::isBot('Mozilla/5.0 (Windows NT 10.0) Chrome/120.0 Safari/537.36'));
    }
}
