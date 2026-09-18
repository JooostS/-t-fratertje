<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use App\Models\Member;
use App\Models\MemberType;
use App\Services\MemberService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function __construct(private MemberService $members) {}

    public function index(Request $request): View
    {
        $archived = $request->boolean('archived');

        $members = Member::query()
            ->with(['memberType', 'address', 'breedingNumber'])
            ->when($archived, fn ($query) => $query->onlyTrashed())
            ->search($request->query('q'))
            ->ofType($request->integer('member_type_id'))
            ->withStatus($request->query('status'))
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(15)
            ->withQueryString();

        return view('members.index', [
            'members' => $members,
            'memberTypes' => MemberType::orderBy('name')->get(),
            'archived' => $archived,
        ]);
    }

    public function create(): View
    {
        return view('members.create', ['memberTypes' => MemberType::orderBy('name')->get()]);
    }

    public function store(MemberRequest $request): RedirectResponse
    {
        $member = $this->members->create($request->validated());

        return redirect()->route('members.show', $member)->with('status', 'Lid is toegevoegd.');
    }

    public function show(Member $member): View
    {
        $member->load(['memberType', 'address', 'breedingNumber', 'invoices']);

        return view('members.show', ['member' => $member]);
    }

    public function edit(Member $member): View
    {
        $member->load(['address', 'breedingNumber']);

        return view('members.edit', [
            'member' => $member,
            'memberTypes' => MemberType::orderBy('name')->get(),
        ]);
    }

    public function update(MemberRequest $request, Member $member): RedirectResponse
    {
        $this->members->update($member, $request->validated());

        return redirect()->route('members.show', $member)->with('status', 'Lid is gewijzigd.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        $this->members->archive($member);

        return redirect()->route('members.index')->with('status', "{$member->full_name} is gearchiveerd.");
    }

    public function restore(Member $member): RedirectResponse
    {
        $member->restore();

        return redirect()->route('members.show', $member)->with('status', "{$member->full_name} is teruggehaald uit het archief.");
    }

    public function approve(Member $member): RedirectResponse
    {
        abort_unless($member->is_quarantine, 404);

        $this->members->approve($member);

        return redirect()->route('members.show', $member)->with('status', 'Aanmelding is verwerkt; de contributiefactuur is aangemaakt.');
    }
}
