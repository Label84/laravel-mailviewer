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
                <a href="{{ route('mailviewer.analytics') }}" class="link-dark text-decoration-none">
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
                                <th scope="col">Notification</th>
                                <th scope="col">#</th>
                                <th scope="col">Last sent at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>
                                    <abbr title="{{ $item->name }}">
                                        {{ class_basename($item->name) }}
                                    </abbr>
                                </td>
                                <td>
                                    {{ $item->total }}
                                </td>
                                <td>
                                    {{ $item->last_sent_at }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</body>

</html>