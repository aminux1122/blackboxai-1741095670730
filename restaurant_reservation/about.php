<?php
$page_title = "About Us";
require_once 'includes/config.php';
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<div class="relative bg-indigo-800">
    <div class="absolute inset-0">
        <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="Restaurant interior">
        <div class="absolute inset-0 bg-indigo-800 mix-blend-multiply"></div>
    </div>
    <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">About TableSpot</h1>
        <p class="mt-6 text-xl text-indigo-100 max-w-3xl">
            Revolutionizing the way people discover and book restaurants, one table at a time.
        </p>
    </div>
</div>

<!-- Our Story Section -->
<div class="py-16 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-8">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Our Story
                </h2>
                <div class="mt-3 text-lg text-gray-500">
                    <p class="mb-4">
                        Founded in 2023, TableSpot emerged from a simple observation: booking a restaurant table should be effortless and enjoyable. Our platform connects food lovers with their perfect dining experiences, while helping restaurants optimize their operations.
                    </p>
                    <p>
                        What started as a small startup has grown into a trusted platform serving thousands of diners and restaurants across the country. We're passionate about food, technology, and bringing people together through memorable dining experiences.
                    </p>
                </div>
            </div>
            <div class="mt-8 lg:mt-0">
                <div class="aspect-w-16 aspect-h-9">
                    <img class="rounded-lg shadow-lg object-cover" src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="Restaurant dining">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mission & Values Section -->
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                Our Mission & Values
            </h2>
            <p class="mt-4 text-lg text-gray-500">
                Guided by our commitment to excellence and innovation
            </p>
        </div>

        <div class="mt-16">
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Mission -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="text-center">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-600 text-white mx-auto">
                                <i class="fas fa-bullseye text-xl"></i>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Our Mission</h3>
                            <p class="mt-2 text-base text-gray-500">
                                To transform the restaurant booking experience by connecting diners with their perfect dining destinations through innovative technology.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Vision -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="text-center">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-600 text-white mx-auto">
                                <i class="fas fa-eye text-xl"></i>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Our Vision</h3>
                            <p class="mt-2 text-base text-gray-500">
                                To be the world's most trusted platform for restaurant discovery and reservations, enhancing dining experiences globally.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Values -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="text-center">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-600 text-white mx-auto">
                                <i class="fas fa-heart text-xl"></i>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Our Values</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Excellence, Innovation, Integrity, and Customer-First approach guide everything we do.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Team Section -->
<div class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                Meet Our Team
            </h2>
            <p class="mt-4 text-lg text-gray-500">
                Passionate professionals dedicated to revolutionizing the dining experience
            </p>
        </div>

        <div class="mt-16 grid grid-cols-1 gap-12 lg:grid-cols-3 lg:gap-8">
            <!-- Team Member 1 -->
            <div class="space-y-4">
                <div class="aspect-w-3 aspect-h-3">
                    <img class="object-cover shadow-lg rounded-lg" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="Sarah Johnson">
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900">Sarah Johnson</h3>
                    <p class="text-sm text-indigo-600">CEO & Founder</p>
                    <p class="mt-2 text-base text-gray-500">
                        Former restaurant owner turned tech entrepreneur with 15+ years in hospitality.
                    </p>
                </div>
            </div>

            <!-- Team Member 2 -->
            <div class="space-y-4">
                <div class="aspect-w-3 aspect-h-3">
                    <img class="object-cover shadow-lg rounded-lg" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="Michael Chen">
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900">Michael Chen</h3>
                    <p class="text-sm text-indigo-600">CTO</p>
                    <p class="mt-2 text-base text-gray-500">
                        Tech innovator with expertise in building scalable platforms.
                    </p>
                </div>
            </div>

            <!-- Team Member 3 -->
            <div class="space-y-4">
                <div class="aspect-w-3 aspect-h-3">
                    <img class="object-cover shadow-lg rounded-lg" src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="Emily Rodriguez">
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900">Emily Rodriguez</h3>
                    <p class="text-sm text-indigo-600">Head of Operations</p>
                    <p class="mt-2 text-base text-gray-500">
                        Hospitality expert ensuring smooth operations and customer satisfaction.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="bg-indigo-800">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:py-16 sm:px-6 lg:px-8 lg:py-20">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                Trusted by Restaurants and Diners Alike
            </h2>
            <p class="mt-3 text-xl text-indigo-200 sm:mt-4">
                Our platform connects thousands of diners with their perfect dining experiences every day.
            </p>
        </div>
        <dl class="mt-10 text-center sm:max-w-3xl sm:mx-auto sm:grid sm:grid-cols-3 sm:gap-8">
            <div class="flex flex-col">
                <dt class="order-2 mt-2 text-lg leading-6 font-medium text-indigo-200">
                    Restaurants
                </dt>
                <dd class="order-1 text-5xl font-extrabold text-white">
                    500+
                </dd>
            </div>
            <div class="flex flex-col mt-10 sm:mt-0">
                <dt class="order-2 mt-2 text-lg leading-6 font-medium text-indigo-200">
                    Monthly Bookings
                </dt>
                <dd class="order-1 text-5xl font-extrabold text-white">
                    50K+
                </dd>
            </div>
            <div class="flex flex-col mt-10 sm:mt-0">
                <dt class="order-2 mt-2 text-lg leading-6 font-medium text-indigo-200">
                    Happy Diners
                </dt>
                <dd class="order-1 text-5xl font-extrabold text-white">
                    100K+
                </dd>
            </div>
        </dl>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
