<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TermsPageContent;

class TermsPageContentSeeder extends Seeder
{
    public function run(): void
    {
        $content = <<<'HTML'
<h2>1. Acceptance of Terms</h2>
<p>By accessing or using this website, you agree to be bound by these Terms and Conditions.</p>
<p>If you do not agree, please do not use the website.</p>

<h2>2. Intellectual Property</h2>
<p>All content on this website, including but not limited to text, documents, frameworks, and materials, is owned by Kevin Thompson Ph.D. Consulting and is protected by copyright and other intellectual property laws.</p>

<h2>3. License to Use Materials</h2>
<p>We grant you a limited, non-exclusive, non-transferable license to download and use materials from this website for:</p>
<ul>
    <li>Personal use</li>
    <li>Internal business use</li>
</ul>
<p>You may not:</p>
<ul>
    <li>Resell, redistribute, or sublicense the materials</li>
    <li>Modify and present the materials as your own</li>
    <li>Use the materials for commercial resale or training without permission</li>
</ul>

<h2>4. Permitted Use</h2>
<p>You agree to use this website and its content only for lawful purposes.</p>
<p>You must not:</p>
<ul>
    <li>Use the content in a way that infringes intellectual property rights</li>
    <li>Attempt to gain unauthorized access to the website</li>
    <li>Use the website in a way that could damage or disrupt it</li>
</ul>

<h2>5. No Warranties</h2>
<p>All materials are provided "as is" without warranties of any kind, express or implied.</p>
<p>We do not guarantee that:</p>
<ul>
    <li>The content is accurate, complete, or up-to-date</li>
    <li>The materials will achieve any specific results</li>
</ul>

<h2>6. Limitation of Liability</h2>
<p>To the fullest extent permitted by law, Kevin Thompson Ph.D. Consulting shall not be liable for any indirect, incidental, or consequential damages arising from the use of this website or its materials.</p>

<h2>7. Third-Party Links</h2>
<p>This website may contain links to third-party websites. We are not responsible for their content or practices.</p>

<h2>8. Changes to These Terms</h2>
<p>We may update these Terms from time to time. Continued use of the website constitutes acceptance of the updated Terms.</p>

<h2>9. Governing Law</h2>
<p>These Terms are governed by the laws of the United States of America.</p>

<h2>10. Contact Information</h2>
<p>If you have any questions about these Terms, please <a href="/contact-us">contact us</a>.</p>
HTML;

        TermsPageContent::updateOrCreate(['id' => 1], [
            'header_kicker'     => 'Legal',
            'header_h1_regular' => 'Terms &',
            'header_h1_em'      => 'Conditions',
            'last_updated'      => 'Effective Date: April 1, 2026',
            'content'           => $content,
            'seo_title'         => 'Terms & Conditions | Kevin Thompson Ph.D. Consulting',
            'seo_description'   => 'The terms governing access to kevinthompsonphd.com, our content, and our consulting and training services. Please read carefully before using the site.',
            'seo_keywords'      => 'terms of service, terms and conditions, user agreement, Kevin Thompson Ph.D., agile consulting',
        ]);
    }
}
