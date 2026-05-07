<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PrivacyPageContent;

class PrivacyPageContentSeeder extends Seeder
{
    public function run(): void
    {
        $content = <<<'HTML'
<p>This Privacy Policy describes how <strong>Kevin Thompson Ph.D. Consulting</strong> ("we," "us," or "our") collects, uses, and safeguards information when you visit <a href="https://kevinthompsonphd.com">kevinthompsonphd.com</a> (the "Site") or interact with our consulting and training services.</p>
<p>We respect your privacy and are committed to handling your information responsibly. By using the Site, you agree to the practices described below.</p>

<h2>1. Information We Collect</h2>
<h3>a. Information You Provide Directly</h3>
<p>When you fill in the <strong>Contact Form</strong>, request a consulting engagement, or enroll in a training class, we collect the information you submit, which typically includes:</p>
<ul>
    <li>Your full name</li>
    <li>Email address</li>
    <li>Subject line and message body</li>
    <li>Any additional details you choose to include (company, role, project context, etc.)</li>
</ul>

<h3>b. Information Collected Automatically</h3>
<p>When you browse the Site, we automatically log technical and usage information through our internal <strong>Visitor Analytics</strong> system, including:</p>
<ul>
    <li>IP address and approximate geolocation</li>
    <li>Browser type, operating system, and device characteristics</li>
    <li>Pages visited, referring URL, and time spent on each page</li>
    <li>Date and time of access</li>
</ul>

<h3>c. Cookies &amp; Similar Technologies</h3>
<p>We use a small number of cookies that are essential to the Site's operation, including a session cookie to maintain navigation state and a CSRF token cookie to protect form submissions. If enabled by the site administrator, third-party analytics services such as <strong>Google Analytics 4</strong> or <strong>Google Tag Manager</strong> may also set cookies to provide aggregated visitor statistics. You can disable cookies in your browser settings, but some features of the Site may not function correctly.</p>

<h2>2. How We Use Your Information</h2>
<p>We use the information we collect for the following purposes:</p>
<ul>
    <li>Responding to inquiries submitted through the Contact Form</li>
    <li>Delivering consulting, training, and advisory services that you request</li>
    <li>Sending follow-up correspondence related to your inquiry or engagement</li>
    <li>Improving the Site's content, performance, and usability</li>
    <li>Understanding aggregate visitor trends and the effectiveness of our materials</li>
    <li>Protecting the Site against fraud, abuse, and unauthorized access</li>
    <li>Complying with applicable legal obligations</li>
</ul>

<h2>3. Legal Basis for Processing (EEA / UK Visitors)</h2>
<p>If you are located in the European Economic Area or the United Kingdom, we process your personal data under one or more of the following legal bases: your <strong>consent</strong>, the necessity to <strong>perform a contract</strong> with you, our <strong>legitimate interests</strong> in operating and improving our services, or to <strong>comply with a legal obligation</strong>.</p>

<h2>4. How We Share Information</h2>
<p>We do not sell, rent, or trade your personal information. We may share limited information with:</p>
<ul>
    <li><strong>Service providers</strong> who support our operations — such as web hosting, email delivery, and analytics — strictly to perform services on our behalf</li>
    <li><strong>Professional advisors</strong> (legal, accounting, insurance) when reasonably necessary</li>
    <li><strong>Authorities</strong>, where required by law, subpoena, or to protect our legal rights</li>
</ul>

<h2>5. Data Retention</h2>
<p>We retain inquiry data for as long as needed to respond to your request and to maintain a record of our correspondence, typically up to <strong>24 months</strong> from your last interaction. Aggregate analytics data may be retained longer in anonymized form.</p>

<h2>6. Your Rights</h2>
<p>Depending on your jurisdiction, you may have the right to:</p>
<ul>
    <li>Access the personal information we hold about you</li>
    <li>Request correction of inaccurate information</li>
    <li>Request deletion of your personal information</li>
    <li>Object to or restrict certain processing activities</li>
    <li>Withdraw consent where processing is based on consent</li>
    <li>Lodge a complaint with your local data protection authority</li>
</ul>
<p>To exercise any of these rights, please reach out through our <a href="/contact-us">Contact Us</a> page. We will respond within a reasonable timeframe.</p>

<h2>7. Data Security</h2>
<p>We employ industry-standard technical and organizational measures — including TLS encryption in transit, hashed credentials at rest, and restricted administrative access — to protect your information. No method of transmission over the Internet is, however, 100% secure, and we cannot guarantee absolute security.</p>

<h2>8. Children's Privacy</h2>
<p>The Site is intended for a professional audience and is not directed to children under 16. We do not knowingly collect personal information from children. If you believe a child has provided us with personal data, please contact us so we can delete it.</p>

<h2>9. International Transfers</h2>
<p>Our servers and service providers may be located outside your country of residence. By using the Site, you understand that your information may be transferred to and processed in jurisdictions whose data protection laws may differ from those of your home country.</p>

<h2>10. Third-Party Links</h2>
<p>The Site may contain links to third-party websites (e.g., LinkedIn, podcast platforms, publishers). We are not responsible for the privacy practices of those external sites. We encourage you to review their privacy policies before sharing any information.</p>

<h2>11. Changes to This Policy</h2>
<p>We may update this Privacy Policy periodically. The "Last updated" label at the top of this page reflects the most recent revision. Material changes will be highlighted on the Site for a reasonable period.</p>

<h2>12. Contact Us</h2>
<p>If you have any questions about this Privacy Policy or our data practices, please get in touch through our <a href="/contact-us">Contact Us</a> page. We aim to respond to all privacy-related inquiries within a reasonable timeframe.</p>
<p>
    <strong>Kevin Thompson Ph.D. Consulting</strong><br>
    Website: <a href="https://kevinthompsonphd.com">kevinthompsonphd.com</a>
</p>
HTML;

        PrivacyPageContent::updateOrCreate(['id' => 1], [
            'header_kicker'     => 'Legal',
            'header_h1_regular' => 'Privacy',
            'header_h1_em'      => 'Policy',
            'last_updated'      => 'Last updated: April 2026',
            'content'           => $content,
            'seo_title'         => 'Privacy Policy | Kevin Thompson Ph.D. Consulting',
            'seo_description'   => 'Learn how Kevin Thompson Ph.D. Consulting collects, uses, and protects visitor information across our consulting, training, and content services.',
            'seo_keywords'      => 'privacy policy, data protection, GDPR, CCPA, Kevin Thompson Ph.D., agile consulting',
        ]);
    }
}
