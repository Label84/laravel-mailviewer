<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>

    <title>{{ config('mailviewer.view.title', 'MailViewer') }}</title>
</head>

<body class="bg-gray-100">
    <main class="max-w-7xl mx-auto px-4 lg:px-0">
        <div>
            <h1 class="mt-5 mb-3 text-gray-700">
                <a href="{{ route('mailviewer.index') }}" class="text-3xl font-bold underline">
                    {{ config('mailviewer.view.title', 'MailViewer') }}
                </a>

                @if(config('mailviewer.view.show_mailer'))
                <span class="float-right">
                    Mailer: <strong>{{ config('mail.default') }}</strong>
                </span>
                @endif
            </h1>
            <div class="mb-5 justify-between">
                <a href="{{ config('mailviewer.view.back_to_application_link_url') ?? '/' }}" class="text-gray-400 hover:text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                    </svg>
                    {{ config('mailviewer.view.back_to_application_link_title') ?? 'Back to Laravel' }}
                </a>
                <a href="{{ route('mailviewer.analytics') }}" class="float-right text-gray-400 hover:text-gray-800">
                    Analytics
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto relative rounded-md shadow">
            <table class="w-full">
                <thead class="text-xs text-left bg-gray-50 border-b">
                    <tr>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-800">Notification</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-800">Subject</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-800">Recipients</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-800">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mails as $mail)
                    <tr class="border-b bg-white hover:bg-gray-50">
                        <td class="text-gray-700 py-4 px-2">
                            <abbr title="{{ $mail->notification }}">
                                {{ class_basename($mail->notification) }}
                            </abbr>
                        </td>
                        <td class="text-gray-700 py-4 px-2">
                            @if($mail->body)
                            <a href="{{ route('mailviewer.show', $mail) }}" target="{{ config('mailviewer.view.use_tabs') ? '_blank' : '_self' }}" class="underline text-blue-500">
                                {{ $mail->subject }}
                            </a>
                            @else
                            {{ $mail->subject }}
                            @endif
                        </td>
                        <td class="text-gray-700 py-4 px-2">
                            @foreach($mail->to_recipients as $recipient)
                            {{ $recipient }}<br>
                            @endforeach

                            @foreach($mail->cc_recipients as $recipient)
                            <span class="align-baseline small text-muted">cc:</span> {{ $recipient }}<br>
                            @endforeach
                        </td>
                        <td class="text-gray-700 py-4 px-2">
                            <span class="text-nowrap">
                                <abbr title="{{ $mail->sent_at->toDateTimeString() }}">
                                    {{ $mail->sent_at->diffForHumans() }}
                                </abbr>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 mb-12">
            {{ $mails->appends(request()->query())->links() }}
        </div>
    </main>
</body>

</html>
