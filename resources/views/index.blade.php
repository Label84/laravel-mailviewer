<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title>{{ config('mailviewer.view.title', 'MailViewer') }}</title>
</head>

<body>
    <main>
        <section class="py-2 container-fluid">
            <h1>
                <a href="{{ route('mailviewer.index') }}" class="link-dark text-decoration-none">
                    {{ config('mailviewer.view.title', 'MailViewer') }}
                </a>

                @if(config('mailviewer.view.show_mailer'))
                <span class="fs-6 float-end text-muted">
                    mailer: <strong>{{ config('mail.default') }}</strong>
                </span>
                @endif
            </h1>
            <div class="row">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th scope="col">UUID</th>
                                <th scope="col">Notification</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Recipients</th>
                                <th scope="col">Sent at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mails as $mail)
                            <tr>
                                <th scope="row">
                                    <a href="{{ route('mailviewer.show', $mail) }}"
                                        target="{{ config('mailviewer.view.use_tabs') ? '_blank' : '_self' }}">
                                        {{ $mail->uuid }}
                                    </a>
                                </th>
                                <td>
                                    <abbr title="{{ $mail->notification }}">
                                        {{ class_basename($mail->notification) }}
                                    </abbr>
                                </td>
                                <td>{{ $mail->subject }}</td>
                                <td>
                                    @foreach($mail->to_recipients as $recipient)
                                    {{ $recipient }}<br>
                                    @endforeach

                                    @foreach($mail->cc_recipients as $recipient)
                                    <span class="align-baseline small text-muted">cc:</span> {{ $recipient }}<br>
                                    @endforeach
                                </td>
                                <td>{{ $mail->sent_at }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $mails->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </section>
    </main>
</body>

</html>