<?php

namespace App\Http\Controllers;

use App\Http\Requests\BreedingNumberRequest;
use App\Models\BreedingNumber;
use App\Models\Member;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BreedingNumberController extends Controller
{
    /**
     * Lijst met kweeknummers; zoekt op nummer en toont optioneel alleen de gearchiveerde.
     */
    public function index(Request $request): View
    {
        $archived = $request->boolean('archived');
        $term = trim((string) $request->query('q'));

        $breedingNumbers = BreedingNumber::query()
            ->with('member')
            ->when($archived, fn ($query) => $query->onlyTrashed())
            ->when($term !== '', fn ($query) => $query->where('breeding_number', 'like', '%'.addcslashes($term, '%_\\').'%'))
            ->orderBy('breeding_number')
            ->paginate(15)
            ->withQueryString();

        return view('breeding-numbers.index', [
            'breedingNumbers' => $breedingNumbers,
            'archived' => $archived,
        ]);
    }

    /**
     * Formulier voor een nieuw kweeknummer; kiesbaar zijn alleen leden die er nog geen hebben.
     */
    public function create(): View
    {
        return view('breeding-numbers.create', ['members' => $this->membersWithoutBreedingNumber()]);
    }

    /**
     * Alle ooit uitgegeven kweeknummers samen, actief én gearchiveerd, op uitgiftejaar.
     */
    public function history(): View
    {
        $breedingNumbers = BreedingNumber::withTrashed()
            ->with('member')
            ->orderByDesc('issue_year')
            ->orderBy('breeding_number')
            ->paginate(20);

        return view('breeding-numbers.history', ['breedingNumbers' => $breedingNumbers]);
    }

    /**
     * Registreert een nieuw kweeknummer bij een lid.
     */
    public function store(BreedingNumberRequest $request): RedirectResponse
    {
        BreedingNumber::create($request->validated());

        return redirect()->route('breeding-numbers.index')->with('status', 'Kweeknummer is geregistreerd.');
    }

    /**
     * Formulier om nummer of uitgiftejaar te wijzigen; het lid ligt vast.
     */
    public function edit(BreedingNumber $breedingNumber): View
    {
        return view('breeding-numbers.edit', ['breedingNumber' => $breedingNumber->load('member')]);
    }

    /**
     * Wijzigt nummer of uitgiftejaar van een kweeknummer.
     */
    public function update(BreedingNumberRequest $request, BreedingNumber $breedingNumber): RedirectResponse
    {
        $breedingNumber->update($request->validated());

        return redirect()->route('breeding-numbers.index')->with('status', 'Kweeknummer is gewijzigd.');
    }

    /**
     * Archiveert een kweeknummer; geweigerd bij een actief NBvV-lid, want die moet er een hebben.
     */
    public function destroy(BreedingNumber $breedingNumber): RedirectResponse
    {
        if ($breedingNumber->member->is_active && $breedingNumber->member->memberType->is_nbvv_member) {
            return back()->withErrors(['delete' => 'Dit kweeknummer hoort bij een actief lid en kan niet worden verwijderd: een actief lid moet een kweeknummer hebben.']);
        }

        $breedingNumber->delete();

        return redirect()->route('breeding-numbers.index')->with('status', 'Kweeknummer is gearchiveerd.');
    }

    /**
     * Haalt een gearchiveerd kweeknummer terug.
     */
    public function restore(BreedingNumber $breedingNumber): RedirectResponse
    {
        $breedingNumber->restore();

        return redirect()->route('breeding-numbers.index', ['archived' => 1])->with('status', 'Kweeknummer is teruggehaald uit het archief.');
    }

    /**
     * Alleen actieve NBvV-leden komen in aanmerking, en maar één kweeknummer per lid.
     *
     * @return Collection<int, Member>
     */
    private function membersWithoutBreedingNumber()
    {
        return Member::query()
            ->where('is_active', true)
            ->whereHas('memberType', fn ($query) => $query->where('is_nbvv_member', true))
            ->whereDoesntHave('breedingNumber')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
    }
}
