<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use App\Models\Member;
use App\Models\MemberType;
use App\Services\MemberService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MemberController extends Controller
{
    public function __construct(private MemberService $members) {}

    /**
     * Ledenlijst met zoeken en filters op lidsoort, status en archief.
     */
    public function index(Request $request): View
    {
        $members = $this->filteredMembers($request)
            ->paginate(15)
            ->withQueryString();

        return view('members.index', [
            'members' => $members,
            'memberTypes' => MemberType::orderBy('name')->get(),
            'archived' => $request->boolean('archived'),
        ]);
    }

    /**
     * Exporteert de gefilterde ledenlijst als CSV (puntkomma-gescheiden, met BOM voor Excel).
     */
    public function export(Request $request): StreamedResponse
    {
        $members = $this->filteredMembers($request)->get();

        return response()->streamDownload(function () use ($members) {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF"); // BOM, zodat Excel het bestand als UTF-8 herkent.
            fputcsv($output, ['Naam', 'Lidsoort', 'Adres', 'Postcode', 'Woonplaats', 'E-mail', 'Geboortedatum', 'Kweeknummer', 'Status'], separator: ';');

            foreach ($members as $member) {
                fputcsv($output, [
                    $member->full_name,
                    $member->memberType->name,
                    $member->address->full_street,
                    $member->address->postal_code,
                    $member->address->city,
                    $member->email,
                    $member->birth_date->format('d-m-Y'),
                    $member->nbvv_number ?? '',
                    $member->trashed() ? 'Gearchiveerd' : match ($member->status) {
                        Member::STATUS_ACTIVE => 'Actief',
                        Member::STATUS_QUARANTINE => 'In quarantaine',
                        default => 'Inactief',
                    },
                ], separator: ';');
            }

            fclose($output);
        }, 'leden-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv']);
    }

    /**
     * Gedeelde query voor lijst en export, zodat die altijd dezelfde selectie tonen.
     */
    private function filteredMembers(Request $request): Builder
    {
        return Member::query()
            ->with(['memberType', 'address', 'breedingNumber'])
            ->when($request->boolean('archived'), fn ($query) => $query->onlyTrashed())
            ->search($request->query('q'))
            ->ofType($request->integer('member_type_id'))
            ->withStatus($request->query('status'))
            ->orderBy('last_name')
            ->orderBy('first_name');
    }

    /**
     * Formulier voor een nieuw lid.
     */
    public function create(): View
    {
        return view('members.create', ['memberTypes' => MemberType::orderBy('name')->get()]);
    }

    /**
     * Voegt een lid toe via MemberService (lid, adres en kweeknummer in één transactie).
     */
    public function store(MemberRequest $request): RedirectResponse
    {
        $member = $this->members->create($request->validated());

        return redirect()->route('members.show', $member)->with('status', 'Lid is toegevoegd.');
    }

    /**
     * Detailpagina met adres, kweeknummer en facturen; ook voor gearchiveerde leden.
     */
    public function show(Member $member): View
    {
        $member->load(['memberType', 'address', 'breedingNumber', 'invoices']);

        return view('members.show', ['member' => $member]);
    }

    /**
     * Formulier om een lid te wijzigen.
     */
    public function edit(Member $member): View
    {
        $member->load(['address', 'breedingNumber']);

        return view('members.edit', [
            'member' => $member,
            'memberTypes' => MemberType::orderBy('name')->get(),
        ]);
    }

    /**
     * Wijzigt lid, adres en kweeknummer via MemberService.
     */
    public function update(MemberRequest $request, Member $member): RedirectResponse
    {
        $this->members->update($member, $request->validated());

        return redirect()->route('members.show', $member)->with('status', 'Lid is gewijzigd.');
    }

    /**
     * Archiveert een lid (soft delete) in plaats van het te verwijderen.
     */
    public function destroy(Member $member): RedirectResponse
    {
        $this->members->archive($member);

        return redirect()->route('members.index')->with('status', "{$member->full_name} is gearchiveerd.");
    }

    /**
     * Haalt een gearchiveerd lid terug uit het archief.
     */
    public function restore(Member $member): RedirectResponse
    {
        $this->members->restore($member);

        return redirect()->route('members.show', $member)->with('status', "{$member->full_name} is teruggehaald uit het archief.");
    }

    /**
     * Keurt een aanmelding in quarantaine goed; start de contributiefactuur.
     */
    public function approve(Member $member): RedirectResponse
    {
        abort_unless($member->is_quarantine, 404);

        // Een jeugd- of volwassen lid mag niet actief worden zonder kweeknummer.
        if ($member->memberType->is_nbvv_member && ! $member->breedingNumber) {
            return back()->withErrors(['approve' => 'Ken eerst een kweeknummer toe via "Wijzigen" voordat je deze aanmelding goedkeurt.']);
        }

        $this->members->approve($member);

        return redirect()->route('members.show', $member)->with('status', 'Aanmelding is verwerkt; de contributiefactuur is aangemaakt.');
    }
}
