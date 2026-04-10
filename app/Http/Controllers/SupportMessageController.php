<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupportMessageRequest;
use App\Models\SupportThread;

class SupportMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(SupportMessageRequest $request, SupportThread $supportThread)
    {
        abort_unless($supportThread->user_id === auth()->id(), 403);

        $supportThread->messages()->create([
            'message' => $request->message,
            'user_id' => auth()->id(),
        ]);

        if ($supportThread->status === SupportThread::STATUS_CLOSED) {
            $supportThread->update(['status' => SupportThread::STATUS_OPEN]);
        }

        return redirect()->route('support.show', $supportThread)->with('status', __('messages.support.message_sent'));
    }
}
