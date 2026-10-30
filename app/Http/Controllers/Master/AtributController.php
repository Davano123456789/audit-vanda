<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\AtributRequest;
use App\Models\ProcessAttribute;
use Illuminate\Http\Request;

/**
 * Process Attribute yang dinilai pada tiap level (PP, PM, WPM, PD, PDep, PMeas, PC, PI, PO).
 */
class AtributController extends Controller
{
    public function index(Request $request)
    {
        $attributes = ProcessAttribute::withCount('questions')
            ->when($request->level, fn ($query, $level) => $query->where('level', $level))
            ->when($request->q, fn ($query, $q) => $query->where(fn ($w) => $w
                ->where('code', 'like', "%{$q}%")
                ->orWhere('name', 'like', "%{$q}%")))
            ->orderBy('level')
            ->orderBy('sort_order')
            ->get();

        return view('master.atribut.index', [
            'attributesByLevel' => $attributes->groupBy('level'),
            'total' => $attributes->count(),
        ]);
    }

    public function create(Request $request)
    {
        $level = (int) $request->level ?: null;

        return view('master.atribut.create', [
            'atribut' => new ProcessAttribute([
                'level' => $level,
                'sort_order' => $level ? ProcessAttribute::where('level', $level)->max('sort_order') + 1 : 1,
            ]),
        ]);
    }

    public function store(AtributRequest $request)
    {
        ProcessAttribute::create($request->validated());

        return redirect()->route('master.atribut.index')->with('success', 'Data saved. Process attribute berhasil ditambahkan.');
    }

    public function edit(ProcessAttribute $atribut)
    {
        return view('master.atribut.edit', compact('atribut'));
    }

    public function update(AtributRequest $request, ProcessAttribute $atribut)
    {
        $atribut->update($request->validated());

        return redirect()->route('master.atribut.index')->with('success', 'Data saved. Process attribute berhasil diperbarui.');
    }

    public function destroy(ProcessAttribute $atribut)
    {
        if ($atribut->questions()->exists()) {
            return back()->with('error', "Atribut {$atribut->code} masih memiliki pertanyaan, hapus pertanyaannya terlebih dahulu.");
        }

        $atribut->delete();

        return redirect()->route('master.atribut.index')->with('success', 'Process attribute berhasil dihapus.');
    }
}
