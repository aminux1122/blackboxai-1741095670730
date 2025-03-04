<?php
$page_title = "Contact Us";
require_once 'includes/config.php';
require_once 'includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $subject = sanitize_input($_POST['subject']);
    $message = sanitize_input($_POST['message']);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Please fill in all fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } else {
        // Here you would typically send an email
        // For now, we'll just show a success message
        $success = 'Thank you for your message. We will get back to you soon!';
    }
}
?>

<!-- Hero Section -->
<div class="relative bg-gray-800">
    <div class="absolute inset-0">
        <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="Contact us">
        <div class="absolute inset-0 bg-gray-800 mix-blend-multiply"></div>
    </div>
    <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Contact Us</h1>
        <p class="mt-6 text-xl text-gray-300 max-w-3xl">
            Have a question or feedback? We'd love to hear from you.
        </p>
    </div>
</div>

<!-- Contact Section -->
<div class="bg-white py-16 px-4 overflow-hidden sm:px-6 lg:px-8 lg:py-24">
    <div class="relative max-w-xl mx-auto">
        <!-- Success/Error Messages -->
        <?php if ($success): ?>
            <div class="mb-8 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo $success; ?></span>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="mb-8 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo $error; ?></span>
            </div>
        <?php endif; ?>

        <!-- Contact Information -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-12">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Contact Information</h3>
                <dl class="mt-4 text-base text-gray-500">
                    <div class="mt-4">
                        <dt class="sr-only">Address</dt>
                        <dd class="flex">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-gray-400"></i>
                            <span>123 Restaurant Street<br>Foodie City, FC 12345</span>
                        </dd>
                    </div>
                    <div class="mt-4">
                        <dt class="sr-only">Phone number</dt>
                        <dd class="flex">
                            <i class="fas fa-phone mt-1 mr-3 text-gray-400"></i>
                            <span>+1 (555) 123-4567</span>
                        </dd>
                    </div>
                    <div class="mt-4">
                        <dt class="sr-only">Email</dt>
                        <dd class="flex">
                            <i class="fas fa-envelope mt-1 mr-3 text-gray-400"></i>
                            <span>info@tablespot.com</span>
                        </dd>
                    </div>
                </dl>
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-900">Business Hours</h3>
                <dl class="mt-4 text-base text-gray-500">
                    <div class="mt-4">
                        <dt class="font-medium">Monday - Friday</dt>
                        <dd>9:00 AM - 8:00 PM</dd>
                    </div>
                    <div class="mt-4">
                        <dt class="font-medium">Saturday</dt>
                        <dd>10:00 AM - 6:00 PM</dd>
                    </div>
                    <div class="mt-4">
                        <dt class="font-medium">Sunday</dt>
                        <dd>Closed</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="mt-12">
            <h2 class="text-2xl font-extrabold text-gray-900 sm:text-3xl mb-8">Send us a message</h2>
            <form action="contact.php" method="POST" class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-8">
                <div class="sm:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <div class="mt-1">
                        <input type="text" name="name" id="name" autocomplete="name" required
                               class="py-3 px-4 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="mt-1">
                        <input type="email" name="email" id="email" autocomplete="email" required
                               class="py-3 px-4 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                    <div class="mt-1">
                        <input type="text" name="subject" id="subject" required
                               class="py-3 px-4 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                    <div class="mt-1">
                        <textarea name="message" id="message" rows="4" required
                                  class="py-3 px-4 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md"></textarea>
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Map Section -->
<div class="bg-gray-50">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-extrabold text-gray-900 sm:text-3xl mb-8">Find Us</h2>
        <div class="aspect-w-16 aspect-h-9">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d387193.30596698663!2d-74.25986548727506!3d40.69714941680757!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2s!4v1683840557072!5m2!1sen!2s"
                width="100%" 
                height="450" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade"
                class="rounded-lg shadow-lg">
            </iframe>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-extrabold text-gray-900 sm:text-3xl">Frequently Asked Questions</h2>
        <div class="mt-12">
            <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-12">
                <div>
                    <dt class="text-lg leading-6 font-medium text-gray-900">
                        How do I make a reservation?
                    </dt>
                    <dd class="mt-2 text-base text-gray-500">
                        Simply search for your desired restaurant, select your preferred date and time, and follow the booking process. You'll receive a confirmation email once your reservation is confirmed.
                    </dd>
                </div>
                <div>
                    <dt class="text-lg leading-6 font-medium text-gray-900">
                        Can I modify my reservation?
                    </dt>
                    <dd class="mt-2 text-base text-gray-500">
                        Yes, you can modify or cancel your reservation through your account up to 2 hours before the scheduled time.
                    </dd>
                </div>
                <div>
                    <dt class="text-lg leading-6 font-medium text-gray-900">
                        Is there a fee for using TableSpot?
                    </dt>
                    <dd class="mt-2 text-base text-gray-500">
                        No, TableSpot is completely free for diners. Restaurants pay a small fee for our reservation management service.
                    </dd>
                </div>
                <div>
                    <dt class="text-lg leading-6 font-medium text-gray-900">
                        How can restaurants join TableSpot?
                    </dt>
                    <dd class="mt-2 text-base text-gray-500">
                        Restaurants can sign up through our restaurant partners page. We offer different subscription plans to suit various business needs.
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
