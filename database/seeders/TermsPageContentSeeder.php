<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TermsPageContent;

class TermsPageContentSeeder extends Seeder
{
    public function run(): void
    {
        $content = <<<'HTML'
<p>These Terms &amp; Conditions ("Terms") govern your access to and use of <a href="https://kevinthompsonphd.com">kevinthompsonphd.com</a> (the "Site") and the consulting, training, advisory, and content services offered by <strong>Kevin Thompson Ph.D. Consulting</strong> ("we," "us," or "our"). By accessing the Site or engaging our services, you agree to be bound by these Terms. If you do not agree, please do not use the Site.</p>

<h2>1. Eligibility</h2>
<p>You must be at least 18 years of age (or the age of legal majority in your jurisdiction) and capable of entering into a binding contract to use the Site or purchase any of our services.</p>

<h2>2. Use of the Site</h2>
<p>You agree to use the Site only for lawful purposes and in a manner that does not infringe the rights of, restrict, or inhibit anyone else's use of the Site. Specifically, you agree not to:</p>
<ul>
    <li>Use the Site in any way that violates any applicable local, national, or international law</li>
    <li>Attempt to gain unauthorized access to any portion of the Site or any related system</li>
    <li>Introduce viruses, malware, or any other harmful code</li>
    <li>Harvest or scrape content, contact data, or user information</li>
    <li>Misrepresent your identity or affiliation in any submission</li>
</ul>

<h2>3. Intellectual Property</h2>
<p>All content on the Site — including articles, blog posts, white papers, presentations, training materials, podcasts, webinars, course curricula, branding, logos, and code — is the property of Kevin Thompson Ph.D. Consulting or its licensors and is protected by copyright, trademark, and other intellectual property laws.</p>
<p>You may view, share, and quote brief excerpts of public content for personal, non-commercial purposes <strong>with proper attribution</strong>. Any other use — including reproduction, distribution, modification, public display, or use in a commercial training program — requires our prior written permission.</p>

<h2>4. Consulting &amp; Training Services</h2>
<h3>a. Engagement Terms</h3>
<p>Specific consulting engagements, training classes, and workshops are governed by a <strong>separate Statement of Work (SOW)</strong> or service agreement that supersedes these Terms with respect to that engagement. Pricing, deliverables, schedules, and cancellation policies for paid engagements will be set out in that document.</p>
<h3>b. Inquiries</h3>
<p>Submitting a Contact Form inquiry does not create a contractual relationship. A binding engagement is formed only when both parties have signed an SOW or written agreement.</p>
<h3>c. Professional Advice Disclaimer</h3>
<p>Content on the Site (blog posts, papers, podcasts, training materials) is provided for educational and informational purposes. It does not constitute legal, financial, engineering, or professional advice tailored to your situation, and should not be relied upon as such without further consultation.</p>

<h2>5. User Submissions</h2>
<p>Any information you submit through the Site (including the Contact Form) must be accurate, current, and your own. You grant us a limited, non-exclusive, royalty-free license to use submitted content solely to respond to your inquiry, deliver requested services, and improve our offerings.</p>

<h2>6. Third-Party Links &amp; Content</h2>
<p>The Site may include links to third-party websites, tools, and resources (e.g., publisher pages, LinkedIn, podcast hosts). These links are provided for convenience only. We do not control and are not responsible for the content, privacy practices, or availability of any third-party site.</p>

<h2>7. Disclaimers</h2>
<p>The Site and all content are provided <strong>"as is" and "as available"</strong> without warranties of any kind, either express or implied — including, but not limited to, implied warranties of merchantability, fitness for a particular purpose, non-infringement, or course of performance.</p>
<p>We do not warrant that the Site will be uninterrupted, error-free, secure, or free of viruses or other harmful components. You use the Site at your own risk.</p>

<h2>8. Limitation of Liability</h2>
<p>To the maximum extent permitted by law, Kevin Thompson Ph.D. Consulting and its principals, employees, and affiliates shall not be liable for any indirect, incidental, special, consequential, or punitive damages — including lost profits, lost revenue, lost data, or business interruption — arising out of or related to your use of the Site or any content, even if advised of the possibility of such damages.</p>
<p>Our aggregate liability for any direct damages arising from these Terms shall not exceed <strong>USD $100</strong> or, where a paid engagement is in effect, the fees you have paid us in the preceding three (3) months, whichever is greater.</p>

<h2>9. Indemnification</h2>
<p>You agree to defend, indemnify, and hold harmless Kevin Thompson Ph.D. Consulting from and against any claims, damages, liabilities, losses, and expenses (including reasonable attorneys' fees) arising out of or related to your use of the Site, your violation of these Terms, or your infringement of any third-party right.</p>

<h2>10. Termination</h2>
<p>We may suspend or terminate your access to the Site at any time, with or without notice, for any conduct that we, in our sole discretion, believe violates these Terms or is harmful to other users, to us, or to third parties. Sections that by their nature should survive termination (e.g., Intellectual Property, Disclaimers, Limitation of Liability, Indemnification, Governing Law) shall survive.</p>

<h2>11. Governing Law &amp; Jurisdiction</h2>
<p>These Terms shall be governed by and construed in accordance with the laws of the <strong>State of California, United States</strong>, without regard to its conflict-of-law provisions. You agree to submit to the exclusive jurisdiction of the state and federal courts located in California for the resolution of any dispute arising under these Terms.</p>

<h2>12. Changes to These Terms</h2>
<p>We may update these Terms from time to time. The "Last updated" label at the top of this page reflects the most recent revision. Continued use of the Site after a change becomes effective constitutes your acceptance of the revised Terms.</p>

<h2>13. Severability</h2>
<p>If any provision of these Terms is held to be invalid or unenforceable by a court of competent jurisdiction, the remaining provisions shall remain in full force and effect.</p>

<h2>14. Contact</h2>
<p>For any questions about these Terms, please get in touch through our <a href="/contact-us">Contact Us</a> page.</p>
<p>
    <strong>Kevin Thompson Ph.D. Consulting</strong><br>
    Website: <a href="https://kevinthompsonphd.com">kevinthompsonphd.com</a>
</p>
HTML;

        TermsPageContent::updateOrCreate(['id' => 1], [
            'header_kicker'     => 'Legal',
            'header_h1_regular' => 'Terms &',
            'header_h1_em'      => 'Conditions',
            'last_updated'      => 'Last updated: April 2026',
            'content'           => $content,
            'seo_title'         => 'Terms & Conditions | Kevin Thompson Ph.D. Consulting',
            'seo_description'   => 'The terms governing access to kevinthompsonphd.com, our content, and our consulting and training services. Please read carefully before using the site.',
            'seo_keywords'      => 'terms of service, terms and conditions, user agreement, Kevin Thompson Ph.D., agile consulting',
        ]);
    }
}
