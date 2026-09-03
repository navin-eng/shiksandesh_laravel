<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Courses
        $courses = [
            [
                'name' => 'Grade XI & XII (Science)',
                'description' => 'Our +2 Science program offers a rigorous curriculum preparing students for engineering, medicine, and advanced sciences with well-equipped laboratories.',
                'requirement' => 'SEE / Equivalent with Minimum B Grade',
                'duration' => '2 Years'
            ],
            [
                'name' => 'Grade XI & XII (Management)',
                'description' => 'The +2 Management program is designed to develop future business leaders and entrepreneurs through practical and theoretical knowledge.',
                'requirement' => 'SEE / Equivalent with Minimum C Grade',
                'duration' => '2 Years'
            ],
            [
                'name' => 'Basic Level (Playgroup to Grade 8)',
                'description' => 'A strong foundation focusing on holistic development, modern learning techniques, and moral values for young learners.',
                'requirement' => 'Birth Certificate / Previous School Transfer',
                'duration' => '10 Years'
            ]
        ];
        foreach ($courses as $course) {
            \App\Models\Course::firstOrCreate(
                ['slug' => Str::slug($course['name'])],
                [
                    'name' => $course['name'],
                    'duration' => $course['duration'],
                    'semester' => 'Annual',
                    'requirement' => $course['requirement'],
                    'starting_time' => '10:00 AM',
                    'closing_time' => '4:00 PM',
                    'image' => 'default.jpg',
                    'gallery' => '[]',
                    'status' => 1,
                    'description' => $course['description'],
                    'fulldescription' => $course['description'] . ' At Shiksha Sandesh, we believe in providing a comprehensive learning environment that nurtures intellectual curiosity and prepares students for future challenges.',
                ]
            );
        }

        // 2. Events
        $events = [
            [
                'name' => 'Annual Sports Meet 2083',
                'description' => 'Join us for a week of exciting sports competitions including football, basketball, and track events to encourage physical fitness and teamwork.'
            ],
            [
                'name' => 'Inter-School Science Exhibition',
                'description' => 'A showcase of innovative projects and experiments by our brilliant students. Open for parents and visitors.'
            ],
            [
                'name' => 'Saraswati Puja Celebration',
                'description' => 'Special prayers and cultural programs organized by students and staff to seek the blessings of the Goddess of Knowledge.'
            ]
        ];
        foreach ($events as $event) {
            \App\Models\Event::firstOrCreate(
                ['slug' => Str::slug($event['name'])],
                [
                    'name' => $event['name'],
                    'description' => $event['description'],
                    'venue' => 'School Premises, Belbari',
                    'visit_date' => now()->addDays(rand(5, 30)),
                    'event_type' => 'event',
                    'image' => 'default.jpg',
                    'status' => 1
                ]
            );
        }

        // 3. Notices
        $notices = [
            ['title' => 'Admission Open for Academic Session 2083/84', 'show_in' => 'p', 'desc' => 'Admissions are now open from Playgroup to Grade 9. Secure your child\'s future with Shiksha Sandesh today!'], // Popup
            ['title' => 'Notice Regarding First Terminal Examination 2083', 'show_in' => 'm', 'desc' => 'The First Terminal Examinations will commence from the 2nd week of next month. Routine is available at the administration desk.'], // Marquee
            ['title' => 'Parents-Teachers Meeting (PTM) Scheduled', 'show_in' => 'n', 'desc' => 'All parents and guardians are requested to attend the PTM this coming Saturday from 10:00 AM to 1:00 PM.'], // Normal
            ['title' => 'School Holiday on account of Local Festival', 'show_in' => 'n', 'desc' => 'The school will remain closed tomorrow to observe the local festival. Classes will resume the day after.']
        ];
        foreach ($notices as $notice) {
            \App\Models\Notice::firstOrCreate(
                ['title' => $notice['title']],
                [
                    'slug' => Str::slug($notice['title']),
                    'description' => $notice['desc'],
                    'show_in' => $notice['show_in'],
                    'image' => 'default.jpg',
                ]
            );
        }

        // 4. Testimonials
        if (class_exists(\App\Models\Testimonial::class)) {
            $testimonials = [
                ['name' => 'Aarav Poudel', 'role' => 'Alumni (+2 Science)', 'description' => 'The supportive teachers and well-equipped labs at Shiksha Sandesh laid a strong foundation for my engineering career.'],
                ['name' => 'Sunita Shrestha', 'role' => 'Parent', 'description' => 'I have seen a massive improvement in my child\'s confidence and academics since joining this school. Highly recommended!'],
                ['name' => 'Rohan Karki', 'role' => 'Current Student (Grade 10)', 'description' => 'The environment here is very welcoming, and I love the balance between academics and extracurricular activities.']
            ];
            foreach ($testimonials as $t) {
                \App\Models\Testimonial::firstOrCreate(
                    ['name' => $t['name']],
                    [
                        'role' => $t['role'],
                        'description' => $t['description'],
                        'image' => 'default.jpg'
                    ]
                );
            }
        }

        // 5. College Messages
        $messages = [
            ['name' => 'Mr. Ramesh Koirala', 'designation' => 'Principal', 'message' => 'Welcome to Shiksha Sandesh English School. Our mission is to impart quality education that empowers students to reach their full potential. We focus on academic excellence, moral values, and overall personality development.', 'order' => 1],
            ['name' => 'Mrs. Anju Thapa', 'designation' => 'Chairperson', 'message' => 'We envision a learning community where every student thrives. We are committed to providing state-of-the-art facilities and a safe, nurturing environment for the leaders of tomorrow.', 'order' => 2]
        ];
        foreach ($messages as $msg) {
            \App\Models\CollegeMessage::firstOrCreate(
                ['name' => $msg['name']],
                [
                    'designation' => $msg['designation'],
                    'message' => $msg['message'],
                    'image' => 'default.jpg',
                    'order' => $msg['order'],
                    'status' => 1
                ]
            );
        }

        // 6. Faculties (Teachers)
        if (class_exists(\App\Models\Teacher::class)) {
            $teachers = [
                ['name' => 'Mr. Dipendra Rai', 'role' => 'HOD, Science Department'],
                ['name' => 'Ms. Sabina Gurung', 'role' => 'Mathematics Instructor'],
                ['name' => 'Mr. Kamal Dahal', 'role' => 'English Literature Teacher'],
                ['name' => 'Mrs. Goma Neupane', 'role' => 'Primary Coordinator']
            ];
            foreach ($teachers as $t) {
                \App\Models\Teacher::firstOrCreate(
                    ['name' => $t['name']],
                    [
                        'role' => $t['role'],
                        'image' => 'default.jpg',
                        'staff_type' => 'teaching'
                    ]
                );
            }
        }

    }
}
