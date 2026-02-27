<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupportThreadRequest;
use App\Models\SupportThread;

class SupportThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $threads = auth()->user()
            ->supportThreads()
            ->with('latestMessage')
            ->latest()
            ->get();

        $activeThread = $threads->first();

        if ($activeThread) {
            $activeThread->load(['messages.user']);
        }

        return view('account.support', compact('threads', 'activeThread'));
    }

    public function show(SupportThread $supportThread)
    {
        abort_unless($supportThread->user_id === auth()->id(), 403);

        $threads = auth()->user()
            ->supportThreads()
            ->with('latestMessage')
            ->latest()
            ->get();

        $supportThread->load(['messages.user']);

        return view('account.support', [
            'threads' => $threads,
            'activeThread' => $supportThread,
        ]);
    }

    public function store(SupportThreadRequest $request)
    {
        $thread = auth()->user()->supportThreads()->create([
            'subject' => $request->subject,
            'status' => SupportThread::STATUS_OPEN,
        ]);

        $thread->messages()->create([
            'message' => $request->initial_message,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('support.show', $thread)->with('status', 'Звернення створено.');
    }
}
