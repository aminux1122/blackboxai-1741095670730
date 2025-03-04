<?php
$page_title = "Privacy Policy";
require_once 'includes/config.php';
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">
                Privacy Policy
            </h1>
            <p class="mt-4 text-lg text-gray-500">
                Last updated: <?php echo date('F d, Y'); ?>
            </p>
        </div>
    </div>
</div>

<!-- Privacy Policy Content -->
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="prose prose-lg max-w-none">
        <h2>1. Introduction</h2>
        <p>
            At TableSpot, we take your privacy seriously. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our restaurant reservation service.
        </p>

        <h2>2. Information We Collect</h2>
        <h3>2.1 Personal Information</h3>
        <p>We collect information that you provide directly to us, including:</p>
        <ul>
            <li>Name and contact information</li>
            <li>Email address and phone number</li>
            <li>Dining preferences and dietary restrictions</li>
            <li>Payment information (processed securely through our payment providers)</li>
            <li>Reservation history and preferences</li>
            <li>Reviews and ratings you submit</li>
        </ul>

        <h3>2.2 Automatically Collected Information</h3>
        <p>When you use our Service, we automatically collect:</p>
        <ul>
            <li>Device information (type, operating system, browser)</li>
            <li>IP address and location data</li>
            <li>Usage data and browsing history on our platform</li>
            <li>Cookies and similar tracking technologies</li>
        </ul>

        <h2>3. How We Use Your Information</h2>
        <p>We use the collected information to:</p>
        <ul>
            <li>Process and manage your reservations</li>
            <li>Communicate with you about your bookings</li>
            <li>Personalize your dining experiences</li>
            <li>Improve our services and user experience</li>
            <li>Send you relevant marketing communications (with your consent)</li>
            <li>Prevent fraud and ensure security</li>
            <li>Comply with legal obligations</li>
        </ul>

        <h2>4. Information Sharing</h2>
        <p>We may share your information with:</p>
        <ul>
            <li>Restaurants where you make reservations</li>
            <li>Service providers who assist our operations</li>
            <li>Payment processors for transaction handling</li>
            <li>Legal authorities when required by law</li>
        </ul>

        <h2>5. Data Security</h2>
        <p>
            We implement appropriate technical and organizational measures to protect your personal information, including:
        </p>
        <ul>
            <li>Encryption of sensitive data</li>
            <li>Secure server infrastructure</li>
            <li>Regular security assessments</li>
            <li>Employee training on data protection</li>
            <li>Access controls and authentication</li>
        </ul>

        <h2>6. Your Rights and Choices</h2>
        <p>You have the right to:</p>
        <ul>
            <li>Access your personal information</li>
            <li>Correct inaccurate data</li>
            <li>Request deletion of your information</li>
            <li>Opt-out of marketing communications</li>
            <li>Control cookie preferences</li>
        </ul>

        <h2>7. Cookie Policy</h2>
        <p>
            We use cookies and similar technologies to:
        </p>
        <ul>
            <li>Remember your preferences</li>
            <li>Analyze site usage</li>
            <li>Personalize content</li>
            <li>Improve user experience</li>
        </ul>

        <h2>8. Children's Privacy</h2>
        <p>
            Our Service is not intended for children under 13. We do not knowingly collect information from children under 13. If you believe we have collected information from a child under 13, please contact us.
        </p>

        <h2>9. International Data Transfers</h2>
        <p>
            Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place for such transfers.
        </p>

        <h2>10. Changes to Privacy Policy</h2>
        <p>
            We may update this Privacy Policy periodically. We will notify you of any material changes by:
        </p>
        <ul>
            <li>Posting the new policy on our website</li>
            <li>Sending an email notification</li>
            <li>Displaying a notice in your account</li>
        </ul>

        <h2>11. Contact Us</h2>
        <p>
            If you have questions about this Privacy Policy or our privacy practices, please contact us:
        </p>
        <ul>
            <li>Email: privacy@tablespot.com</li>
            <li>Phone: +1 (555) 123-4567</li>
            <li>Address: 123 Restaurant Street, Foodie City, FC 12345</li>
        </ul>

        <div class="mt-8 p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600">
                By using TableSpot, you agree to the collection and use of information in accordance with this Privacy Policy. We are committed to protecting your privacy and ensuring you have a positive experience on our platform.
            </p>
        </div>

        <div class="mt-8 border-t border-gray-200 pt-8">
            <h2>12. Additional Resources</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="terms.php" class="block p-6 bg-white rounded-lg border border-gray-200 hover:bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900">Terms of Service</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Read our Terms of Service for more information about using TableSpot.
                    </p>
                </a>
                <a href="faq.php" class="block p-6 bg-white rounded-lg border border-gray-200 hover:bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900">FAQ</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Find answers to commonly asked questions about our service.
                    </p>
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
