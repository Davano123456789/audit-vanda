<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\ProsesRequest;
use App\Models\Domain;
use App\Models\ItilProcess;
use Illuminate\Http\Request;

/**
 * Process Category: subdomain Service Operation (EM, IM, PM, RF, AM).
 */
class ProsesController extends Controller
{
    public function index(Request $request)
    {
        $processes = ItilProcess::with('domain')
            ->withCount('questions')
            ->when($request->domain, fn ($query, $domain) => $query->where('domain_code', $domain))
            ->when($request->q, fn ($query, $q) => $query->where(fn ($w) => $w
                ->where('code', 'like', "%{$q}%")
                ->orWhere('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%")))
            ->orderBy('code')
            ->paginate(10)
            ->withQueryString();

        return view('master.proses.index', [
            'processes' => $processes,
            'domainOptions' => $this->domainOptions(),
        ]);
    }

    public function create()
    {
        return view('master.proses.create', [
            'proses' => new ItilProcess(['domain_code' => Domain::orderBy('code')->value('code')]),
            'domainOptions' => $this->domainOptions(),
        ]);
    }

    public function store(ProsesRequest $request)
    {
        ItilProcess::create($request->validated());

        return redirect()->route('master.proses.index')->with('success', 'Data saved. Process berhasil ditambahkan.');
    }

    public function edit(ItilProcess $proses)
    {
        return view('master.proses.edit', [
            'proses' => $proses,
            'domainOptions' => $this->domainOptions(),
        ]);
    }

    public function update(ProsesRequest $request, ItilProcess $proses)
    {
        $proses->update($request->validated());

        return redirect()->route('master.proses.index')->with('success', 'Data saved. Process berhasil diperbarui.');
    }

    public function destroy(ItilProcess $proses)
    {
        if ($proses->assessmentProcesses()->exists()) {
            return back()->with('error', "Process {$proses->code} sudah dipakai di assessment sehingga tidak dapat dihapus.");
        }

        if ($proses->questions()->exists()) {
            return back()->with('error', "Process {$proses->code} masih memiliki pertanyaan, hapus pertanyaannya terlebih dahulu.");
        }

        $proses->delete();

        return redirect()->route('master.proses.index')->with('success', 'Process berhasil dihapus.');
    }

    /**
     * Pilihan domain untuk dropdown, contoh: ['SO' => 'SO - Service Operation'].
     */
    private function domainOptions(): array
    {
        return Domain::orderBy('code')->get()
            ->mapWithKeys(fn ($d) => [$d->code => "{$d->code} - {$d->name}"])
            ->all();
    }
}
