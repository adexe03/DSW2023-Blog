<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="antialiased">
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        @if (Route::has('login'))
        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
            @auth
            <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
            @else
            <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
            @endif
            @endauth
        </div>
        @endif

        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <h1 class="text-5xl">Publicaciones recientes</h1>
            <div class="">
                @foreach ($firstPosts as $post)
                <div class="rounded-3xl shadow-lg bg-white m-3 p-3 border-gray-500">
                    <div class="grid grid-flow-col justify-items-stretch">
                        <span class="text-4xl mt-3 col-auto">{{$post->title}}</span>
                        <div class="text-right pt-3">
                            <span class="text-sm bg-slate-200 p-2 rounded-2xl mt-5">
                                {{ \Carbon\Carbon::create($post->published_at)->format('d/m/Y')}}
                            </span>
                        </div>
                    </div>
                    <p class="mt-3">{{$post->summary}}</p>
                    <div class="grid grid-cols-2">
                        <div class="m-3">
                            <span class="text-sm">
                                Creado por
                                <span class=" font-bold text-slate-700">
                                    {{ $post->user->name }}
                                </span>
                            </span>
                        </div>
                        <div class="m-3 text-right">
                            <a class="text-sm bg-blue-200 p-2 rounded-xl" href="{{ Route('posts.show', $post->id)}}">
                                Leer más
                            </a>
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <ul class="list-outside">
                @foreach ($otherPosts as $post)
                <li class="m-2">
                    <span class="text-sm bg-slate-200 p-2 rounded-2xl">
                        {{ \Carbon\Carbon::create($post->published_at)->format('d/m/Y')}}
                    </span>
                    <span class="text-xl mt-3 col-auto mx-4">{{$post->title}}</span>

                </li>
                @endforeach
            </ul>
        </div>
    </div>
</body>

</html>