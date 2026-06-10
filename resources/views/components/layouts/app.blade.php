<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="bg-white dark:bg-[#161618] text-gray-900 dark:text-gray-100 antialiased min-h-screen flex">

    <!-- Sidebar -->
    <x-layouts.sidebar />

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col ml-64">
        <main class="flex-1 p-6 bg-gray-50 dark:bg-[#161618] overflow-auto">
            {{ $slot }}
        </main>
    </div>

    @fluxScripts
</body>
</html>
