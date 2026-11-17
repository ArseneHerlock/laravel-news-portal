<?php

namespace App\Http\Controllers;

use App\Models\AuthorProfile;
use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserPortalController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = $request->user();

        $bookmarks = Bookmark::with(['post.category', 'post.author'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $comments = Comment::with('post')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $tickets = Ticket::where('user_id', $user->id)->latest()->get();

        $stats = [
            'bookmarks_count' => $bookmarks->count(),
            'comments_count' => $comments->count(),
            'approved_comments' => $comments->where('status', 'approved')->count(),
            'tickets_count' => $tickets->count(),
        ];

        return view('portal.user.dashboard', compact('user', 'bookmarks', 'comments', 'tickets', 'stats'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Your profile details have been updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Your password has been changed successfully.');
    }

    public function requestAuthorRole(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'designation' => ['required', 'string', 'max:100'],
            'about' => ['required', 'string', 'max:1000'],
        ]);

        AuthorProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'designation' => $validated['designation'],
                'about' => $validated['about'],
                'is_approved' => false,
            ]
        );

        return back()->with('success', 'Your Author application has been submitted for Admin approval!');
    }

    public function storeTicket(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'in:low,medium,high'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $ticket = Ticket::create([
            'user_id' => $request->user()->id,
            'ticket_code' => 'TCK-'.strtoupper(Str::random(6)),
            'subject' => $validated['subject'],
            'priority' => $validated['priority'],
            'status' => 'open',
        ]);

        $ticket->replies()->create([
            'user_id' => $request->user()->id,
            'message' => $validated['message'],
        ]);

        return back()->with('success', 'Support ticket '.$ticket->ticket_code.' created successfully.');
    }
}
