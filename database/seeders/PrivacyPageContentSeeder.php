<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PrivacyPageContent;

class PrivacyPageContentSeeder extends Seeder
{
    public function run(): void
    {
        $content = <<<'HTML'
<h2>1. Information We Collect</h2>
<p>We collect personal information that you voluntarily provide when you contact us through our website. This may include:</p>
<ul>
    <li>Your name (if provided)</li>
    <li>Your email address</li>
    <li>Any information you include in your message</li>
</ul>

<h2>2. How We Use Your Information</h2>
<p>We use the information you provide to:</p>
<ul>
    <li>Respond to your inquiries</li>
    <li>Communicate with you about your request</li>
    <li>Improve our services and website</li>
</ul>

<h2>3. How We Share Your Information</h2>
<p>We do not sell, rent, or trade your personal information.</p>
<p>We may share your information with trusted service providers (e.g., email hosting or website platforms) only as necessary to operate our business, and they are obligated to keep your information secure.</p>

<h2>4. Data Retention</h2>
<p>We retain your information only as long as necessary to respond to your inquiry and for reasonable business record-keeping purposes, unless a longer retention period is required by law.</p>

<h2>5. Data Security</h2>
<p>We take reasonable measures to protect your information from unauthorized access, disclosure, or misuse. However, no method of transmission over the Internet is completely secure.</p>

<h2>6. Your Rights</h2>
<p>Depending on your location, you may have the right to:</p>
<ul>
    <li>Request access to the personal data we hold about you</li>
    <li>Request correction or deletion of your data</li>
    <li>Withdraw consent to our use of your data</li>
</ul>
<p>To exercise these rights, please <a href="/contact-us">contact us</a>.</p>

<h2>7. Third-Party Services</h2>
<p>Our website may use third-party services (such as hosting providers or analytics tools) that may collect limited technical information (e.g., IP address, browser type). Please refer to their respective privacy policies for more information.</p>

<h2>8. Changes to This Policy</h2>
<p>We may update this Privacy Policy from time to time. Updates will be posted on this page with a revised effective date.</p>

<h2>9. Contact Us</h2>
<p>If you have any questions about this Privacy Policy, you can <a href="/contact-us">contact us</a> for details.</p>
HTML;

        PrivacyPageContent::updateOrCreate(['id' => 1], [
            'header_kicker'     => 'Legal',
            'header_h1_regular' => 'Privacy',
            'header_h1_em'      => 'Policy',
            'last_updated'      => 'Effective Date: April 1, 2026',
            'content'           => $content,
            'seo_title'         => 'Privacy Policy | Kevin Thompson Ph.D. Consulting',
            'seo_description'   => 'Learn how Kevin Thompson Ph.D. Consulting collects, uses, and protects visitor information across our consulting, training, and content services.',
            'seo_keywords'      => 'privacy policy, data protection, GDPR, CCPA, Kevin Thompson Ph.D., agile consulting',
        ]);
    }
}
