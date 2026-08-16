<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // DATA HARI PERTAMA (Sponsorship GIHES _opt_012.jpg)
        // ==========================================
        $day1 = [
            ['time' => '08.00 - 09.00', 'session' => 'Registration & Welcome Lobby', 'topic' => 'Registration, networking, institutional showcase access.', 'speaker' => 'Secretariat & Committee', 'break' => false],
            ['time' => '09.00 - 09.30', 'session' => 'Opening Ceremony', 'topic' => 'Opening Remarks', 'speaker' => '1. Ketua MPR, 2. Ketua Badan Wakaf, 3. Pimpinan Pondok Modern Gontor', 'break' => false],
            ['time' => '09.30 - 10.00', 'session' => 'Keynote I', 'topic' => 'The Future of Holistic Islamic Education', 'speaker' => 'Prof. Hamid Fahmy Zarkasyi', 'break' => false],
            ['time' => '10.00 - 10.30', 'session' => 'Keynote II', 'topic' => 'National Perspective on Holistic Education', 'speaker' => 'International/ National Speaker', 'break' => false],
            ['time' => '10.30 - 10.45', 'session' => 'Coffee Break', 'topic' => 'Networking Session', 'speaker' => 'Committee', 'break' => true],
            ['time' => '10.45 - 11.15', 'session' => 'Keynote III', 'topic' => 'International Perspective', 'speaker' => 'International Speaker', 'break' => false],
            ['time' => '11.15 - 11.45', 'session' => 'Keynote IV', 'topic' => 'Global Collaboration in Islamic Education', 'speaker' => 'International Speaker', 'break' => false],
            ['time' => '11.45 - 12.45', 'session' => 'Plenary Session', 'topic' => 'Pesantren in Global Conversation', 'speaker' => 'Malaysia • India • Japan • China (Moderator: Indonesia)', 'break' => false],
            ['time' => '12.45 - 13.45', 'session' => 'Lunch Break', 'topic' => 'Networking Lunch', 'speaker' => '-', 'break' => true],
            ['time' => '13.45 - 16.30', 'session' => 'Parallel Dialogue (2 Rooms)', 'topic' => "• Room 1 = Kurikulum Holistik, Kepemimpinan Santri, Pendidikan Bahasa Arab dan Adab, Foundation & Institutional Development\n• Room 2 = Pesantren dan Mental Health, Digital / AI dalam Pendidikan, Foundation & Institutional Development", 'speaker' => 'Invited Speakers', 'break' => false],
            ['time' => '13.45 - 16.30', 'session' => 'Leaders Talk', 'topic' => 'Pesantren Governance, Kaderisasi, dan Berkelanjutan Institusi', 'speaker' => 'National & International Leaders', 'break' => false],
            ['time' => '16.30 - 19.00', 'session' => 'Break', 'topic' => 'Gala Dinner Preparation', 'speaker' => '-', 'break' => true],
            ['time' => '19.00 - 21.00', 'session' => 'Gala Dinner', 'topic' => 'Networking & Fellowship Dinner', 'speaker' => 'Hosted by Ahmad Muzani', 'break' => false],
        ];

        foreach ($day1 as $item) {
            Schedule::create([
                'day' => 1,
                'time_range' => $item['time'],
                'session_name' => $item['session'],
                'topic_description' => $item['topic'],
                'speaker' => $item['speaker'],
                'is_break' => $item['break'],
            ]);
        }

        // ==========================================
        // DATA HARI KEDUA (Sponsorship GIHES _opt_013.jpg)
        // ==========================================
        $day2 = [
            ['time' => '08.30 - 09.15', 'session' => 'Keynote I', 'topic' => 'Islam, Modernity, and the Global Future of Pesantren Education.', 'speaker' => 'Pembicara LN / DN', 'break' => false],
            ['time' => '09.15 - 10.00', 'session' => 'Keynote II', 'topic' => 'The Future of Holistic Islamic Education dan Global Collaboration.', 'speaker' => 'Pembicara LN / DN', 'break' => false],
            ['time' => '10.00 - 10.15', 'session' => 'Coffee Break', 'topic' => 'Networking dan media interview.', 'speaker' => 'Panitia', 'break' => true],
            ['time' => '10.15 - 11.15', 'session' => 'Plenary Session II', 'topic' => 'Pesantren in Global Conversation: Australia / Finland England / USA Timur Tengah / Indonesia Turkey Moderator: Indonesia', 'speaker' => 'Australia / Finland, England / USA, Timur Tengah / Indonesia, Turkey Moderator: Indonesia', 'break' => false],
            ['time' => '11.15 - 12.00', 'session' => 'Institutional Showcase Panel', 'topic' => 'Best Practices of Holistic Pesantren Education: Curriculum, Discipline, Language, Organization, Entrepreneurship, and Community Service.', 'speaker' => 'Alumni Pondok Modern Gontor Pesantren NU / Muhammadiyah Perguruan Tinggi Mitra Praktisi Pendidikan', 'break' => false],
            ['time' => '12.00 - 12.50', 'session' => 'Working Group (WG) Sessions', 'topic' => 'Diskusi paralel berdasarkan tema prioritas.', 'speaker' => 'Koordinator WG dan rapporteur akademik.', 'break' => false],
            ['time' => '12.50 - 13.00', 'session' => 'MoU Partnership Session', 'topic' => 'Penandatangan/penjajakan MoU pertukaran guru, riset, kurikulum, publikasi, dan institutional partnership.', 'speaker' => 'FPAG, pesantren, universitas, lembaga internasional.', 'break' => false],
            ['time' => '13.00 - 14.00', 'session' => 'Break', 'topic' => 'Istirahat & Networking.', 'speaker' => 'Panitia', 'break' => true],
            ['time' => '14.00 - 16.30', 'session' => 'Jakarta Cultural Experience', 'topic' => 'City tour bagi peserta dan pendamping.', 'speaker' => 'Panitia', 'break' => false],
            ['time' => '19.00 - 21.30', 'session' => 'Gala Dinner & GIHES Declaration', 'topic' => 'Malam jamuan kehormatan, penandatangan GIHES Declaration, dan networking.', 'speaker' => 'Host: Gubernur DKI Jakarta | Plataran Monas', 'break' => false],
        ];

        foreach ($day2 as $item) {
            Schedule::create([
                'day' => 2,
                'time_range' => $item['time'],
                'session_name' => $item['session'],
                'topic_description' => $item['topic'],
                'speaker' => $item['speaker'],
                'is_break' => $item['break'],
            ]);
        }
    }
}