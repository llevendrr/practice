<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupportMessageRequest;
use App\Models\SupportThread;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $threads = SupportThread::with(['user', 'latestMessage'])
            ->latest()
            ->get();

        return view('admin.support.index', compact('threads'));
    }

    public function show(SupportThread $supportThread)
    {
        $supportThread->load(['user', 'messages.user']);

        $threads = SupportThread::with(['user', 'latestMessage'])
            ->latest()
            ->get();

        return view('admin.support.show', [
            'threads' => $threads,
            'activeThread' => $supportThread,
        ]);
    }

    public function storeMessage(SupportMessageRequest $request, SupportThread $supportThread)
    {
        $supportThread->messages()->create([
            'message' => $request->message,
            'user_id' => auth()->id(),
        ]);

        if ($supportThread->status === SupportThread::STATUS_CLOSED) {
            $supportThread->update(['status' => SupportThread::STATUS_OPEN]);
        }

        return back()->with('status', 'Відповідь надіслано.');
    }

    public function updateStatus(Request $request, SupportThread $supportThread)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in([SupportThread::STATUS_OPEN, SupportThread::STATUS_CLOSED])],
        ]);

        $supportThread->update(['status' => $validated['status']]);

        return back()->with('status', 'Статус оновлено.');
    }
}
