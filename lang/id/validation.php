<?php

/*
|--------------------------------------------------------------------------
| Pesan Validasi (Bahasa Indonesia)
|--------------------------------------------------------------------------
| Aturan yang tidak tercantum di sini akan memakai pesan bahasa Inggris
| bawaan Laravel (fallback_locale).
*/

return [
    'array' => ':attribute harus berupa daftar.',
    'between' => [
        'numeric' => ':attribute harus bernilai antara :min sampai :max.',
        'string' => ':attribute harus terdiri dari :min sampai :max karakter.',
    ],
    'boolean' => ':attribute harus bernilai benar atau salah.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'current_password' => 'Password saat ini salah.',
    'different' => ':attribute harus berbeda dengan :other.',
    'distinct' => ':attribute memiliki nilai yang duplikat.',
    'email' => ':attribute harus berupa alamat email yang valid.',
    'exists' => ':attribute yang dipilih tidak valid.',
    'file' => ':attribute harus berupa file.',
    'image' => ':attribute harus berupa gambar.',
    'in' => ':attribute yang dipilih tidak valid.',
    'integer' => ':attribute harus berupa angka bulat.',
    'max' => [
        'file' => 'Ukuran :attribute maksimal :max KB.',
        'numeric' => ':attribute maksimal bernilai :max.',
        'string' => ':attribute maksimal :max karakter.',
    ],
    'mimes' => ':attribute harus berupa file dengan format: :values.',
    'min' => [
        'array' => ':attribute minimal berisi :min item.',
        'numeric' => ':attribute minimal bernilai :min.',
        'string' => ':attribute minimal :min karakter.',
    ],
    'regex' => 'Format :attribute tidak valid.',
    'required' => ':attribute wajib diisi.',
    'starts_with' => ':attribute harus diawali dengan: :values.',
    'string' => ':attribute harus berupa teks.',
    'unique' => ':attribute sudah digunakan.',

    /*
    | Nama field yang ditampilkan di pesan error.
    */
    'attributes' => [
        'id' => 'kode domain',
        'code' => 'kode',
        'name' => 'nama',
        'description' => 'deskripsi',
        'domain_id' => 'domain',
        'process_code' => 'proses',
        'practice_code' => 'praktik',
        'level' => 'level',
        'question_text' => 'pertanyaan',
        'expected_evidence' => 'bukti yang diharapkan',
        'email' => 'email',
        'password' => 'password',
        'role' => 'role',
        'phone' => 'nomor telepon',
        'address' => 'alamat',
    ],
];
