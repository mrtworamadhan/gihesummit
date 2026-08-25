<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SAPU BERSIH DATA LAMA!
        Schedule::truncate();

        // 2. SIAPKAN DATA BARU
        $schedules = [
            // ================= DAY 1 - 5 SEPTEMBER 2026 =================
            [
                'day' => 1,
                'time_range' => '08:00 - 08:45',
                'session_name' => 'Registration & Welcome',
                'topic_description' => 'Registration, networking, institutional showcase, and access to information booths',
                'speaker' => 'Secretariat & Committee',
                'is_break' => 0,
            ],
            [
                'day' => 1,
                'time_range' => '09:00 - 09:30',
                'session_name' => 'Opening Speech',
                'topic_description' => 'Welcome Remarks and Opening Reflection',
                'speaker' => 'Drs. KH. Akrim Mariyat, Dipl.A.Ed.',
                'is_break' => 0,
            ],
            [
                'day' => 1,
                'time_range' => '09:30 - 10:00',
                'session_name' => 'Opening Ceremony',
                'topic_description' => 'Official Opening of GIHES 2026',
                'speaker' => 'H. Ahmad Muzani',
                'is_break' => 0,
            ],
            [
                'day' => 1,
                'time_range' => '10:00 - 10:30',
                'session_name' => 'Keynote Address I',
                'topic_description' => 'The Future of Islamic Holistic Education',
                'speaker' => 'Prof. Dr. Hamid Fahmy Zarkasyi, M.A.Ed., M.Phil.',
                'is_break' => 0,
            ],
            [
                'day' => 1,
                'time_range' => '10:30 - 11:00',
                'session_name' => 'Keynote Address II',
                'topic_description' => 'National Perspectives on Holistic Education',
                'speaker' => 'Prof. Dr. Abdul Mu\'ti, M.A.',
                'is_break' => 0,
            ],
            [
                'day' => 1,
                'time_range' => '11:00 - 13:30',
                'session_name' => 'Networking Lunch & Break',
                'topic_description' => 'Lunch, prayer, networking, institutional engagement, and check-in hotel',
                'speaker' => 'Committee',
                'is_break' => 1, 
            ],
            [
                'day' => 1,
                'time_range' => '13:30 - 15:00',
                'session_name' => 'Plenary Session I',
                'topic_description' => 'Holistic Education: Exploring and Revealing the Pesantren Education System',
                'speaker' => "Prof. Dr. Amin Abdullah\nProf. Dr. Nuri Tinaz",
                'is_break' => 0,
            ],
            [
                'day' => 1,
                'time_range' => '15:00 - 15:15',
                'session_name' => 'Coffee Break',
                'topic_description' => 'Networking Session',
                'speaker' => 'Committee',
                'is_break' => 1, 
            ],
            [
                'day' => 1,
                'time_range' => '15:15 - 17:00',
                'session_name' => 'Plenary Session II',
                'topic_description' => 'Pesantren in the Global Conversation: International Perspectives and Future Collaboration',
                'speaker' => "Prof. Dr. Ryoko Tsuneyoshi\nDr. Mahamood Shihab K.M.\nProf. Dato'.Ts. Dr. Norazah Mohd. Nordin",
                'is_break' => 0,
            ],
            [
                'day' => 1,
                'time_range' => '17:00 - 19:00',
                'session_name' => 'Break & Gala Dinner Preparation',
                'topic_description' => 'Prayer, rest, networking, and preparation for Gala Dinner',
                'speaker' => 'Committee',
                'is_break' => 1, 
            ],
            [
                'day' => 1,
                'time_range' => '19:00 - 22:00',
                'session_name' => 'Special Address & Gala Dinner',
                'topic_description' => 'Opportunities and Challenges for Pesantren and Islamic Education in Preparing for Golden Indonesia 2045',
                'speaker' => "Dr. H. TB. Ace Hasan Syadzily\nProf. Dr. Nasaruddin Umar, M.A.\nH.E. Dr. (H.C.) H. Muhammad Jusuf Kalla",
                'is_break' => 0,
            ],

            // ================= DAY 2 - 6 SEPTEMBER 2026 =================
            [
                'day' => 2,
                'time_range' => '08:30 - 11:30',
                'session_name' => 'Parallel Dialogue Sessions (Room 1)',
                'topic_description' => "Islamic Holistic Education: The Educational Model of Pondok Darussalam Gontor as a Benchmark\nEducating the Whole Person Beyond the Classroom: Modern Japanese Tokkatsu and the Islamic Boarding School in Comparative Perspective\nMuslim Youth and Identity Formation in The West: What Holistic Islamic Education Can Offer Diaspora Communities",
                'speaker' => "Prof. Dr. Hamid Fahmy Zarkasyi, M.A.Ed., M.Phil.\nProf. Dr. Ryoko Tsuneyoshi\nProf. Dr. Nuri Tinaz",
                'is_break' => 0,
            ],
            [
                'day' => 2,
                'time_range' => '08:30 - 11:30',
                'session_name' => 'Parallel Dialogue Sessions (Room 2)',
                'topic_description' => "Bridging Tradition and future: The Role of Holistic Education in India's Muslim Institutions\nHumanising Technology In 24-Hour Learning Ecosystems: Lessons For Holistic Character Education In The 4IR\nThe Roadmap of Pesantren in Indonesia: Opportunities, Challenges, and Hopes",
                'speaker' => "Dr. Mahamood Shihab K.M.\nProf. Dato'.Ts. Dr. Norazah Mohd. Nordin\nDr. H. Basnang Said, S.Ag., M.Ag.",
                'is_break' => 0,
            ],
            [
                'day' => 2,
                'time_range' => '08:30 - 11:30',
                'session_name' => 'Leaders Talk',
                'topic_description' => 'Pesantren Governance, Cadreship, Institutional Sustainability & Global Leadership',
                'speaker' => "Prof. Dr. H. Amien Suyitno, M.Ag.\nProf. Asep Saepudin Jahar, Ph.D.\nYudi Latif, M.A., Ph.D.",
                'is_break' => 0,
            ],
            [
                'day' => 2,
                'time_range' => '11:30 - 13:00',
                'session_name' => 'Networking Lunch & Break',
                'topic_description' => 'Lunch, prayer, networking, institutional engagement.',
                'speaker' => 'Committee',
                'is_break' => 1, 
            ],
            [
                'day' => 2,
                'time_range' => '13:00 - 13:30',
                'session_name' => 'MoU Partnership Session',
                'topic_description' => 'Signing of MoUs for teacher exchange, research, curriculum, publications, and institutional partnerships',
                'speaker' => 'FPAG, Pesantren, Universities & International Institutions',
                'is_break' => 0,
            ],
            [
                'day' => 2,
                'time_range' => '13:30 - 15:30',
                'session_name' => 'Special Address and Closing Ceremony',
                'topic_description' => "Pesantren, Research, and STEM: Integrating Islamic Values, Scientific Inquiry, and Innovation\nGIHES Declaration: Timeless Values, Future Civilizations; official declaration, networking, and closing celebration.",
                'speaker' => "Prof. Dr. Arif Satria\nDr. K.H. L. Zulkifli Muhadli, S.H., M.M.",
                'is_break' => 0,
            ],
            [
                'day' => 2,
                'time_range' => '15:30 - 18:30',
                'session_name' => 'Break & Preparation',
                'topic_description' => 'Rest, prayer, and preparation for the closing program',
                'speaker' => 'Protocol & Committee',
                'is_break' => 1, 
            ],
            [
                'day' => 2,
                'time_range' => '18:30 - 19:00',
                'session_name' => 'Guest Arrival & Reception',
                'topic_description' => 'Arrival and welcoming of distinguished guests',
                'speaker' => 'Protocol & Committee',
                'is_break' => 0,
            ],
            [
                'day' => 2,
                'time_range' => '19:00 - 21:30',
                'session_name' => 'Gala Dinner & GIHES Declaration',
                'topic_description' => 'Gontor Centenary Gathering with Ambassadors and Distinguished Leaders at Nusantara IV, DPR/MPR Senayan',
                'speaker' => "Dr. K.H. Hidayat Nur Wahid, Lc., M.A.\nProf. K.H. M. Din Syamsuddin, M.A., Ph.D.\nK.H. Hasan Abdullah Sahal",
                'is_break' => 0,
            ],
        ];

        // 3. MASUKKAN DATA BARU
        foreach ($schedules as $schedule) {
            // Kita ganti pakai create saja biar langsung masuk
            Schedule::create($schedule);
        }
    }
}