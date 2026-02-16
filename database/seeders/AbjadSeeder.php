<?php

namespace Database\Seeders;

use App\Models\Abjad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AbjadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'huruf' => 'A',
                'deskripsi' => 'Kepalkan tangan dengan ibu jari tegak lurus di samping telunjuk.',
                'berkas_video' => 'img/abjad/A.png'
            ],
            [
                'huruf' => 'B',
                'deskripsi' => 'Buka telapak tangan dengan jari-jari rapat dan tegang, ibu jari ditekuk ke dalam telapak tangan.',
                'berkas_video' => 'img/abjad/B.png'
            ],
            [
                'huruf' => 'C',
                'deskripsi' => 'Bentuk huruf C dengan tangan kanan, jari-jari melengkung.',
                'berkas_video' => 'img/abjad/C.png'
            ],
            [
                'huruf' => 'D',
                'deskripsi' => 'Acungkan jari telunjuk lurus ke atas, sementara ibu jari dan jari lainnya membentuk lingkaran.',
                'berkas_video' => 'img/abjad/D.png'
            ],
            [
                'huruf' => 'E',
                'deskripsi' => 'Tekuk semua jari ke arah telapak tangan, namun tidak mengepal, ibu jari di bawah jari-jari lainnya.',
                'berkas_video' => 'img/abjad/E.png'
            ],
            [
                'huruf' => 'F',
                'deskripsi' => 'Satukan ujung telunjuk dan ibu jari membentuk lingkaran, jari lainnya tegak lurus.',
                'berkas_video' => 'img/abjad/F.png'
            ],
            [
                'huruf' => 'G',
                'deskripsi' => 'Arahkan telunjuk ke kiri, posisi tangan miring.',
                'berkas_video' => 'img/abjad/G.png'
            ],
            [
                'huruf' => 'H',
                'deskripsi' => 'Arahkan telunjuk dan jari tengah ke kiri, posisi tangan miring.',
                'berkas_video' => 'img/abjad/H.png'
            ],
            [
                'huruf' => 'I',
                'deskripsi' => 'Acungkan jari kelingking tegak lurus, jari lainnya mengepal.',
                'berkas_video' => 'img/abjad/I.png'
            ],
            [
                'huruf' => 'J',
                'deskripsi' => 'Buat huruf I, lalu gerakkan kelingking membentuk lengkungan huruf J di udara.',
                'berkas_video' => 'img/abjad/J.png'
            ],
            [
                'huruf' => 'K',
                'deskripsi' => 'Acungkan jari telunjuk dan jari tengah membentuk huruf V, ibu jari diselipkan di antaranya.',
                'berkas_video' => 'img/abjad/K.png'
            ],
            [
                'huruf' => 'L',
                'deskripsi' => 'Bentuk huruf L dengan ibu jari dan telunjuk tegak lurus.',
                'berkas_video' => 'img/abjad/L.png'
            ],
            [
                'huruf' => 'M',
                'deskripsi' => 'Selipkan ibu jari di bawah jari manis, jari tengah, dan telunjuk yang ditekuk.',
                'berkas_video' => 'img/abjad/M.png'
            ],
            [
                'huruf' => 'N',
                'deskripsi' => 'Selipkan ibu jari di bawah jari tengah dan telunjuk yang ditekuk.',
                'berkas_video' => 'img/abjad/N.png'
            ],
            [
                'huruf' => 'O',
                'deskripsi' => 'Bentuk lingkaran dengan menghubungkan ujung semua jari.',
                'berkas_video' => 'img/abjad/O.png'
            ],
            [
                'huruf' => 'P',
                'deskripsi' => 'Bentuk huruf K, namun arahkan ke bawah.',
                'berkas_video' => 'img/abjad/P.png'
            ],
            [
                'huruf' => 'Q',
                'deskripsi' => 'Arahkan telunjuk dan ibu jari ke bawah, jari lainnya mengepal.',
                'berkas_video' => 'img/abjad/Q.png'
            ],
            [
                'huruf' => 'R',
                'deskripsi' => 'Silangkan jari tengah di atas jari telunjuk.',
                'berkas_video' => 'img/abjad/R.png'
            ],
            [
                'huruf' => 'S',
                'deskripsi' => 'Kepalkan tangan dengan ibu jari menindih jari-jari lainnya.',
                'berkas_video' => 'img/abjad/S.png'
            ],
            [
                'huruf' => 'T',
                'deskripsi' => 'Selipkan ibu jari di antara telunjuk dan jari tengah yang mengepal.',
                'berkas_video' => 'img/abjad/T.png'
            ],
            [
                'huruf' => 'U',
                'deskripsi' => 'Acungkan jari telunjuk dan jari tengah rapat tegak lurus.',
                'berkas_video' => 'img/abjad/U.png'
            ],
            [
                'huruf' => 'V',
                'deskripsi' => 'Acungkan jari telunjuk dan jari tengah membentuk huruf V.',
                'berkas_video' => 'img/abjad/V.png'
            ],
            [
                'huruf' => 'W',
                'deskripsi' => 'Acungkan jari telunjuk, tengah, dan manis membentuk huruf W.',
                'berkas_video' => 'img/abjad/W.png'
            ],
            [
                'huruf' => 'X',
                'deskripsi' => 'Tekuk jari telunjuk seperti kait, jari lainnya mengepal.',
                'berkas_video' => 'img/abjad/X.png'
            ],
            [
                'huruf' => 'Y',
                'deskripsi' => 'Acungkan ibu jari dan kelingking, jari lainnya mengepal.',
                'berkas_video' => 'img/abjad/Y.png'
            ],
            [
                'huruf' => 'Z',
                'deskripsi' => 'Lukis huruf Z di udara dengan jari telunjuk.',
                'berkas_video' => 'img/abjad/Z.png'
            ],
        ];

        foreach ($data as $item) {
            Abjad::updateOrCreate(
                ['huruf' => $item['huruf']],
                $item
            );
        }
    }
}
