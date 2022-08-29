<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>

    <title>{{ config('mailviewer.view.title', 'MailViewer') }}</title>
</head>

<body class="bg-gray-100">
    <main class="max-w-7xl mx-auto px-4 lg:px-0">
        <div>
            <h1 class="mt-5 mb-3 text-gray-700">
                <a href="{{ route('mailviewer.analytics') }}" class="text-3xl font-bold underline">
                    {{ config('mailviewer.view.analytics_title', 'MailViewer Analytics') }}
                </a>

                @if(config('mailviewer.view.show_mailer'))
                <span class="float-right">
                    Mailer: <strong>{{ config('mail.default') }}</strong>
                </span>
                @endif
            </h1>
            <p class="mb-5">
                <a href="{{ route('mailviewer.index') }}" class="text-gray-400 hover:text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                    </svg>
                    Back to overview
                </a>
            </p>
        </div>

        <div class="overflow-x-auto relative rounded-md shadow">
            <table class="w-full">
                <thead class="text-xs text-left bg-gray-50 border-b">
                    <tr>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-800">Notification</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-800">#</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-800">Last sent at</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr class="border-b bg-white hover:bg-gray-50">
                        <td class="text-gray-700 py-4 px-2">
                            <abbr title="{{ $item->name }}">
                                {{ class_basename($item->name) }}
                            </abbr>
                        </td>
                        <td class="text-gray-700 py-4 px-2">
                            {{ $item->total }}
                        </td>
                        <td class="text-gray-700 py-4 px-2 whitespace-nowrap">
                            <abbr title="{{ $item->last_sent_at }}">
                                {{ Carbon\Carbon::parse($item->last_sent_at)->diffForHumans() }}
                            </abbr>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>