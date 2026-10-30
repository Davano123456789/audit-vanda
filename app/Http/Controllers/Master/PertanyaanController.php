<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\PertanyaanRequest;
use App\Models\ItilProcess;
use App\Models\ProcessAttribute;
use App\Models\Question;
use Illuminate\Http\Request;

class PertanyaanController extends Controller
{
    public function index(Request $request)
    {
        $questions = Question::with(['process', 'attribute'])
            ->select('itil_questions.*')
            ->join('process_attributes', 'process_attributes.code', '=', 'itil_questions.attribute_code')
            ->when($request->proses, fn ($query, $proses) => $query->where('itil_questions.process_code', $proses))
            ->when($request->atribut, fn ($query, $atribut) => $query->where('itil_questions.attribute_code', $atribut))
            ->when($request->level, fn ($query, $level) => $query->where('process_attributes.level', $level))
            ->when($request->q, fn ($query, $q) => $query->where('itil_questions.question_text', 'like', "%{$q}%"))
            ->orderBy('itil_questions.process_code')
            ->orderBy('process_attributes.level')
            ->orderBy('process_attributes.sort_order')
            ->orderBy('itil_questions.number')
            ->paginate(25)
            ->withQueryString();

        return view('master.pertanyaan.index', [
            'questions' => $questions,
            'processes' => ItilProcess::orderBy('code')->get(),
            'attributes' => $this->attributes(),
            'summary' => $this->summary(),
        ]);
    }

    public function create(Request $request)
    {
        return view('master.pertanyaan.create', [
            'pertanyaan' => new Question([
                'process_code' => $request->proses,
                'attribute_code' => $request->atribut,
            ]),
            'processes' => ItilProcess::orderBy('code')->get(),
            'attributes' => $this->attributes(),
        ]);
    }

    public function store(PertanyaanRequest $request)
    {
        $data = $request->validated();
        // Nomor kosong = diletakkan di urutan terakhir atribut tersebut
        $data['number'] ??= $this->nextNumber($data['process_code'], $data['attribute_code']);

        $question = Question::create($data);

        // Tombol "Simpan & Tambah Lagi": kembali ke form dengan process dan atribut yang sama
        if ($request->boolean('tambah_lagi')) {
            return redirect()
                ->route('master.pertanyaan.create', ['proses' => $question->process_code, 'atribut' => $question->attribute_code])
                ->with('success', 'Data saved. Pertanyaan berhasil ditambahkan.');
        }

        return redirect()
            ->route('master.pertanyaan.index', ['proses' => $question->process_code, 'atribut' => $question->attribute_code])
            ->with('success', 'Data saved. Pertanyaan berhasil ditambahkan.');
    }

    public function edit(Question $pertanyaan)
    {
        return view('master.pertanyaan.edit', [
            'pertanyaan' => $pertanyaan,
            'processes' => ItilProcess::orderBy('code')->get(),
            'attributes' => $this->attributes(),
        ]);
    }

    public function update(PertanyaanRequest $request, Question $pertanyaan)
    {
        $data = $request->validated();
        $moved = $pertanyaan->process_code !== $data['process_code'] || $pertanyaan->attribute_code !== $data['attribute_code'];
        $old = [$pertanyaan->process_code, $pertanyaan->attribute_code];

        // Pindah ke process/atribut lain tanpa nomor = diletakkan di urutan terakhir
        $data['number'] ??= $moved ? $this->nextNumber($data['process_code'], $data['attribute_code']) : $pertanyaan->number;

        $pertanyaan->update($data);

        if ($moved) {
            $this->renumber(...$old);
        }

        return redirect()
            ->route('master.pertanyaan.index', ['proses' => $pertanyaan->process_code, 'atribut' => $pertanyaan->attribute_code])
            ->with('success', 'Data saved. Pertanyaan berhasil diperbarui.');
    }

    public function destroy(Question $pertanyaan)
    {
        if ($pertanyaan->answers()->exists()) {
            return back()->with('error', 'Pertanyaan ini sudah dijawab pada assessment sehingga tidak dapat dihapus.');
        }

        $pertanyaan->delete();
        $this->renumber($pertanyaan->process_code, $pertanyaan->attribute_code);

        return back()->with('success', 'Pertanyaan berhasil dihapus.');
    }

    private function attributes()
    {
        return ProcessAttribute::orderBy('level')->orderBy('sort_order')->get();
    }

    private function nextNumber(string $processCode, string $attributeCode): int
    {
        return (int) Question::where('process_code', $processCode)
            ->where('attribute_code', $attributeCode)
            ->max('number') + 1;
    }

    /**
     * Rapikan nomor urut 1, 2, 3, ... setelah ada pertanyaan yang dihapus atau dipindah.
     */
    private function renumber(string $processCode, string $attributeCode): void
    {
        Question::where('process_code', $processCode)
            ->where('attribute_code', $attributeCode)
            ->orderBy('number')
            ->orderBy('id')
            ->get()
            ->each(function ($question, $index) {
                if ($question->number !== $index + 1) {
                    $question->update(['number' => $index + 1]);
                }
            });
    }

    /**
     * Jumlah pertanyaan per process per atribut, contoh: ['IM' => ['PP' => 6, 'PM' => 15, ...]].
     */
    private function summary(): array
    {
        return Question::selectRaw('process_code, attribute_code, COUNT(*) as total')
            ->groupBy('process_code', 'attribute_code')
            ->get()
            ->groupBy('process_code')
            ->map(fn ($rows) => $rows->pluck('total', 'attribute_code')->all())
            ->all();
    }
}
