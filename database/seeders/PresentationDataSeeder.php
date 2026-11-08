<?php

namespace Database\Seeders;

use App\Models\TrainingSeminar;
use App\Models\TransparencyPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class PresentationDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $adminId = $admin ? $admin->id : 1;

        $this->seedTransparencyPosts($adminId);
        $this->seedTrainingSeminars($adminId);
    }

    private function seedTransparencyPosts(int $adminId): void
    {
        $posts = [
            [
                'title' => 'Annual Financial Report for AY 2025-2026',
                'content' => "The CSUFA Finance Committee is pleased to present the Annual Financial Report for Academic Year 2025-2026.\n\nTotal Collections: ₱2,450,000.00\nTotal Disbursements: ₱1,875,000.00\nNet Fund Balance: ₱575,000.00\n\nBreakdown of Disbursements:\n• Medical & Hospitalization Claims: ₱650,000.00\n• Death & Calamity Benefits: ₱320,000.00\n• Training & Professional Development: ₱280,000.00\n• Operational Expenses: ₱185,000.00\n• General Assembly & Events: ₱240,000.00\n• Miscellaneous: ₱200,000.00\n\nThe complete audited financial statement is available for review at the CSUFA Office. All members are encouraged to examine the report and raise any concerns during the next General Assembly.\n\nPrepared by:\nCSUFA Finance Committee",
                'type' => 'news',
                'visibility' => 'public',
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(2),
                'created_by' => $adminId,
            ],
            [
                'title' => 'Notice of General Assembly — 2nd Semester AY 2025-2026',
                'content' => "All CSUFA members are hereby notified of the General Assembly scheduled as follows:\n\nDate: May 10, 2026 (Saturday)\nTime: 1:00 PM – 5:00 PM\nVenue: CSU Convention Center, Carig Campus\nMode: Hybrid (In-person and via Zoom)\n\nAgenda:\n1. Call to Order and Prayer\n2. Reading and Approval of Minutes\n3. President's Report\n4. Treasurer's Financial Report\n5. Committee Reports\n6. Old Business\n   • Update on Constitution Amendment\n7. New Business\n   • Proposed Increase in Medical Benefits Ceiling\n   • Election Guidelines for AY 2026-2027\n8. Open Forum\n9. Adjournment\n\nAttendance is mandatory for all active members. Members attending online must register through the provided link. Please bring your valid CSU ID for verification.\n\nFor queries, contact the CSUFA Secretary.",
                'type' => 'announcement',
                'visibility' => 'public',
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'created_by' => $adminId,
            ],
            [
                'title' => 'Resolution No. 2026-003: Enhanced Death and Calamity Benefits',
                'content' => "WHEREAS, the Executive Board has received feedback regarding the adequacy of current death and calamity benefits;\n\nWHEREAS, the Finance Committee has certified fund availability to support enhanced benefits;\n\nNOW, THEREFORE, BE IT RESOLVED:\n\n1. Death benefit for active members is increased from ₱20,000 to ₱30,000.\n2. Death benefit for dependents is increased from ₱10,000 to ₱15,000.\n3. Calamity assistance is increased from ₱5,000 to ₱10,000 per verified incident.\n4. A new provision for fire victims is established at ₱15,000.\n5. These changes shall take effect immediately upon publication.\n\nVotes: 42 For | 2 Against | 1 Abstain\n\nAdopted on March 15, 2026 at Tuguegarao City.\n\nAttested by:\nCSUFA Executive Board",
                'type' => 'resolution',
                'visibility' => 'public',
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(10),
                'created_by' => $adminId,
            ],
            [
                'title' => 'Mid-Year Fund Release Summary — January to March 2026',
                'content' => "The CSUFA Treasurer's Office provides the following mid-year summary of fund releases:\n\nMedical & Hospitalization Claims:\n• Total claims processed: 87\n• Total amount released: ₱348,500.00\n• Average claim: ₱4,005.75\n\nDeath Benefits:\n• Claims processed: 4\n• Amount released: ₱90,000.00\n\nCalamity Assistance:\n• Claims processed: 12\n• Amount released: ₱72,000.00\n\nTraining Sponsorships:\n• Faculty sponsored: 23\n• Amount released: ₱115,000.00\n\nTotal Released (Jan–Mar 2026): ₱625,500.00\nRemaining Fund Balance: ₱1,824,500.00\n\nAll disbursements have been verified by the Auditor and approved by the Executive Board. Detailed records are available upon request.",
                'type' => 'release',
                'visibility' => 'members_only',
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(7),
                'created_by' => $adminId,
            ],
            [
                'title' => 'Updated Membership Dues Schedule for AY 2026-2027',
                'content' => "Effective June 2026, the CSUFA membership dues schedule has been updated as follows:\n\nMonthly Contribution Rates:\n• Instructor I-III: ₱200/month\n• Assistant Professor I-IV: ₱300/month\n• Associate Professor I-V: ₱400/month\n• Professor I-VI: ₱500/month\n\nPayment Options:\n1. Automatic salary deduction (recommended)\n2. Over-the-counter at the CSUFA Office\n3. Bank transfer to CSUFA Land Bank Account\n\nMembers with outstanding dues for more than 3 months will be temporarily suspended from benefit claims until dues are settled.\n\nFor salary deduction enrollment, please submit the updated authorization form to your campus HR office.\n\nCSUFA Finance Committee",
                'type' => 'announcement',
                'visibility' => 'public',
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(14),
                'created_by' => $adminId,
            ],
            [
                'title' => 'CSUFA Partners with DepEd for Faculty Wellness Program',
                'content' => "The Cagayan State University Faculty Association is proud to announce a new partnership with the Department of Education Regional Office for a joint Faculty Wellness Program.\n\nProgram Highlights:\n• Free annual medical check-ups for all CSUFA members\n• Mental health counseling sessions (quarterly)\n• Fitness and wellness workshops\n• Stress management seminars\n• Nutritional assessment and guidance\n\nSchedule:\n• Medical Check-ups: Every first Saturday of the month\n• Counseling Sessions: By appointment\n• Wellness Workshops: Monthly (schedule to be announced)\n\nVenue: CSU Health Services Center, Carig Campus\nEligibility: All active CSUFA members and their immediate dependents\n\nRegistration for the first batch of medical check-ups is now open. Please sign up through the CSUFA portal or visit the office.\n\nThis initiative reflects our commitment to the holistic well-being of our faculty members.",
                'type' => 'news',
                'visibility' => 'public',
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(3),
                'created_by' => $adminId,
            ],
            [
                'title' => 'Quarterly Audit Report — Q1 2026',
                'content' => "In compliance with the CSUFA Constitution, the Office of the Auditor presents the Quarterly Audit Report for Q1 2026.\n\nAudit Findings:\n✓ All financial transactions properly documented\n✓ Bank reconciliation statements verified\n✓ Disbursement vouchers complete with supporting documents\n✓ Collection records match bank deposits\n\nRecommendations:\n1. Implement digital receipt system for faster processing\n2. Establish emergency fund equivalent to 3 months' operating expenses\n3. Conduct mid-year budget review\n\nThe auditor certifies that all funds have been utilized in accordance with approved budgets and resolutions. No irregularities were found.\n\nPrepared by:\nCSUFA University Auditor",
                'type' => 'other',
                'visibility' => 'members_only',
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(20),
                'created_by' => $adminId,
            ],
            [
                'title' => 'Allegation Response: Clarification on Fund Usage Concerns',
                'content' => "The CSUFA Executive Board addresses the concerns raised by some members regarding fund allocation for the recently concluded Leadership Summit.\n\nClarification:\n1. The total expenditure for the event was ₱185,000 — not ₱250,000 as alleged.\n2. Complete documentation including receipts, vouchers, and liquidation reports are available at the office.\n3. The event was pre-approved in the annual budget during the General Assembly held on September 2025.\n4. Competitive bidding was conducted for catering and venue services.\n\nThe Board reaffirms its commitment to transparency and accountability. All members are welcome to review the complete event documentation at the CSUFA Office during regular hours.\n\nWe encourage members to verify information through official channels before circulating unconfirmed reports.\n\nCSUFA Executive Board",
                'type' => 'allegation',
                'visibility' => 'public',
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(25),
                'created_by' => $adminId,
            ],
            [
                'title' => 'Scholarship Grant for Members\' Dependents — Now Open',
                'content' => "CSUFA is pleased to announce the opening of applications for the Academic Scholarship Grant for dependents of active members.\n\nScholarship Details:\n• Full tuition fee assistance (up to ₱15,000/semester)\n• Book allowance of ₱3,000/semester\n• Available for undergraduate programs at any SUC\n\nEligibility:\n1. Dependent must be a child of an active CSUFA member\n2. Member must have at least 2 years of continuous membership\n3. Student must maintain a GWA of 1.75 or better\n4. Family annual gross income must not exceed ₱500,000\n\nRequirements:\n• Application form (available at CSUFA Office)\n• Latest grade report / transcript of records\n• Certificate of enrollment\n• Income Tax Return of parent-member\n• Birth certificate of the applicant\n\nDeadline: June 30, 2026\nSlots available: 20 scholars\n\nSubmit applications to the CSUFA Office, Carig Campus.",
                'type' => 'announcement',
                'visibility' => 'public',
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDay(),
                'created_by' => $adminId,
            ],
        ];

        foreach ($posts as $post) {
            TransparencyPost::firstOrCreate(['title' => $post['title']], $post);
        }

        $this->command->info('✓ Seeded '.count($posts).' transparency posts');
    }

    private function seedTrainingSeminars(int $adminId): void
    {
        $trainings = [
            [
                'title' => 'Research Writing Workshop: From Proposal to Publication',
                'description' => "A comprehensive 3-day workshop designed to guide faculty members through the entire research writing process. Participants will learn how to craft compelling research proposals, conduct literature reviews, apply appropriate methodologies, and write publication-ready manuscripts.\n\nTopics covered:\n• Identifying research gaps and formulating objectives\n• Literature review strategies\n• Research design and methodology\n• Data analysis and interpretation\n• Writing for ISI/Scopus-indexed journals\n• Navigating the peer review process\n\nParticipants will work on their own research manuscripts with guidance from experienced mentors.",
                'venue' => 'CSU Graduate School Building, Carig Campus',
                'facilitator' => 'Dr. Maria Elena Cruz, UP Diliman',
                'start_datetime' => now()->addDays(14)->setTime(8, 0),
                'end_datetime' => now()->addDays(16)->setTime(17, 0),
                'type' => 'workshop',
                'max_participants' => 40,
                'registration_fee' => 0,
                'status' => 'upcoming',
                'is_published' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Seminar on Outcomes-Based Education (OBE) Implementation',
                'description' => "This seminar aims to enhance faculty understanding of Outcomes-Based Education and its effective implementation in the classroom. Aligned with CHED and AACCUP standards.\n\nLearning Outcomes:\n• Understand the philosophy and framework of OBE\n• Design course syllabi aligned with program outcomes\n• Develop assessment rubrics for OBE\n• Implement continuous quality improvement (CQI)\n\nThis seminar is particularly relevant for faculty involved in accreditation preparation.",
                'venue' => 'CSU Convention Center, Carig Campus',
                'facilitator' => 'Dr. Roberto Santos, CHED Technical Panel',
                'start_datetime' => now()->addDays(7)->setTime(9, 0),
                'end_datetime' => now()->addDays(7)->setTime(16, 0),
                'type' => 'seminar',
                'max_participants' => 100,
                'registration_fee' => 0,
                'status' => 'upcoming',
                'is_published' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Advanced Data Analytics Using Python and R',
                'description' => "A hands-on training program for faculty who want to enhance their data analytics capabilities using Python and R programming languages.\n\nModules:\n• Introduction to Python for Data Science\n• Statistical Analysis with R\n• Data Visualization (Matplotlib, ggplot2)\n• Machine Learning Fundamentals\n• Working with Real-World Datasets\n\nParticipants must bring their own laptops with Python and R pre-installed. Setup instructions will be sent prior to the training.\n\nCertificate of Completion will be issued (24 CPD units).",
                'venue' => 'ICT Laboratory, College of Engineering, Carig Campus',
                'facilitator' => 'Prof. John Andrew Reyes, UPLB',
                'start_datetime' => now()->addDays(21)->setTime(8, 0),
                'end_datetime' => now()->addDays(24)->setTime(17, 0),
                'type' => 'training',
                'max_participants' => 30,
                'registration_fee' => 500.00,
                'status' => 'upcoming',
                'is_published' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'National Conference on Sustainable Development Goals in Higher Education',
                'description' => "CSUFA, in partnership with PASUC Region 02, invites all faculty members to participate in this national conference focusing on the integration of Sustainable Development Goals (SDGs) in higher education curricula and research.\n\nConference Tracks:\n• SDG Integration in Teaching and Learning\n• Research for Sustainable Communities\n• Green Campus Initiatives\n• Community Extension and SDGs\n• Innovation and Industry Partnership\n\nKeynote Speakers:\n• Dr. Ana Maria Gonzales, UN Philippines\n• Dr. Eduardo Villanueva, CHED Commissioner\n\nCall for Papers: Abstracts accepted until April 30, 2026.\nBest Paper Awards: ₱10,000 (1st), ₱7,000 (2nd), ₱5,000 (3rd)",
                'venue' => 'CSU Convention Center & Hotel & Restaurant, Carig Campus',
                'facilitator' => 'Multiple Speakers',
                'start_datetime' => now()->addDays(45)->setTime(8, 0),
                'end_datetime' => now()->addDays(47)->setTime(17, 0),
                'type' => 'conference',
                'max_participants' => 200,
                'registration_fee' => 1500.00,
                'status' => 'upcoming',
                'is_published' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Webinar: Mental Health and Well-being for Educators',
                'description' => "A free online webinar addressing the mental health challenges faced by educators and practical strategies for maintaining well-being.\n\nTopics:\n• Understanding burnout and compassion fatigue\n• Mindfulness and stress reduction techniques\n• Building resilience in academic settings\n• When to seek professional help\n• Creating supportive work environments\n\nThis webinar is open to all CSUFA members across all campuses. A Zoom link will be provided upon registration.\n\nCPD units: 4",
                'venue' => 'Online (Zoom)',
                'facilitator' => 'Dr. Patricia Ramos, Licensed Clinical Psychologist',
                'start_datetime' => now()->addDays(5)->setTime(14, 0),
                'end_datetime' => now()->addDays(5)->setTime(17, 0),
                'type' => 'webinar',
                'max_participants' => null,
                'registration_fee' => 0,
                'status' => 'upcoming',
                'is_published' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Flexible Learning Design and Technology Integration',
                'description' => "This training equips faculty with skills in designing flexible learning experiences using modern educational technology tools.\n\nTopics:\n• Blended learning course design\n• Using LMS platforms effectively (Moodle, Google Classroom)\n• Creating engaging multimedia content\n• Assessment tools for online learning\n• Accessibility and inclusive design\n• AI tools for education\n\nHands-on activities included. Participants will design a sample flexible learning module by the end of the training.",
                'venue' => 'Multimedia Room, College of Education, Carig Campus',
                'facilitator' => 'Dr. Liza Mae Fernandez, CSU-CITE',
                'start_datetime' => now()->subDays(10)->setTime(8, 0),
                'end_datetime' => now()->subDays(8)->setTime(17, 0),
                'type' => 'training',
                'max_participants' => 35,
                'registration_fee' => 0,
                'status' => 'completed',
                'is_published' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Leadership Summit: Empowering Faculty Leaders',
                'description' => "The annual CSUFA Leadership Summit brings together campus officers, committee chairs, and aspiring leaders for a day of capacity building.\n\nProgram:\n• Morning Session: Servant Leadership in Academe\n• Workshop: Strategic Planning for Campus Chapters\n• Panel Discussion: Challenges and Opportunities in FA Governance\n• Afternoon Session: Financial Literacy for Officers\n• Closing: Action Planning and Commitment Setting\n\nThis event is primarily for current campus officers but open to all interested members.",
                'venue' => 'CSU Convention Center, Carig Campus',
                'facilitator' => 'Atty. Miguel Torres & Dr. Carmen Aquino',
                'start_datetime' => now()->subDays(3)->setTime(8, 0),
                'end_datetime' => now()->subDays(3)->setTime(17, 0),
                'type' => 'seminar',
                'max_participants' => 80,
                'registration_fee' => 0,
                'status' => 'completed',
                'is_published' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Grant Writing and External Funding Workshop',
                'description' => "Learn how to write winning grant proposals and secure external funding for research and extension projects.\n\nCovered Topics:\n• Overview of funding agencies (DOST, CHED, NRCP, international agencies)\n• Understanding the grant application process\n• Writing a compelling research proposal\n• Budget preparation and justification\n• Compliance and reporting requirements\n• Tips from successful grant recipients\n\nGuest resource persons from DOST-PCAARRD and CHED-DARE will share insights.\n\n12 CPD units upon completion.",
                'venue' => 'Research & Extension Building, Carig Campus',
                'facilitator' => 'Dr. Angelica Reyes, DOST-PCAARRD',
                'start_datetime' => now()->addDays(30)->setTime(8, 0),
                'end_datetime' => now()->addDays(31)->setTime(17, 0),
                'type' => 'workshop',
                'max_participants' => 50,
                'registration_fee' => 300.00,
                'status' => 'upcoming',
                'is_published' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Ethics in Academic Research Seminar',
                'description' => "A mandatory seminar for faculty engaged in research involving human participants, focusing on ethical standards and responsible conduct of research.\n\nTopics:\n• Principles of research ethics (Belmont Report)\n• Informed consent and data privacy\n• IRB/Ethics Review Board processes\n• Plagiarism and research integrity\n• Data management and protection (RA 10173)\n\nCompletion of this seminar is a prerequisite for IRB clearance at CSU.",
                'venue' => 'CSU Graduate School Auditorium, Carig Campus',
                'facilitator' => 'Dr. Eduardo Santos, CSU Research Ethics Board Chair',
                'start_datetime' => now()->addDays(10)->setTime(9, 0),
                'end_datetime' => now()->addDays(10)->setTime(16, 0),
                'type' => 'seminar',
                'max_participants' => 60,
                'registration_fee' => 0,
                'status' => 'upcoming',
                'is_published' => true,
                'created_by' => $adminId,
            ],
        ];

        foreach ($trainings as $training) {
            TrainingSeminar::firstOrCreate(['title' => $training['title']], $training);
        }

        $this->command->info('✓ Seeded '.count($trainings).' trainings & seminars');
    }
}
