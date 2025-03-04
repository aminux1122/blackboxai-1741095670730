<?php
$page_title = "FAQ";
require_once 'includes/config.php';
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<div class="relative bg-indigo-800">
    <div class="absolute inset-0">
        <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1474&q=80" alt="FAQ">
        <div class="absolute inset-0 bg-indigo-800 mix-blend-multiply"></div>
    </div>
    <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
            Frequently Asked Questions
        </h1>
        <p class="mt-6 text-xl text-indigo-100 max-w-3xl">
            Find answers to common questions about TableSpot's reservation system.
        </p>
    </div>
</div>

<!-- FAQ Categories -->
<div class="bg-gray-50">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
            <!-- For Diners -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="text-center">
                        <i class="fas fa-utensils text-4xl text-indigo-600"></i>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">For Diners</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Questions about making reservations and using our platform
                        </p>
                    </div>
                </div>
            </div>

            <!-- For Restaurants -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="text-center">
                        <i class="fas fa-store text-4xl text-indigo-600"></i>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">For Restaurants</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Information about partnering with TableSpot
                        </p>
                    </div>
                </div>
            </div>

            <!-- Technical Support -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="text-center">
                        <i class="fas fa-cog text-4xl text-indigo-600"></i>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Technical Support</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Help with technical issues and account management
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Sections -->
        <div class="mt-16">
            <!-- For Diners Section -->
            <div class="mb-16">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-8">For Diners</h2>
                <dl class="space-y-8">
                    <div>
                        <dt class="text-lg leading-6 font-medium text-gray-900">
                            How do I make a reservation?
                        </dt>
                        <dd class="mt-2 text-base text-gray-500">
                            Making a reservation is easy! Simply search for your desired restaurant, select your preferred date and time, choose the number of guests, and confirm your booking. You'll receive an email confirmation once the restaurant accepts your reservation.
                        </dd>
                    </div>

                    <div>
                        <dt class="text-lg leading-6 font-medium text-gray-900">
                            Can I modify or cancel my reservation?
                        </dt>
                        <dd class="mt-2 text-base text-gray-500">
                            Yes, you can modify or cancel your reservation up to 2 hours before the scheduled time. Simply log in to your account, go to "My Reservations," and select the booking you wish to modify or cancel.
                        </dd>
                    </div>

                    <div>
                        <dt class="text-lg leading-6 font-medium text-gray-900">
                            Is there a fee for using TableSpot?
                        </dt>
                        <dd class="mt-2 text-base text-gray-500">
                            No, TableSpot is completely free for diners. We don't charge any booking fees or service charges.
                        </dd>
                    </div>

                    <div>
                        <dt class="text-lg leading-6 font-medium text-gray-900">
                            What happens if I'm running late?
                        </dt>
                        <dd class="mt-2 text-base text-gray-500">
                            If you're running late, please contact the restaurant directly. Most restaurants hold reservations for 15 minutes, but policies may vary by establishment.
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- For Restaurants Section -->
            <div class="mb-16">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-8">For Restaurants</h2>
                <dl class="space-y-8">
                    <div>
                        <dt class="text-lg leading-6 font-medium text-gray-900">
                            How can my restaurant join TableSpot?
                        </dt>
                        <dd class="mt-2 text-base text-gray-500">
                            To join TableSpot, simply register as a restaurant partner through our website. We'll review your application and contact you to set up your account and provide training on our platform.
                        </dd>
                    </div>

                    <div>
                        <dt class="text-lg leading-6 font-medium text-gray-900">
                            What are the costs involved?
                        </dt>
                        <dd class="mt-2 text-base text-gray-500">
                            We offer flexible pricing plans based on your restaurant's size and needs. Contact our sales team for detailed pricing information and to find the best plan for your business.
                        </dd>
                    </div>

                    <div>
                        <dt class="text-lg leading-6 font-medium text-gray-900">
                            How does the reservation system work?
                        </dt>
                        <dd class="mt-2 text-base text-gray-500">
                            Our system allows you to manage your table inventory, set available times, and receive real-time notifications of new bookings. You can also access detailed analytics and customer data to improve your service.
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Technical Support Section -->
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 mb-8">Technical Support</h2>
                <dl class="space-y-8">
                    <div>
                        <dt class="text-lg leading-6 font-medium text-gray-900">
                            How do I reset my password?
                        </dt>
                        <dd class="mt-2 text-base text-gray-500">
                            Click on the "Forgot Password" link on the login page. Enter your email address, and we'll send you instructions to reset your password.
                        </dd>
                    </div>

                    <div>
                        <dt class="text-lg leading-6 font-medium text-gray-900">
                            What should I do if I can't access my account?
                        </dt>
                        <dd class="mt-2 text-base text-gray-500">
                            First, ensure you're using the correct email and password. If you still can't access your account, contact our support team for assistance.
                        </dd>
                    </div>

                    <div>
                        <dt class="text-lg leading-6 font-medium text-gray-900">
                            Is my personal information secure?
                        </dt>
                        <dd class="mt-2 text-base text-gray-500">
                            Yes, we take security seriously. We use industry-standard encryption to protect your personal information and never share your data with third parties without your consent.
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Still Have Questions -->
        <div class="mt-16 text-center">
            <h2 class="text-3xl font-extrabold text-gray-900">Still Have Questions?</h2>
            <p class="mt-4 text-lg text-gray-500">
                Can't find the answer you're looking for? Please contact our support team.
            </p>
            <div class="mt-6">
                <a href="contact.php" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                    Contact Support
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
