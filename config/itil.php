<?php

/*
|--------------------------------------------------------------------------
| Konfigurasi Penilaian Maturity Level ITIL V3
|--------------------------------------------------------------------------
| Sumber: proposal skripsi, Tabel 2.1 (Tingkatan Maturity Level),
| Tabel 2.2 (Rating Level), Tabel 3.3 (Keterangan Kode), dan Gambar 3.3 (Flowchart Sistem).
*/

return [

    /*
    | Tingkatan maturity level (Tabel 2.1).
    */
    'levels' => [
        0 => [
            'name' => 'Non-Existent',
            'color' => 'red',
            'label' => 'Tidak Ada',
            'description' => 'Proses tidak dilakukan atau tidak mencapai tujuannya. Tidak ada bukti pendekatan yang sistematis dan tidak ada kesadaran terhadap perlunya proses tersebut dalam organisasi.',
        ],
        1 => [
            'name' => 'Initial',
            'color' => 'orange',
            'label' => 'Awal',
            'description' => 'Proses sudah mulai dilakukan, tetapi pelaksanaannya masih bersifat tidak terencana dan bergantung pada kemampuan individu. Belum terdapat standar atau prosedur yang diterapkan secara konsisten dalam organisasi.',
        ],
        2 => [
            'name' => 'Repeatable',
            'color' => 'blue',
            'label' => 'Dapat Diulang',
            'description' => 'Proses telah dilakukan secara berulang dengan pola pelaksanaan yang relatif sama. Namun, proses belum terdokumentasi secara formal dan penerapannya masih bergantung pada pengalaman individu.',
        ],
        3 => [
            'name' => 'Defined',
            'color' => 'brand',
            'label' => 'Terdefinisi',
            'description' => 'Proses telah ditetapkan, didokumentasikan, dan distandarkan secara formal. Prosedur yang telah ditetapkan dipahami dan diterapkan oleh anggota organisasi secara konsisten.',
        ],
        4 => [
            'name' => 'Managed',
            'color' => 'amber',
            'label' => 'Dikelola',
            'description' => 'Proses telah direncanakan, dilaksanakan, dipantau, dan dikendalikan secara teratur. Pelaksanaan proses didukung oleh dokumentasi dan pengukuran sehingga kinerja proses dapat dipantau dan dievaluasi.',
        ],
        5 => [
            'name' => 'Optimizing',
            'color' => 'violet',
            'label' => 'Dioptimalkan',
            'description' => 'Proses telah berjalan secara optimal dan dilakukan perbaikan secara berkelanjutan. Hasil pengukuran dan evaluasi digunakan sebagai dasar untuk meningkatkan efektivitas dan efisiensi proses.',
        ],
    ],

    /*
    | Level pertama dan terakhir yang dinilai. Level 1 & 2 digabung dan disimpan sebagai 2.
    */
    'start_level' => 2,
    'max_level' => 5,

    /*
    | Skala rating level (Tabel 2.2). Diurutkan dari batas tertinggi.
    | "min" = batas bawah persentase, "inclusive" = apakah batas bawah ikut masuk.
    | F memakai >= 85% sesuai flowchart sistem (Gambar 3.3).
    */
    'ratings' => [
        'F' => ['name' => 'Fully Achieved', 'label' => 'Tercapai penuh', 'min' => 85, 'inclusive' => true, 'range' => '≥ 85% – 100%', 'color' => 'green'],
        'L' => ['name' => 'Largely Achieved', 'label' => 'Sebagian besar tercapai', 'min' => 50, 'inclusive' => false, 'range' => '> 50% – 85%', 'color' => 'blue'],
        'P' => ['name' => 'Partially Achieved', 'label' => 'Sebagian tercapai', 'min' => 15, 'inclusive' => false, 'range' => '> 15% – 50%', 'color' => 'amber'],
        'N' => ['name' => 'Not Achieved', 'label' => 'Tidak tercapai', 'min' => 0, 'inclusive' => true, 'range' => '0% – 15%', 'color' => 'red'],
    ],

    /*
    | Jumlah angka desimal nilai kematangan domain.
    */
    'maturity_decimals' => 2,

    /*
    | Interval nilai kematangan ke maturity level (Tabel 2.1).
    | "max" = batas atas interval (inklusif).
    */
    'maturity_scale' => [
        ['max' => 0.50, 'level' => 0],
        ['max' => 1.50, 'level' => 1],
        ['max' => 2.50, 'level' => 2],
        ['max' => 3.50, 'level' => 3],
        ['max' => 4.50, 'level' => 4],
        ['max' => 5.00, 'level' => 5],
    ],
];
