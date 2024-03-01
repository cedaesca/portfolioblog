<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Complete Layout with Tailwind CSS</title>
</head>
<body class="flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav class="bg-gray-800 text-white p-4">
        <div class="container mx-auto flex justify-between">
            <div class="text-lg">Brand Name</div>
            <div>
                <a href="#" class="px-3 py-2 rounded hover:bg-gray-700">Home</a>
                <a href="#" class="px-3 py-2 rounded hover:bg-gray-700">About</a>
                <a href="#" class="px-3 py-2 rounded hover:bg-gray-700">Services</a>
                <a href="#" class="px-3 py-2 rounded hover:bg-gray-700">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <div class="flex-grow">
        <h1 class="text-3xl font-bold underline text-center mt-5">
            Hello world!
        </h1>
    </div>

    <!-- Projects Showcase Section -->
    <section class="bg-gray-100 py-8">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-center mb-8">Software Development Projects</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 justify-items-center">
                <!-- Project Item -->
                <div class="bg-white shadow-md rounded-lg p-4">
                    <img src="project-image-url.jpg" alt="Project 1" class="w-full mb-2">
                    <h3 class="text-lg font-semibold">Project 1</h3>
                    <p>Short description of the project.</p>
                </div>
                <!-- Add more project items as needed -->
            </div>
        </div>
    </section>

    <!-- Blog Articles Section -->
    <section class="py-8">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-center mb-8">Blog Articles</h2>
            <div class="space-y-4">
                <!-- Blog Article Item -->
                <div class="bg-white shadow-md rounded-lg p-4">
                    <h3 class="text-lg font-semibold">Article Title</h3>
                    <p class="text-gray-600">Short introduction or summary of the article.</p>
                    <a href="#" class="text-indigo-600 hover:text-indigo-800">Read more →</a>
                </div>
                <!-- Add more article items as needed -->
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-200 text-center p-4 w-full">
        This is the footer.
    </footer>
</body>
</html>
