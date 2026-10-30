<?php

/*
| Daftar pertanyaan lembar kerja ITIL V3 domain Service Operation.
| Sumber: proposal skripsi, Tabel 3.4 - 3.48 (Lembar Kerja EM, IM, PM, RF, AM).
| Struktur: [kode subdomain => [kode atribut => [pertanyaan, ...]]]
*/

return [
    // Event Management
    'EM' => [
        // Process Performance (Level 1 & 2)
        'PP' => [
            'Bagaimana perusahaan mengetahui adanya kejadian penting pada layanan TI atau sistem yang digunakan, dan apakah kejadian tersebut dicatat?',
            'Bagaimana perusahaan menentukan apakah suatu kejadian kondisi memiliki tingkat kepentingan tertentu dan perlu ditindaklanjuti?',
            'Jika suatu kejadian dianggap penting, bagaimana perusahaan menentukan tindakan yang harus dilakukan dan kepada siapa informasi mengenai kejadian tersebut disampaikan?',
        ],
        // Performance Management (Level 3)
        'PM' => [
            'Bagaimana perusahaan menentukan jenis kejadian yang terjadi dan informasi apa saja yang perlu dicatat atau disampaikan?',
            'Apakah perusahaan menggunakan alat atau mekanisme tertentu untuk menerima dan menyampaikan pemberitahuan ketika terjadi kejadian?',
            'Bagaimana proses pendeteksian, penyaringan, dan pencatatan kejadian dilakukan oleh perusahaan?',
            'Apakah perusahaan memiliki kriteria tertentu untuk menentukan apakah suatu kejadian kondisi penting dan perlu mendapatkan tindakan lebih lanjut?',
            'Bagaimana perusahaan menentukan tindakan atau respons yang sesuai berdasarkan tingkat kepentingan kejadian?',
            'Setelah kejadian penting ditangani, apakah perusahaan melakukan pemeriksaan untuk memastikan bahwa penanganannya sudah sesuai?',
            'Apakah perusahaan memantau tren atau pola kejadian yang terjadi, misalnya berdasarkan jenis atau frekuensi kejadian?',
            'Bagaimana proses penutupan kejadian dilakukan dan apakah hasil penanganannya dicatat sebagai bagian dari riwayat kejadian?',
        ],
        // Work Product Management (Level 3)
        'WPM' => [
            'Apakah perusahaan menggunakan alat pemantauan yang dapat memberikan notifikasi ketika terjadi kejadian pada layanan atau sistem TI?',
            'Apakah setiap kejadian kondisi yang terjadi memiliki catatan atau riwayat yang disimpan oleh perusahaan?',
            'Apakah perusahaan memiliki alat yang digunakan untuk mendukung Event Management dan apakah alat tersebut terhubung dengan proses atau sistem lainnya?',
            'Apakah terdapat aturan yang digunakan untuk menentukan kejadian mana yang perlu diteruskan dan kejadian mana yang dapat diabaikan?',
            'Apakah perusahaan memiliki kategori tertentu untuk mengelompokkan kejadian kondisi yang terjadi?',
            'Apakah terdapat aturan yang menghubungkan jenis kejadian tertentu dengan tindakan atau respons yang harus dilakukan?',
            'Apakah target tingkat layanan dipantau melalui proses atau aktivitas Event Management?',
            'Apakah perusahaan memiliki batas kapasitas tertentu yang dipantau untuk mengetahui adanya kejadian pada layanan atau sistem TI?',
            'Apakah target ketersediaan layanan dipantau dan apakah perusahaan dapat mengetahui ketika target tersebut tidak tercapai?',
            'Apakah kejadian yang berkaitan dengan keamanan atau akses pengguna dipantau sebagai bagian dari Event Management?',
            'Apakah perusahaan memiliki prosedur dalam mengenali, mencatat, melakukan eskalasi, dan mengomunikasikan kejadian?',
            'Apakah perusahaan membuat laporan mengenai tren atau pola kejadian kondisi yang terjadi?',
            'Apakah perusahaan membuat laporan apabila terjadi kejadian kondisi yang menyebabkan target, KPI, atau SLA tidak tercapai?',
        ],
        // Process Definition (Level 3)
        'PD' => [
            'Siapa yang ditetapkan sebagai pemilik atau penanggung jawab proses Event Management di perusahaan?',
            'Apakah tujuan, sasaran, ruang lingkup, pemicu, input, dan output Event Management telah ditetapkan dan didokumentasikan?',
            'Apakah tahapan atau urutan aktivitas Event Management telah ditentukan dan didokumentasikan?',
            'Bagaimana hubungan Event Management dengan proses lain, seperti Incident Management atau Problem Management, ditentukan?',
            'Apakah peran dan tanggung jawab setiap pihak dalam Event Management telah ditentukan?',
            'Apakah kompetensi yang diperlukan untuk menjalankan Event Management telah ditentukan?',
            'Apakah infrastruktur dan lingkungan kerja yang diperlukan untuk menjalankan Event Management telah ditentukan?',
            'Apakah terdapat metode atau indikator seperti KPI dan CSF yang digunakan untuk memantau efektivitas dan kesesuaian Event Management?',
            'Apakah format, isi, dan frekuensi laporan manajemen layanan telah disepakati dengan pihak yang berkepentingan?',
        ],
        // Process Deployment (Level 3+)
        'PDep' => [
            'Apakah pemilik proses menjalankan peran dan tanggung jawabnya dalam Event Management?',
            'Bagaimana proses standar Event Management diterapkan dan disesuaikan dengan kondisi atau kebutuhan perusahaan?',
            'Apakah pelaksanaan Event Management mengikuti proses standar atau model proses yang telah ditentukan?',
            'Bagaimana interaksi Event Management dengan proses lain dilakukan dalam kegiatan operasional?',
            'Apakah peran dan tanggung jawab dalam Event Management telah ditetapkan dan dikomunikasikan kepada pihak yang terkait?',
            'Apakah pihak yang menjalankan Event Management memiliki kompetensi yang dibutuhkan atau mendapatkan pelatihan yang sesuai?',
            'Apakah sumber daya manusia yang dibutuhkan untuk menjalankan Event Management tersedia dan dialokasikan?',
            'Apakah informasi yang dibutuhkan dalam menjalankan Event Management tersedia dan digunakan oleh pihak terkait?',
            'Apakah infrastruktur yang diperlukan untuk mendukung Event Management tersedia, digunakan, dan dipelihara?',
            'Apakah data mengenai pelaksanaan Event Management dikumpulkan dan dianalisis untuk mengetahui efektivitas serta peluang perbaikan proses?',
            'Apakah laporan manajemen layanan terkait Event Management dibuat dan disampaikan kepada pihak yang berkepentingan?',
        ],
        // Process Measurement (Level 4)
        'PMeas' => [
            'Informasi apa yang dibutuhkan perusahaan untuk mengetahui kinerja Event Management dan bagaimana kaitannya dengan tujuan bisnis?',
            'Bagaimana perusahaan menentukan tujuan pengukuran kinerja Event Management?',
            'Apakah perusahaan memiliki sasaran kinerja Event Management yang dapat diukur secara kuantitatif?',
            'Apakah sasaran kinerja yang ditetapkan diverifikasi atau disepakati bersama manajemen dan pemilik proses?',
            'Bagaimana metode pengukuran dan pengumpulan data kinerja Event Management ditentukan?',
            'Apakah terdapat mekanisme untuk memastikan hasil pengukuran Event Management sudah benar dan dapat dipercaya?',
            'Apakah hasil pengukuran kinerja Event Management dikumpulkan dan diolah secara berkala?',
            'Bagaimana hasil pengukuran digunakan untuk mengetahui apakah tujuan kinerja Event Management telah tercapai?',
        ],
        // Process Control (Level 4)
        'PC' => [
            'Teknik atau metode apa yang digunakan perusahaan untuk menganalisis dan mengendalikan kinerja Event Management?',
            'Apakah perusahaan memiliki batas tertentu untuk menentukan apakah kinerja Event Management masih berada dalam kondisi normal?',
            'Bagaimana data hasil pengukuran dianalisis untuk mengetahui penyebab terjadinya penyimpangan atau variasi kinerja?',
            'Apa tindakan yang dilakukan apabila ditemukan penyimpangan atau penyebab tertentu yang memengaruhi kinerja Event Management?',
            'Setelah dilakukan tindakan perbaikan, apakah batas pengendalian kinerja ditinjau atau ditetapkan kembali apabila diperlukan?',
        ],
        // Process Innovation (Level 5)
        'PI' => [
            'Bagaimana perusahaan menentukan tujuan perbaikan Event Management agar mendukung tujuan bisnis?',
            'Bagaimana hasil pengukuran digunakan untuk menemukan variasi atau potensi masalah dalam kinerja Event Management?',
            'Apakah perusahaan menggunakan inovasi atau praktik terbaik industri sebagai sumber peluang perbaikan Event Management?',
            'Apakah teknologi baru atau konsep proses baru pernah dipertimbangkan sebagai peluang untuk meningkatkan Event Management?',
            'Bagaimana perusahaan menentukan, memprioritaskan, menerapkan, dan mengevaluasi strategi perbaikan Event Management dalam jangka panjang?',
        ],
        // Process Optimization (Level 5)
        'PO' => [
            'Bagaimana perusahaan menilai dampak dari setiap perubahan yang dilakukan terhadap Event Management berdasarkan tujuan proses yang telah ditetapkan?',
            'Bagaimana perusahaan mengelola penerapan perubahan agar tidak menimbulkan gangguan terhadap kinerja Event Management?',
            'Setelah perubahan diterapkan, bagaimana perusahaan mengevaluasi apakah perubahan tersebut efektif berdasarkan kinerja proses dan tujuan bisnis?',
        ],
    ],
    // Incident Management
    'IM' => [
        // Process Performance (Level 1 & 2)
        'PP' => [
            'Apakah setiap insiden yang terjadi dicatat, termasuk tindakan atau penyelesaian yang telah dilakukan?',
            'Bagaimana perusahaan menentukan prioritas penanganan insiden agar sesuai dengan dampaknya terhadap kegiatan bisnis?',
            'Apakah terdapat batas waktu penyelesaian insiden yang ditetapkan dalam SLA, dan apakah penyelesaian insiden biasanya mengikuti batas waktu tersebut?',
            'Bagaimana perusahaan menangani insiden untuk mengurangi dampaknya terhadap kegiatan operasional atau bisnis?',
            'Apakah setiap insiden dipantau atau dilacak sejak pertama kali dilaporkan sampai insiden tersebut dinyatakan selesai?',
            'Bagaimana perusahaan memberikan informasi kepada pengguna atau pihak terkait mengenai perkembangan penanganan suatu insiden?',
        ],
        // Performance Management (Level 3)
        'PM' => [
            'Bagaimana perusahaan mengetahui adanya insiden dan bagaimana insiden tersebut dicatat ketika pertama kali dilaporkan?',
            'Melalui saluran atau pihak mana pengguna biasanya melaporkan insiden yang dialami?',
            'Apakah informasi atau catatan insiden diperbarui selama proses penanganan berlangsung?',
            'Bagaimana perusahaan mengelompokkan atau menentukan kategori dari setiap insiden yang terjadi?',
            'Bagaimana perusahaan menentukan tingkat prioritas suatu insiden berdasarkan dampak dan urgensinya?',
            'Bagaimana pemeriksaan atau diagnosis awal dilakukan ketika suatu insiden pertama kali diterima?',
            'Dalam kondisi seperti apa suatu insiden perlu diteruskan atau dieskalasikan kepada tim atau pihak lain?',
            'Bagaimana proses investigasi dilakukan untuk mengetahui penyebab atau solusi dari suatu insiden?',
            'Bagaimana perusahaan menentukan solusi yang tepat dan apakah solusi tersebut diuji sebelum diterapkan?',
            'Bagaimana perusahaan menerapkan solusi terhadap insiden agar tidak menimbulkan gangguan tambahan pada layanan TI?',
            'Bagaimana perusahaan menginformasikan perkembangan penanganan insiden kepada pengguna atau pihak yang berkepentingan?',
            'Apa saja yang diperiksa atau dipastikan sebelum suatu insiden dinyatakan selesai dan ditutup?',
            'Apakah perusahaan memiliki cara atau prosedur khusus untuk menangani insiden yang berdampak besar terhadap layanan atau bisnis?',
            'Bagaimana perusahaan mengidentifikasi dan menangani insiden yang berkaitan dengan keamanan informasi?',
            'Apakah insiden besar atau insiden keamanan ditinjau kembali oleh manajemen setelah penanganannya selesai?',
        ],
        // Work Product Management (Level 3)
        'WPM' => [
            'Apakah catatan seluruh insiden yang dilaporkan disimpan dan dapat ditelusuri kembali apabila diperlukan?',
            'Apakah perusahaan memiliki basis pengetahuan yang berisi informasi atau solusi dari insiden yang pernah terjadi, dan apakah informasi tersebut diperbarui?',
            'Apakah perusahaan menggunakan alat atau sistem tertentu untuk membantu pencatatan dan pengelolaan insiden?',
            'Apakah perusahaan memiliki kategori atau kode tertentu untuk mengelompokkan insiden?',
            'Apakah tingkat prioritas insiden memiliki kategori atau kode tertentu yang digunakan dalam proses penanganan?',
            'Apakah perusahaan melakukan pengukuran atau survei kepuasan pengguna setelah insiden ditangani?',
            'Apakah target waktu penyelesaian insiden telah ditentukan dan disepakati melalui SLA?',
            'Apakah terdapat prosedur atau model penanganan khusus untuk jenis insiden tertentu yang sering terjadi?',
        ],
        // Process Definition (Level 3)
        'PD' => [
            'Siapa yang ditetapkan sebagai pemilik atau penanggung jawab proses Incident Management di perusahaan?',
            'Apakah tujuan, sasaran, ruang lingkup, pemicu, input, dan output Incident Management telah ditetapkan dan didokumentasikan?',
            'Apakah tahapan atau urutan aktivitas Incident Management telah ditentukan dan didokumentasikan?',
            'Bagaimana hubungan Incident Management dengan proses lain, seperti Problem Management atau Change Management, ditentukan?',
            'Apakah peran dan tanggung jawab setiap pihak dalam Incident Management telah ditentukan?',
            'Apakah kompetensi yang dibutuhkan untuk menjalankan Incident Management telah diidentifikasi?',
            'Apakah infrastruktur dan lingkungan kerja yang dibutuhkan untuk menjalankan Incident Management telah ditentukan?',
            'Apakah perusahaan memiliki metode atau indikator seperti KPI/CSF untuk memantau efektivitas dan kesesuaian Incident Management?',
            'Apakah format, isi, dan frekuensi laporan manajemen layanan terkait Incident Management telah disepakati dengan pihak yang berkepentingan?',
        ],
        // Process Deployment (Level 3+)
        'PDep' => [
            'Apakah pemilik proses menjalankan peran dan tanggung jawabnya dalam Incident Management?',
            'Bagaimana proses standar Incident Management diterapkan dan disesuaikan dengan kebutuhan perusahaan?',
            'Apakah penanganan insiden dilakukan sesuai dengan proses atau prosedur standar yang telah ditetapkan?',
            'Bagaimana Incident Management berinteraksi dengan proses lain dalam kegiatan operasional?',
            'Apakah peran dan tanggung jawab dalam Incident Management telah ditetapkan dan dikomunikasikan kepada pihak terkait?',
            'Apakah pihak yang menangani insiden memiliki kompetensi yang diperlukan atau mendapatkan pelatihan yang sesuai?',
            'Apakah sumber daya manusia yang dibutuhkan untuk menangani insiden tersedia dan dialokasikan?',
            'Apakah informasi yang dibutuhkan untuk menangani insiden tersedia dan dapat digunakan oleh pihak terkait?',
            'Apakah infrastruktur atau alat yang digunakan untuk mendukung Incident Management tersedia, digunakan, dan dipelihara?',
            'Apakah data mengenai penanganan insiden dikumpulkan dan dianalisis untuk mengetahui efektivitas proses serta peluang perbaikan?',
            'Apakah laporan mengenai Incident Management dibuat dan disampaikan kepada pihak yang berkepentingan?',
        ],
        // Process Measurement (Level 4)
        'PMeas' => [
            'Informasi apa yang dibutuhkan perusahaan untuk mengetahui kinerja Incident Management dan kaitannya dengan tujuan bisnis?',
            'Bagaimana perusahaan menentukan tujuan pengukuran kinerja Incident Management?',
            'Apakah perusahaan memiliki sasaran kinerja Incident Management yang dapat diukur secara kuantitatif?',
            'Apakah sasaran kinerja tersebut diverifikasi atau disepakati bersama manajemen dan pemilik proses?',
            'Bagaimana metode pengukuran dan pengumpulan data kinerja Incident Management ditentukan?',
            'Apakah terdapat mekanisme untuk memastikan hasil pengukuran Incident Management dapat dipercaya dan sesuai dengan kondisi sebenarnya?',
            'Apakah hasil pengukuran kinerja Incident Management dikumpulkan dan diolah secara berkala?',
            'Bagaimana hasil pengukuran digunakan untuk mengetahui pencapaian tujuan kinerja Incident Management?',
        ],
        // Process Control (Level 4)
        'PC' => [
            'Teknik atau metode apa yang digunakan untuk menganalisis dan mengendalikan kinerja Incident Management?',
            'Apakah perusahaan memiliki batas tertentu untuk menentukan apakah kinerja Incident Management masih berada dalam kondisi normal?',
            'Bagaimana data hasil pengukuran dianalisis untuk mengetahui penyebab terjadinya penyimpangan atau variasi kinerja?',
            'Apa tindakan korektif yang dilakukan apabila ditemukan penyimpangan yang memengaruhi kinerja Incident Management?',
            'Setelah tindakan perbaikan dilakukan, apakah batas pengendalian kinerja ditinjau atau ditetapkan kembali apabila diperlukan?',
        ],
        // Process Innovation (Level 5)
        'PI' => [
            'Bagaimana perusahaan menentukan tujuan perbaikan Incident Management agar mendukung tujuan bisnis?',
            'Bagaimana hasil pengukuran digunakan untuk menemukan variasi atau potensi masalah dalam kinerja Incident Management?',
            'Apakah perusahaan menggunakan inovasi atau praktik terbaik industri sebagai sumber peluang perbaikan Incident Management?',
            'Apakah teknologi baru atau konsep proses baru pernah dipertimbangkan untuk meningkatkan Incident Management?',
            'Bagaimana perusahaan menentukan, memprioritaskan, menerapkan, dan mengevaluasi strategi perbaikan Incident Management dalam jangka panjang?',
        ],
        // Process Optimization (Level 5)
        'PO' => [
            'Bagaimana perusahaan menilai dampak dari perubahan yang dilakukan terhadap Incident Management berdasarkan tujuan proses yang telah ditetapkan?',
            'Bagaimana perusahaan mengelola penerapan perubahan agar tidak menimbulkan gangguan terhadap kinerja Incident Management?',
            'Setelah perubahan diterapkan, bagaimana perusahaan mengevaluasi efektivitas perubahan berdasarkan kinerja proses dan tujuan bisnis?',
        ],
    ],
    // Problem Management
    'PM' => [
        // Process Performance (Level 1 & 2)
        'PP' => [
            'Bagaimana perusahaan mengetahui adanya suatu masalah pada layanan TI dan apakah masalah tersebut dicatat setelah ditemukan?',
            'Bagaimana perusahaan menangani suatu masalah agar dampaknya terhadap kegiatan operasional atau bisnis dapat dikurangi?',
            'Bagaimana perusahaan menangani masalah yang menyebabkan insiden terjadi berulang agar kejadian tersebut tidak terus terulang?',
            'Jika suatu masalah belum dapat diselesaikan, bagaimana perusahaan mengurangi atau mengendalikan dampaknya terhadap layanan dan kegiatan bisnis?',
        ],
        // Performance Management (Level 3)
        'PM' => [
            'Bagaimana perusahaan mengetahui adanya masalah berdasarkan insiden atau laporan yang telah terjadi sebelumnya?',
            'Apakah perusahaan melakukan pencarian masalah secara proaktif berdasarkan tren, pola, atau riwayat gangguan yang terjadi?',
            'Apakah informasi mengenai masalah, seperti waktu ditemukan, kondisi yang terjadi, dan detail lainnya, dicatat oleh perusahaan?',
            'Bagaimana perusahaan menentukan kategori dari masalah yang ditemukan?',
            'Bagaimana perusahaan menentukan prioritas suatu masalah berdasarkan seberapa sering masalah terjadi dan seberapa besar dampaknya?',
            'Bagaimana perusahaan melakukan investigasi untuk menemukan akar penyebab dari suatu masalah?',
            'Jika akar masalah belum dapat segera diselesaikan, apakah perusahaan menyediakan solusi sementara atau workaround untuk mengurangi dampaknya?',
            'Apakah perusahaan memiliki pencatatan mengenai known error atau masalah yang sudah diketahui penyebab dan solusinya, dan apakah catatan tersebut dipelihara?',
            'Bagaimana perusahaan menerapkan solusi terhadap masalah, dan apakah perubahan yang diperlukan dikelola melalui proses perubahan apabila dibutuhkan?',
            'Bagaimana perusahaan menentukan bahwa suatu masalah sudah dapat ditutup dan apakah catatan masalah diperbarui setelah penyelesaian dilakukan?',
            'Apakah masalah yang berdampak besar mendapatkan tinjauan dari manajemen dan dilakukan evaluasi setelah solusi diterapkan?',
        ],
        // Work Product Management (Level 3)
        'WPM' => [
            'Apakah catatan masalah disimpan mulai dari masalah ditemukan sampai masalah tersebut diselesaikan dan ditutup?',
            'Apakah perusahaan memiliki basis pengetahuan atau Known Error Database (KEDB) yang berisi informasi mengenai masalah dan solusi yang pernah ditemukan, serta apakah informasi tersebut diperbarui?',
            'Apakah perusahaan menggunakan alat atau sistem untuk mendukung Problem Management, dan apakah alat tersebut terhubung dengan pencatatan insiden?',
            'Apakah perusahaan memiliki kategori atau klasifikasi tertentu untuk mengelompokkan masalah?',
            'Apakah perusahaan memiliki tingkatan atau kode prioritas yang digunakan untuk menentukan penanganan masalah?',
            'Apakah perusahaan menggunakan data tren atau pola insiden sebagai bahan untuk mengidentifikasi dan menganalisis masalah?',
            'Apakah sistem atau pencatatan yang digunakan memungkinkan hubungan antara insiden, masalah, known error, dan Configuration Item untuk ditelusuri?',
        ],
        // Process Definition (Level 3)
        'PD' => [
            'Siapa yang ditetapkan sebagai pemilik atau penanggung jawab proses Problem Management di perusahaan?',
            'Apakah tujuan, sasaran, ruang lingkup, pemicu, input, dan output Problem Management telah ditetapkan dan didokumentasikan?',
            'Apakah tahapan atau urutan aktivitas Problem Management telah ditentukan dan didokumentasikan?',
            'Bagaimana hubungan Problem Management dengan Incident Management, Change Management, atau proses lainnya ditentukan?',
            'Apakah peran dan tanggung jawab pihak-pihak yang terlibat dalam Problem Management telah ditentukan?',
            'Apakah kompetensi yang diperlukan untuk menjalankan Problem Management telah diidentifikasi?',
            'Apakah infrastruktur dan lingkungan kerja yang diperlukan untuk menjalankan Problem Management telah ditentukan?',
            'Apakah terdapat metode atau indikator seperti KPI dan CSF yang digunakan untuk memantau efektivitas Problem Management?',
            'Apakah format, isi, dan frekuensi laporan manajemen layanan terkait Problem Management telah disepakati dengan pihak yang berkepentingan?',
        ],
        // Process Deployment (Level 3+)
        'PDep' => [
            'Apakah pemilik proses menjalankan peran dan tanggung jawabnya dalam Problem Management?',
            'Bagaimana proses standar Problem Management diterapkan dan disesuaikan dengan kebutuhan perusahaan?',
            'Apakah pelaksanaan Problem Management mengikuti proses atau prosedur standar yang telah ditetapkan?',
            'Bagaimana Problem Management berinteraksi dengan proses lain dalam kegiatan operasional?',
            'Apakah peran dan tanggung jawab dalam Problem Management telah ditetapkan dan dikomunikasikan kepada pihak terkait?',
            'Apakah pihak yang menjalankan Problem Management memiliki kompetensi yang diperlukan atau mendapatkan pelatihan yang sesuai?',
            'Apakah sumber daya manusia yang diperlukan untuk menjalankan Problem Management tersedia dan dialokasikan?',
            'Apakah informasi yang dibutuhkan untuk menjalankan Problem Management tersedia dan digunakan oleh pihak terkait?',
            'Apakah infrastruktur atau alat pendukung Problem Management tersedia, digunakan, dan dipelihara?',
            'Apakah data mengenai pelaksanaan Problem Management dikumpulkan dan dianalisis untuk mengetahui efektivitas proses serta peluang perbaikan?',
            'Apakah laporan terkait Problem Management dibuat dan disampaikan kepada pihak yang berkepentingan?',
        ],
        // Process Measurement (Level 4)
        'PMeas' => [
            'Informasi apa yang dibutuhkan perusahaan untuk mengetahui kinerja Problem Management dan kaitannya dengan tujuan bisnis?',
            'Bagaimana perusahaan menentukan tujuan pengukuran kinerja Problem Management?',
            'Apakah perusahaan memiliki sasaran kinerja Problem Management yang dapat diukur secara kuantitatif?',
            'Apakah sasaran kinerja tersebut diverifikasi atau disepakati bersama manajemen dan pemilik proses?',
            'Bagaimana metode pengukuran dan pengumpulan data kinerja Problem Management ditentukan?',
            'Apakah terdapat mekanisme untuk memastikan hasil pengukuran Problem Management dapat dipercaya?',
            'Apakah hasil pengukuran kinerja Problem Management dikumpulkan dan diolah secara berkala?',
            'Bagaimana hasil pengukuran digunakan untuk mengetahui pencapaian tujuan kinerja Problem Management?',
        ],
        // Process Control (Level 4)
        'PC' => [
            'Teknik atau metode apa yang digunakan perusahaan untuk menganalisis dan mengendalikan kinerja Problem Management?',
            'Apakah perusahaan memiliki batas tertentu untuk menentukan apakah kinerja Problem Management masih berada dalam kondisi normal?',
            'Bagaimana data hasil pengukuran dianalisis untuk mengetahui penyebab terjadinya penyimpangan atau variasi kinerja?',
            'Apa tindakan korektif yang dilakukan apabila ditemukan penyimpangan yang memengaruhi kinerja Problem Management?',
            'Setelah tindakan perbaikan dilakukan, apakah batas pengendalian kinerja ditinjau atau ditetapkan kembali apabila diperlukan?',
        ],
        // Process Innovation (Level 5)
        'PI' => [
            'Bagaimana perusahaan menentukan tujuan perbaikan Problem Management agar mendukung tujuan bisnis?',
            'Bagaimana hasil pengukuran digunakan untuk menemukan variasi atau potensi masalah dalam kinerja Problem Management?',
            'Apakah perusahaan menggunakan inovasi atau praktik terbaik industri sebagai sumber peluang perbaikan Problem Management?',
            'Apakah teknologi baru atau konsep proses baru pernah dipertimbangkan untuk meningkatkan Problem Management?',
            'Bagaimana perusahaan menentukan, memprioritaskan, menerapkan, dan mengevaluasi strategi perbaikan Problem Management dalam jangka panjang?',
        ],
        // Process Optimization (Level 5)
        'PO' => [
            'Bagaimana perusahaan menilai dampak dari perubahan yang dilakukan terhadap Problem Management berdasarkan tujuan proses yang telah ditetapkan?',
            'Bagaimana perusahaan mengelola penerapan perubahan agar tidak menimbulkan gangguan terhadap kinerja Problem Management?',
            'Setelah perubahan diterapkan, bagaimana perusahaan mengevaluasi efektivitas perubahan berdasarkan kinerja proses dan tujuan bisnis?',
        ],
    ],
    // Request Fulfilment
    'RF' => [
        // Process Performance (Level 1 & 2)
        'PP' => [
            'Bagaimana perusahaan menerima dan menangani permintaan layanan yang diajukan oleh pengguna?',
            'Apakah setiap permintaan layanan dicatat dan dipantau sampai permintaan tersebut selesai dipenuhi?',
            'Bagaimana perusahaan menentukan prioritas ketika terdapat beberapa permintaan layanan yang harus ditangani?',
            'Apakah pemenuhan permintaan layanan dilakukan sesuai dengan target waktu atau SLA yang telah ditetapkan?',
        ],
        // Performance Management (Level 3)
        'PM' => [
            'Melalui saluran atau pihak mana pengguna biasanya menyampaikan permintaan layanan kepada perusahaan?',
            'Ketika permintaan layanan diterima, informasi apa saja yang dicatat mengenai permintaan tersebut?',
            'Bagaimana perusahaan menentukan kategori dari permintaan layanan yang diterima?',
            'Bagaimana perusahaan menentukan prioritas suatu permintaan layanan?',
            'Apakah terdapat jenis permintaan layanan tertentu yang memerlukan persetujuan sebelum diproses? Jika ada, bagaimana proses persetujuannya?',
            'Bagaimana perusahaan menentukan pihak atau tim yang bertanggung jawab untuk memenuhi suatu permintaan layanan?',
            'Bagaimana proses pemenuhan permintaan layanan dilakukan setelah permintaan diterima dan disetujui?',
            'Apakah perkembangan pemenuhan permintaan layanan dipantau sampai permintaan tersebut selesai?',
            'Setelah permintaan layanan dipenuhi, bagaimana perusahaan memastikan permintaan telah terpenuhi dan pengguna puas, serta bagaimana proses penutupan dan pembaruan catatannya dilakukan?',
        ],
        // Work Product Management (Level 3)
        'WPM' => [
            'Apakah setiap permintaan layanan memiliki catatan yang disimpan dan dapat ditelusuri kembali?',
            'Apakah perusahaan memiliki katalog layanan yang menjadi acuan pengguna ketika mengajukan permintaan layanan?',
            'Apakah perusahaan menggunakan alat atau sistem tertentu untuk mencatat dan mengelola permintaan layanan?',
            'Apakah perusahaan memiliki kategori dan tingkat prioritas tertentu untuk mengelompokkan permintaan layanan?',
            'Apakah perusahaan memiliki prosedur atau panduan yang digunakan sebagai acuan dalam memenuhi permintaan layanan?',
        ],
        // Process Definition (Level 3)
        'PD' => [
            'Siapa yang ditetapkan sebagai pemilik atau penanggung jawab proses Request Fulfilment di perusahaan?',
            'Apakah tujuan, sasaran, ruang lingkup, pemicu, input, dan output Request Fulfilment telah ditetapkan dan didokumentasikan?',
            'Apakah tahapan atau urutan aktivitas Request Fulfilment telah ditentukan dan didokumentasikan?',
            'Bagaimana hubungan Request Fulfilment dengan proses lain, seperti Incident Management atau Access Management, ditentukan?',
            'Apakah peran dan tanggung jawab setiap pihak yang terlibat dalam Request Fulfilment telah ditentukan?',
            'Apakah kompetensi yang diperlukan untuk menjalankan Request Fulfilment telah diidentifikasi?',
            'Apakah infrastruktur dan lingkungan kerja yang diperlukan untuk menjalankan Request Fulfilment telah ditentukan?',
            'Apakah terdapat metode atau indikator seperti KPI dan CSF untuk memantau efektivitas dan kesesuaian Request Fulfilment?',
            'Apakah format, isi, dan frekuensi laporan manajemen layanan terkait Request Fulfilment telah disepakati dengan pihak yang berkepentingan?',
        ],
        // Process Deployment (Level 3+)
        'PDep' => [
            'Apakah pemilik proses menjalankan peran dan tanggung jawabnya dalam Request Fulfilment?',
            'Bagaimana proses standar Request Fulfilment diterapkan dan disesuaikan dengan kebutuhan perusahaan?',
            'Apakah pelaksanaan Request Fulfilment mengikuti proses atau prosedur standar yang telah ditetapkan?',
            'Bagaimana Request Fulfilment berinteraksi dengan proses lain dalam kegiatan operasional?',
            'Apakah peran dan tanggung jawab dalam Request Fulfilment telah ditetapkan dan dikomunikasikan kepada pihak terkait?',
            'Apakah pihak yang menjalankan Request Fulfilment memiliki kompetensi yang diperlukan atau mendapatkan pelatihan yang sesuai?',
            'Apakah sumber daya manusia yang dibutuhkan untuk menjalankan Request Fulfilment tersedia dan dialokasikan?',
            'Apakah informasi yang dibutuhkan dalam menjalankan Request Fulfilment tersedia dan digunakan oleh pihak terkait?',
            'Apakah infrastruktur atau alat yang dibutuhkan untuk mendukung Request Fulfilment tersedia, digunakan, dan dipelihara?',
            'Apakah data mengenai pelaksanaan Request Fulfilment dikumpulkan dan dianalisis untuk mengetahui efektivitas proses serta peluang perbaikan?',
            'Apakah laporan terkait Request Fulfilment dibuat dan disampaikan kepada pihak yang berkepentingan?',
        ],
        // Process Measurement (Level 4)
        'PMeas' => [
            'Informasi apa yang dibutuhkan perusahaan untuk mengetahui kinerja Request Fulfilment dan kaitannya dengan tujuan bisnis?',
            'Bagaimana perusahaan menentukan tujuan pengukuran kinerja Request Fulfilment?',
            'Apakah perusahaan memiliki sasaran kinerja Request Fulfilment yang dapat diukur secara kuantitatif?',
            'Apakah sasaran kinerja yang ditetapkan diverifikasi atau disepakati bersama manajemen dan pemilik proses?',
            'Bagaimana metode pengukuran dan pengumpulan data kinerja Request Fulfilment ditentukan?',
            'Apakah terdapat mekanisme untuk memastikan hasil pengukuran Request Fulfilment sudah benar dan dapat dipercaya?',
            'Apakah hasil pengukuran kinerja Request Fulfilment dikumpulkan dan diolah secara berkala?',
            'Bagaimana hasil pengukuran digunakan untuk mengetahui apakah tujuan kinerja Request Fulfilment telah tercapai?',
        ],
        // Process Control (Level 4)
        'PC' => [
            'Teknik atau metode apa yang digunakan perusahaan untuk menganalisis dan mengendalikan kinerja Request Fulfilment?',
            'Apakah perusahaan memiliki batas tertentu untuk menentukan apakah kinerja Request Fulfilment masih berada dalam kondisi normal?',
            'Bagaimana data hasil pengukuran dianalisis untuk mengetahui penyebab terjadinya penyimpangan atau variasi kinerja?',
            'Apa tindakan korektif yang dilakukan apabila ditemukan penyimpangan yang memengaruhi kinerja Request Fulfilment?',
            'Setelah dilakukan tindakan perbaikan, apakah batas pengendalian kinerja ditinjau atau ditetapkan kembali apabila diperlukan?',
        ],
        // Process Innovation (Level 5)
        'PI' => [
            'Bagaimana perusahaan menentukan tujuan perbaikan Request Fulfilment agar mendukung tujuan bisnis?',
            'Bagaimana hasil pengukuran digunakan untuk menemukan variasi atau potensi masalah dalam kinerja Request Fulfilment?',
            'Apakah perusahaan menggunakan inovasi atau praktik terbaik industri sebagai sumber peluang perbaikan Request Fulfilment?',
            'Apakah teknologi baru atau konsep proses baru pernah dipertimbangkan sebagai peluang untuk meningkatkan Request Fulfilment?',
            'Bagaimana perusahaan menentukan, memprioritaskan, menerapkan, dan mengevaluasi strategi perbaikan Request Fulfilment dalam jangka panjang?',
        ],
        // Process Optimization (Level 5)
        'PO' => [
            'Bagaimana perusahaan menilai dampak dari setiap perubahan yang dilakukan terhadap Request Fulfilment berdasarkan tujuan proses yang telah ditetapkan?',
            'Bagaimana perusahaan mengelola penerapan perubahan agar tidak menimbulkan gangguan terhadap kinerja Request Fulfilment?',
            'Setelah perubahan diterapkan, bagaimana perusahaan mengevaluasi apakah perubahan tersebut efektif berdasarkan kinerja proses dan tujuan bisnis?',
        ],
    ],
    // Access Management
    'AM' => [
        // Process Performance (Level 1 & 2)
        'PP' => [
            'Bagaimana perusahaan menentukan dan memberikan hak akses kepada pengguna terhadap layanan atau sistem TI sesuai dengan kebutuhannya?',
            'Bagaimana perusahaan mengelola dan mengendalikan hak akses pengguna agar hanya pihak yang berwenang yang dapat mengakses layanan TI?',
            'Bagaimana perusahaan menangani perubahan atau pencabutan hak akses pengguna ketika terjadi perubahan kebutuhan atau status pengguna?',
            'Bagaimana perusahaan memastikan bahwa pengelolaan hak akses dapat menjaga keamanan layanan dan informasi yang digunakan?',
        ],
        // Performance Management (Level 3)
        'PM' => [
            'Bagaimana perusahaan menerima dan mencatat permintaan hak akses dari pengguna?',
            'Apakah permintaan hak akses diverifikasi terlebih dahulu sebelum akses diberikan kepada pengguna?',
            'Apakah terdapat aturan atau kebijakan yang menjadi dasar dalam menentukan hak akses yang diberikan kepada pengguna?',
            'Apakah hak akses pengguna dipantau atau ditinjau kembali secara berkala untuk memastikan masih sesuai dengan kebutuhannya?',
            'Bagaimana proses perubahan atau pencabutan hak akses dilakukan ketika pengguna tidak lagi membutuhkan akses tersebut?',
            'Apakah aktivitas atau perubahan hak akses pengguna dicatat sehingga dapat ditelusuri kembali apabila diperlukan?',
            'Bagaimana perusahaan menangani permasalahan atau pelanggaran yang berkaitan dengan hak akses pengguna?',
        ],
        // Work Product Management (Level 3)
        'WPM' => [
            'Apakah perusahaan menyimpan catatan mengenai hak akses yang dimiliki oleh setiap pengguna?',
            'Apakah setiap permintaan pemberian, perubahan, atau pencabutan akses didokumentasikan?',
            'Apakah perusahaan memiliki kebijakan atau aturan yang digunakan sebagai pedoman dalam pengelolaan hak akses?',
            'Bagaimana perusahaan menjaga dan memperbarui informasi mengenai pengguna beserta hak akses yang dimilikinya?',
            'Apakah terdapat rekaman atau log yang dapat digunakan untuk mengetahui aktivitas akses pengguna terhadap layanan atau sistem TI?',
            'Apakah perusahaan memiliki prosedur atau panduan yang digunakan dalam pemberian, perubahan, pemantauan, dan pencabutan hak akses?',
        ],
        // Process Definition (Level 3)
        'PD' => [
            'Siapa yang ditetapkan sebagai pemilik atau penanggung jawab proses Access Management di perusahaan?',
            'Apakah tujuan, sasaran, ruang lingkup, pemicu, input, dan output Access Management telah ditetapkan dan didokumentasikan?',
            'Apakah tahapan atau urutan aktivitas Access Management telah ditentukan dan didokumentasikan?',
            'Bagaimana hubungan Access Management dengan proses lain, seperti Incident Management atau Request Fulfilment, ditentukan?',
            'Apakah peran dan tanggung jawab setiap pihak yang terlibat dalam Access Management telah ditentukan?',
            'Apakah kompetensi yang diperlukan untuk menjalankan Access Management telah diidentifikasi?',
            'Apakah infrastruktur dan lingkungan kerja yang dibutuhkan untuk menjalankan Access Management telah ditentukan?',
            'Apakah terdapat metode atau indikator seperti KPI dan CSF yang digunakan untuk memantau efektivitas dan kesesuaian Access Management?',
            'Apakah format, isi, dan frekuensi laporan manajemen layanan terkait Access Management telah disepakati dengan pihak yang berkepentingan?',
        ],
        // Process Deployment (Level 3+)
        'PDep' => [
            'Apakah pemilik proses menjalankan peran dan tanggung jawabnya dalam Access Management?',
            'Bagaimana proses standar Access Management diterapkan dan disesuaikan dengan kondisi atau kebutuhan perusahaan?',
            'Apakah pelaksanaan Access Management mengikuti proses atau prosedur standar yang telah ditentukan?',
            'Bagaimana Access Management berinteraksi dengan proses lain dalam kegiatan operasional?',
            'Apakah peran dan tanggung jawab dalam Access Management telah ditetapkan dan dikomunikasikan kepada pihak terkait?',
            'Apakah pihak yang menjalankan Access Management memiliki kompetensi yang dibutuhkan atau mendapatkan pelatihan yang sesuai?',
            'Apakah sumber daya manusia yang dibutuhkan untuk menjalankan Access Management tersedia dan dialokasikan?',
            'Apakah informasi yang dibutuhkan dalam menjalankan Access Management tersedia dan digunakan oleh pihak terkait?',
            'Apakah infrastruktur atau alat yang diperlukan untuk mendukung Access Management tersedia, digunakan, dan dipelihara?',
            'Apakah data mengenai pelaksanaan Access Management dikumpulkan dan dianalisis untuk mengetahui efektivitas proses serta peluang perbaikan?',
            'Apakah laporan mengenai Access Management dibuat dan disampaikan kepada pihak yang berkepentingan?',
        ],
        // Process Measurement (Level 4)
        'PMeas' => [
            'Informasi apa yang dibutuhkan perusahaan untuk mengetahui kinerja Access Management dan bagaimana kaitannya dengan tujuan bisnis?',
            'Bagaimana perusahaan menentukan tujuan pengukuran kinerja Access Management?',
            'Apakah perusahaan memiliki sasaran kinerja Access Management yang dapat diukur secara kuantitatif?',
            'Apakah sasaran kinerja yang ditetapkan diverifikasi atau disepakati bersama manajemen dan pemilik proses?',
            'Bagaimana metode pengukuran dan pengumpulan data kinerja Access Management ditentukan?',
            'Apakah terdapat mekanisme untuk memastikan hasil pengukuran Access Management sudah benar dan dapat dipercaya?',
            'Apakah hasil pengukuran kinerja Access Management dikumpulkan dan diolah secara berkala?',
            'Bagaimana hasil pengukuran digunakan untuk mengetahui apakah tujuan kinerja Access Management telah tercapai?',
        ],
        // Process Control (Level 4)
        'PC' => [
            'Teknik atau metode apa yang digunakan perusahaan untuk menganalisis dan mengendalikan kinerja Access Management?',
            'Apakah perusahaan memiliki batas tertentu untuk menentukan apakah kinerja Access Management masih berada dalam kondisi normal?',
            'Bagaimana data hasil pengukuran dianalisis untuk mengetahui penyebab terjadinya penyimpangan atau variasi kinerja?',
            'Apa tindakan korektif yang dilakukan apabila ditemukan penyimpangan yang memengaruhi kinerja Access Management?',
            'Setelah dilakukan tindakan perbaikan, apakah batas pengendalian kinerja ditinjau atau ditetapkan kembali apabila diperlukan?',
        ],
        // Process Innovation (Level 5)
        'PI' => [
            'Bagaimana perusahaan menentukan tujuan perbaikan Access Management agar mendukung tujuan bisnis?',
            'Bagaimana hasil pengukuran digunakan untuk menemukan variasi atau potensi masalah dalam kinerja Access Management?',
            'Apakah perusahaan menggunakan inovasi atau praktik terbaik industri sebagai sumber peluang perbaikan Access Management?',
            'Apakah teknologi baru atau konsep proses baru pernah dipertimbangkan sebagai peluang untuk meningkatkan Access Management?',
            'Bagaimana perusahaan menentukan, memprioritaskan, menerapkan, dan mengevaluasi strategi perbaikan Access Management dalam jangka panjang?',
        ],
        // Process Optimization (Level 5)
        'PO' => [
            'Bagaimana perusahaan menilai dampak dari setiap perubahan yang dilakukan terhadap Access Management berdasarkan tujuan proses yang telah ditetapkan?',
            'Bagaimana perusahaan mengelola penerapan perubahan agar tidak menimbulkan gangguan terhadap kinerja Access Management?',
            'Setelah perubahan diterapkan, bagaimana perusahaan mengevaluasi apakah perubahan tersebut efektif berdasarkan kinerja proses dan tujuan bisnis?',
        ],
    ],
];
