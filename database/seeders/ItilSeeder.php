<?php

namespace Database\Seeders;

use App\Models\Domain;
use App\Models\ItilProcess;
use App\Models\ProcessAttribute;
use Illuminate\Database\Seeder;

/**
 * Data awal ITIL V3 sesuai ruang lingkup proposal:
 * domain Service Operation, 5 subdomain, dan 9 atribut proses.
 */
class ItilSeeder extends Seeder
{
    public function run(): void
    {
        Domain::updateOrCreate(['code' => 'SO'], [
            'name' => 'Service Operation',
            'description' => 'Tahapan siklus hidup ITIL V3 yang melibatkan aktivitas operasional untuk mendukung penyampaian layanan TI kepada pengguna secara efektif dan efisien.',
        ]);

        foreach ($this->processes() as $code => [$name, $description]) {
            ItilProcess::updateOrCreate(['code' => $code], [
                'domain_code' => 'SO',
                'name' => $name,
                'description' => $description,
            ]);
        }

        foreach ($this->attributes() as $code => [$name, $level, $order, $description]) {
            ProcessAttribute::updateOrCreate(['code' => $code], [
                'name' => $name,
                'level' => $level,
                'sort_order' => $order,
                'description' => $description,
            ]);
        }
    }

    /**
     * Subdomain Service Operation (BAB 2.5.4).
     */
    private function processes(): array
    {
        return [
            'EM' => ['Event Management', 'Proses mendeteksi dan mengelola setiap kejadian (event) pada infrastruktur TI, lalu menentukan tindakan penanganan yang sesuai berdasarkan kategori kejadian.'],
            'IM' => ['Incident Management', 'Proses pemulihan layanan TI ke kondisi normal sesegera mungkin ketika terjadi gangguan yang tidak terencana, guna meminimalkan dampak terhadap operasional bisnis.'],
            'PM' => ['Problem Management', 'Proses mengidentifikasi, menganalisis, dan mengatasi akar penyebab (root cause) insiden, serta mendokumentasikannya dalam Known Error Database (KEDB).'],
            'RF' => ['Request Fulfilment', 'Proses menangani permintaan layanan standar dari pengguna yang bersifat rutin dan telah terdefinisi dalam katalog layanan.'],
            'AM' => ['Access Management', 'Proses mengelola hak akses pengguna terhadap layanan, data, dan sistem TI secara terkontrol berdasarkan kebijakan keamanan informasi.'],
        ];
    }

    /**
     * Atribut proses: [nama, level, urutan, deskripsi]. Level 1 & 2 disimpan sebagai 2.
     */
    private function attributes(): array
    {
        return [
            'PP' => ['Process Performance', 2, 1, 'Sejauh mana tujuan proses tercapai melalui aktivitas dasar yang dilakukan.'],
            'PM' => ['Performance Management', 3, 1, 'Pengelolaan pelaksanaan proses secara terstruktur sesuai prosedur dan kriteria yang ditetapkan.'],
            'WPM' => ['Work Product Management', 3, 2, 'Pengelolaan produk kerja (catatan, alat, kategori, laporan) yang menjadi masukan dan keluaran proses.'],
            'PD' => ['Process Definition', 3, 3, 'Proses standar telah didefinisikan dan didokumentasikan, termasuk pemilik, tujuan, ruang lingkup, serta peran dan tanggung jawab.'],
            'PDep' => ['Process Deployment', 3, 4, 'Penerapan proses standar di organisasi, termasuk ketersediaan sumber daya dan interaksi antarproses (Level 3+).'],
            'PMeas' => ['Process Measurement', 4, 1, 'Pengukuran kinerja proses menggunakan indikator dan target yang ditetapkan.'],
            'PC' => ['Process Control', 4, 2, 'Pengendalian proses berdasarkan hasil pengukuran, termasuk tindakan korektif atas penyimpangan.'],
            'PI' => ['Process Innovation', 5, 1, 'Identifikasi peluang perbaikan dan inovasi proses berdasarkan hasil evaluasi.'],
            'PO' => ['Process Optimization', 5, 2, 'Penerapan perbaikan secara berkelanjutan untuk mengoptimalkan efektivitas dan efisiensi proses.'],
        ];
    }
}
