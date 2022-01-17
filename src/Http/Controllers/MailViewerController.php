<?php

namespace Label84\MailViewer\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Label84\MailViewer\Models\MailViewerItem;

class MailViewerController extends Controller
{
    public function index(Request $request): View
    {
        /** @var string notification */
        $notification = $request->query('notification', null);

        $mails = MailViewerItem::query()
            ->when($request->query('to'), fn ($query) => $query->whereJsonContains('recipients->to', [$request->query('to')]))
            ->when($request->query('cc'), fn ($query) => $query->whereJsonContains('recipients->cc', [$request->query('cc')]))
            ->when($request->query('bcc'), fn ($query) => $query->whereJsonContains('recipients->bcc', [$request->query('bcc')]))
            ->when($request->query('notification'), fn ($query) => $query->where('notification', 'LIKE', "%{$notification}"))
            ->when($request->query('from'), fn ($query) => $query->whereDate('sent_at', '>=', Carbon::parse($request->query('from'))))
            ->when($request->query('till'), fn ($query) => $query->whereDate('sent_at', '<=', Carbon::parse($request->query('till'))))
            ->when($request->query('around'), function ($query) use ($request) {
                return $query->whereBetween('sent_at', [
                    Carbon::parse($request->query('around'))->subMinutes((int) $request->query('d', 10)),
                    Carbon::parse($request->query('around'))->addMinutes((int) $request->query('d', 10)),
                ]);
            })
            ->paginate(config('mailviewer.view.items_per_page', 50));

        return view('mailviewer::index', compact('mails'));
    }

    public function show(string $uuid): Response
    {
        $mailViewerItem = MailViewerItem::where('uuid', $uuid)->firstOrFail();

        return response($mailViewerItem->body);
    }

    public function analytics(): View
    {
        $items = DB::table('mail_viewer_items')
            ->whereNotNull('notification')
            ->select([
                DB::raw('notification AS name'),
                DB::raw('count(*) AS total'),
                DB::raw('MAX(sent_at) AS last_sent_at'),
            ])
            ->groupBy('notification')
            ->orderByDesc('total')
            ->get();

        return view('mailviewer::analytics', compact('items'));
    }
}
