<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Memo;
use App\Models\Release;
use App\Models\Resolution;
use App\Models\TrainingAnnouncement;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransparencyWallSeeder extends Seeder
{
    /**
     * Seed the Transparency Wall with sample data.
     */
    public function run(): void
    {
        // Get admin user for created_by
        $admin = User::where('role', 'super_admin')->first();
        $adminId = $admin ? $admin->id : 1;

        $this->seedMemos($adminId);
        $this->seedResolutions($adminId);
        $this->seedCollections($adminId);
        $this->seedReleases($adminId);
        $this->seedTrainingAnnouncements($adminId);
    }

    private function seedMemos(int $adminId): void
    {
        $memos = [
            [
                'memo_number' => '2026-001',
                'title' => 'Deadline Extension for Membership Dues Payment',
                'content' => "To: All CSUFA Members\nFrom: Office of the President\n\nDear Members,\n\nDue to numerous requests from faculty members across all campuses, the Executive Board has decided to extend the deadline for membership dues payment.\n\nOriginal Deadline: January 15, 2026\nNew Deadline: January 31, 2026\n\nPlease be reminded that only members with updated dues are eligible to:\n- Vote in the upcoming General Assembly\n- File benefit claims\n- Participate in CSUFA-sponsored trainings\n\nPayment may be made through:\n1. Direct deduction from salary (submit authorization form to HR)\n2. Over-the-counter payment at the CSUFA Office\n3. Bank transfer to CSUFA Account (Land Bank 1234-5678-90)\n\nFor inquiries, please contact the CSUFA Treasurer.\n\nThank you for your continued support.\n\nDr. Ricardo dela Cruz\nCSUFA President",
                'category' => 'Financial',
                'issued_by' => 'President',
                'effective_date' => '2026-01-10',
                'expiration_date' => '2026-01-31',
                'requires_acknowledgment' => false,
                'priority' => 'Normal',
                'status' => 'Published',
                'views_count' => 145,
                'published_at' => now()->subDays(5),
                'created_by' => $adminId,
            ],
            [
                'memo_number' => '2026-002',
                'title' => 'Updated Office Hours for CSUFA Secretariat',
                'content' => "To: All CSUFA Members\nFrom: CSUFA Secretariat\n\nEffective immediately, the CSUFA Office will observe the following schedule:\n\nRegular Days (Monday-Friday):\nMorning: 8:00 AM - 12:00 NN\nAfternoon: 1:00 PM - 5:00 PM\n\nSaturdays:\n8:00 AM - 12:00 NN (Skeleton staff only)\n\nSundays and Holidays: CLOSED\n\nFor urgent concerns outside office hours, please contact:\n- Secretary: 0917-xxx-xxxx\n- Treasurer: 0918-xxx-xxxx\n\nNote: Processing of benefit claims requires at least 3 working days. Please plan accordingly.\n\nMaria Santos\nCSUFA Secretary",
                'category' => 'Administrative',
                'issued_by' => 'Secretary',
                'effective_date' => '2026-01-15',
                'expiration_date' => null,
                'requires_acknowledgment' => false,
                'priority' => 'Normal',
                'status' => 'Published',
                'views_count' => 89,
                'published_at' => now()->subDays(3),
                'created_by' => $adminId,
            ],
            [
                'memo_number' => '2026-003',
                'title' => 'Mandatory Attendance - General Assembly Meeting',
                'content' => "To: All CSUFA Members\nFrom: Office of the Secretary\n\nThe CSUFA General Assembly for the 1st Semester, AY 2025-2026 is scheduled as follows:\n\nDate: February 15, 2026 (Saturday)\nTime: 1:00 PM - 5:00 PM\nVenue: CSU Convention Center, Carig Campus\nMode: Hybrid (In-person and via Zoom)\n\nAGENDA:\n1. Call to Order\n2. Prayer\n3. Reading of Minutes\n4. Treasurer's Report\n5. Committee Reports\n6. Old Business\n7. New Business\n   - Proposed Amendment to Constitution\n   - Budget Allocation for AY 2026-2027\n   - Election Guidelines\n8. Announcements\n9. Adjournment\n\nIMPORTANT REMINDERS:\n- Attendance is MANDATORY for all members\n- Members attending online must register via the link: [registration link]\n- Only members with updated dues may vote\n- Please bring valid ID for verification\n\nAbsences without prior written notice may result in:\n- Ineligibility for certain benefits\n- Notation in membership records\n\nFor excused absences, submit written request to the Secretary at least 3 days before the GA.\n\nMaria Santos\nCSUFA Secretary",
                'category' => 'Urgent',
                'issued_by' => 'Secretary',
                'effective_date' => '2026-02-15',
                'expiration_date' => '2026-02-15',
                'requires_acknowledgment' => true,
                'priority' => 'High',
                'status' => 'Published',
                'views_count' => 234,
                'published_at' => now()->subDays(1),
                'created_by' => $adminId,
            ],
        ];

        foreach ($memos as $memo) {
            Memo::firstOrCreate(['memo_number' => $memo['memo_number']], $memo);
        }

        $this->command->info('✓ Seeded 3 memos');
    }

    private function seedResolutions(int $adminId): void
    {
        $resolutions = [
            [
                'resolution_number' => 'RES-2026-001',
                'title' => 'Resolution Approving Enhanced Medical Assistance Program',
                'content' => "WHEREAS, the CSUFA Executive Board recognizes the need to provide better healthcare support to its members and their dependents;\n\nWHEREAS, the current medical assistance ceiling of ₱15,000 per year has remained unchanged for the past five years despite rising healthcare costs;\n\nWHEREAS, the Finance Committee has certified the availability of funds to support an enhanced program;\n\nNOW, THEREFORE, BE IT RESOLVED, as it is hereby resolved, that:\n\n1. The annual medical assistance ceiling is hereby increased from ₱15,000 to ₱25,000 per member.\n\n2. Coverage is extended to include:\n   a. Outpatient consultations\n   b. Laboratory and diagnostic procedures\n   c. Hospitalization\n   d. Medications (with prescription)\n   e. Dental procedures (up to ₱5,000)\n   f. Optical services (up to ₱3,000)\n\n3. Dependents (spouse and children up to 21 years old) may claim up to 50% of the member's unused allocation.\n\n4. This resolution shall take effect on March 1, 2026.\n\nADOPTED this 20th day of January 2026 at Tuguegarao City.",
                'resolved_date' => '2026-01-20',
                'votes_for' => 45,
                'votes_against' => 3,
                'votes_abstain' => 2,
                'status' => 'Published',
                'views_count' => 312,
                'published_at' => now()->subDays(7),
                'created_by' => $adminId,
            ],
            [
                'resolution_number' => 'RES-2026-002',
                'title' => 'Resolution on Membership Dues Adjustment',
                'content' => "WHEREAS, the last adjustment of membership dues was made in 2020;\n\nWHEREAS, the cost of operations and services provided by CSUFA has significantly increased;\n\nWHEREAS, the Association aims to expand its programs and services to better serve its members;\n\nWHEREAS, a survey conducted among members showed 67% support for a reasonable dues increase;\n\nNOW, THEREFORE, BE IT RESOLVED, as it is hereby resolved, that:\n\n1. Monthly membership dues shall be adjusted as follows:\n   - Instructor I-III: ₱150 (from ₱100)\n   - Assistant Professor I-IV: ₱200 (from ₱150)\n   - Associate Professor I-V: ₱250 (from ₱200)\n   - Professor I-VI: ₱300 (from ₱250)\n\n2. Special assessment of ₱500 annually for the Building Fund shall continue.\n\n3. Life membership option at ₱50,000 one-time payment is now available.\n\n4. The new rates shall take effect on July 1, 2026 to coincide with the new fiscal year.\n\n5. Members who pay annual dues in full by June 30 shall receive a 10% discount.\n\nADOPTED this 25th day of January 2026 at Tuguegarao City.",
                'resolved_date' => '2026-01-25',
                'votes_for' => 38,
                'votes_against' => 8,
                'votes_abstain' => 4,
                'status' => 'Published',
                'views_count' => 187,
                'published_at' => now()->subDays(4),
                'created_by' => $adminId,
            ],
            [
                'resolution_number' => 'RES-2026-003',
                'title' => 'Resolution Establishing the CSUFA Scholarship Fund for Faculty Dependents',
                'content' => "WHEREAS, CSUFA recognizes the importance of education and the financial challenges faced by members in sending their children to college;\n\nWHEREAS, the Association has accumulated sufficient funds to establish a sustainable scholarship program;\n\nWHEREAS, investing in the education of our members' dependents reflects our commitment to the welfare of the CSUFA family;\n\nNOW, THEREFORE, BE IT RESOLVED, as it is hereby resolved, that:\n\n1. A CSUFA Scholarship Fund is hereby established with an initial endowment of ₱2,000,000.\n\n2. The fund shall provide the following types of scholarships:\n   a. Academic Excellence Scholarship - ₱30,000/year\n      (For dependents with GWA of 1.50 or better)\n   b. Need-Based Scholarship - ₱20,000/year\n      (Based on family income assessment)\n   c. Athletic/Arts Scholarship - ₱15,000/year\n      (For outstanding achievers in sports or arts)\n\n3. Eligibility requirements:\n   - Parent must be CSUFA member in good standing for at least 2 years\n   - Dependent must be enrolled in a recognized HEI\n   - Maintain satisfactory academic standing\n\n4. A Scholarship Committee shall be created to manage the fund and select recipients.\n\n5. Application period shall be every May 1-31 for the following academic year.\n\nADOPTED this 28th day of January 2026 at Tuguegarao City.",
                'resolved_date' => '2026-01-28',
                'votes_for' => 48,
                'votes_against' => 0,
                'votes_abstain' => 2,
                'status' => 'Published',
                'views_count' => 256,
                'published_at' => now()->subDays(2),
                'created_by' => $adminId,
            ],
        ];

        foreach ($resolutions as $resolution) {
            Resolution::firstOrCreate(['resolution_number' => $resolution['resolution_number']], $resolution);
        }

        $this->command->info('✓ Seeded 3 resolutions');
    }

    private function seedCollections(int $adminId): void
    {
        $collections = [
            [
                'report_number' => 'COL-2026-JAN',
                'title' => 'Monthly Collection Report - January 2026',
                'content' => 'This report presents the collection summary for January 2026. Total collection reached ₱485,750.00, representing a 92% collection rate. Outstanding balances from 2025 are being carried forward and monitored for collection.',
                'period_covered' => 'January 1-31, 2026',
                'total_collected' => 485750.00,
                'breakdown_data' => json_encode([
                    'categories' => [
                        ['name' => 'Monthly Dues (Main Campus)', 'amount' => 125000.00],
                        ['name' => 'Monthly Dues (Carig Campus)', 'amount' => 85500.00],
                        ['name' => 'Monthly Dues (Piat Campus)', 'amount' => 45000.00],
                        ['name' => 'Monthly Dues (Sanchez Mira Campus)', 'amount' => 52750.00],
                        ['name' => 'Monthly Dues (Lal-lo Campus)', 'amount' => 38500.00],
                        ['name' => 'Monthly Dues (Gonzaga Campus)', 'amount' => 41000.00],
                        ['name' => 'Monthly Dues (Lasam Campus)', 'amount' => 35000.00],
                        ['name' => 'Special Assessment', 'amount' => 45000.00],
                        ['name' => 'Late Payment Fees', 'amount' => 3500.00],
                        ['name' => 'Miscellaneous', 'amount' => 14500.00],
                    ],
                    'notes' => 'Collection rate: 92%. Outstanding balance carried forward from 2025: ₱125,000',
                    'prepared_by' => 'Juan dela Cruz, Treasurer',
                    'verified_by' => 'Elena Villanueva, Auditor',
                ]),
                'report_type' => 'Monthly',
                'status' => 'Published',
                'views_count' => 156,
                'published_at' => now()->subDays(5),
                'created_by' => $adminId,
            ],
            [
                'report_number' => 'COL-2025-Q4',
                'title' => 'Quarterly Collection Report - Q4 2025',
                'content' => 'This quarterly report covers collections from October to December 2025. The fourth quarter achieved ₱1,425,680.00 in total collections, exceeding our target by 5%. Year-end collection efforts were particularly successful.',
                'period_covered' => 'October - December 2025',
                'total_collected' => 1425680.00,
                'breakdown_data' => json_encode([
                    'categories' => [
                        ['name' => 'October Collections', 'amount' => 465250.00],
                        ['name' => 'November Collections', 'amount' => 478930.00],
                        ['name' => 'December Collections', 'amount' => 481500.00],
                    ],
                    'by_type' => [
                        ['name' => 'Regular Dues', 'amount' => 1250000.00],
                        ['name' => 'Special Assessment', 'amount' => 135000.00],
                        ['name' => 'Interest Income', 'amount' => 25680.00],
                        ['name' => 'Miscellaneous', 'amount' => 15000.00],
                    ],
                    'notes' => 'Year-end collection exceeded target by 5%. Total active members: 485',
                    'prepared_by' => 'Juan dela Cruz, Treasurer',
                    'verified_by' => 'Elena Villanueva, Auditor',
                    'approved_by' => 'Dr. Ricardo dela Cruz, President',
                ]),
                'report_type' => 'Quarterly',
                'status' => 'Published',
                'views_count' => 234,
                'published_at' => now()->subDays(30),
                'created_by' => $adminId,
            ],
            [
                'report_number' => 'COL-2025-ANNUAL',
                'title' => 'Annual Collection Report - Year 2025',
                'content' => 'This is the comprehensive annual collection report for fiscal year 2025. Total collections reached ₱5,825,430.00 - the highest in CSUFA history. The Building Fund target was achieved, and we saw an 8.5% year-over-year growth. Special thanks to all campus representatives for their diligent collection efforts.',
                'period_covered' => 'January - December 2025',
                'total_collected' => 5825430.00,
                'breakdown_data' => json_encode([
                    'by_quarter' => [
                        ['name' => 'Q1 (Jan-Mar)', 'amount' => 1385250.00],
                        ['name' => 'Q2 (Apr-Jun)', 'amount' => 1425000.00],
                        ['name' => 'Q3 (Jul-Sep)', 'amount' => 1589500.00],
                        ['name' => 'Q4 (Oct-Dec)', 'amount' => 1425680.00],
                    ],
                    'by_campus' => [
                        ['name' => 'Main Campus', 'amount' => 1850000.00],
                        ['name' => 'Carig Campus', 'amount' => 1125000.00],
                        ['name' => 'Piat Campus', 'amount' => 625000.00],
                        ['name' => 'Sanchez Mira Campus', 'amount' => 725430.00],
                        ['name' => 'Lal-lo Campus', 'amount' => 550000.00],
                        ['name' => 'Gonzaga Campus', 'amount' => 500000.00],
                        ['name' => 'Lasam Campus', 'amount' => 450000.00],
                    ],
                    'summary' => [
                        'total_members' => 485,
                        'active_paying' => 465,
                        'collection_rate' => '95.87%',
                        'year_over_year_growth' => '+8.5%',
                    ],
                    'notes' => 'Highest collection in CSUFA history. Building fund target achieved.',
                    'prepared_by' => 'Juan dela Cruz, Treasurer',
                    'verified_by' => 'Elena Villanueva, Auditor',
                    'approved_by' => 'Dr. Ricardo dela Cruz, President',
                    'presented_at' => 'Annual General Assembly, January 15, 2026',
                ]),
                'report_type' => 'Annual',
                'status' => 'Published',
                'views_count' => 412,
                'published_at' => now()->subDays(15),
                'created_by' => $adminId,
            ],
        ];

        foreach ($collections as $collection) {
            Collection::firstOrCreate(['report_number' => $collection['report_number']], $collection);
        }

        $this->command->info('✓ Seeded 3 collections');
    }

    private function seedReleases(int $adminId): void
    {
        $releases = [
            [
                'release_number' => 'PR-2026-001',
                'title' => 'CSUFA Statement on Proposed Tuition Fee Increase',
                'release_type' => 'Press Release',
                'content' => "FOR IMMEDIATE RELEASE\nJanuary 18, 2026\n\nCSU FACULTY ASSOCIATION CALLS FOR DIALOGUE ON PROPOSED TUITION INCREASE\n\nTUGUEGARAO CITY - The Cagayan State University Faculty Association (CSUFA) today called for a thorough consultation process regarding the proposed 15% tuition fee increase for Academic Year 2026-2027.\n\nIn a statement issued by CSUFA President Dr. Ricardo dela Cruz, the association emphasized the need to balance institutional development with student accessibility.\n\n\"While we understand the university's need for additional resources to improve facilities and services, we urge the administration to consider the economic situation of our students and their families,\" Dr. dela Cruz said.\n\nThe association proposes the following:\n\n1. Phased implementation of any fee increase over two academic years\n2. Expansion of scholarship programs and financial aid\n3. Transparent allocation of additional revenues\n4. Regular consultation with stakeholders including faculty, students, and parents\n\n\"As faculty members, we are committed to quality education. We believe this can be achieved through collaborative decision-making that considers all stakeholders,\" the statement concluded.\n\nCSUFA represents over 485 faculty members across all CSU campuses in the Cagayan Valley region.\n\n###\n\nFOR MORE INFORMATION:\nDr. Ricardo dela Cruz\nPresident, CSUFA\nEmail: president@csufa.edu.ph\nMobile: 0917-xxx-xxxx",
                'contact_person' => 'Dr. Ricardo dela Cruz',
                'contact_email' => 'president@csufa.edu.ph',
                'contact_phone' => '0917-123-4567',
                'status' => 'Published',
                'views_count' => 523,
                'published_at' => now()->subDays(10),
                'created_by' => $adminId,
            ],
            [
                'release_number' => 'PR-2026-002',
                'title' => 'CSUFA Welcomes New Policy on Anti-Sexual Harassment',
                'release_type' => 'Official Statement',
                'content' => "OFFICIAL STATEMENT\nJanuary 22, 2026\n\nCSUFA WELCOMES STRENGTHENED ANTI-SEXUAL HARASSMENT POLICY\n\nThe Cagayan State University Faculty Association (CSUFA) expresses its full support for the university's newly revised Anti-Sexual Harassment Policy, which took effect this month.\n\nKey provisions welcomed by CSUFA include:\n\n• Clearer definitions of harassment including online/cyber harassment\n• Streamlined reporting procedures with multiple reporting channels\n• Stronger protections for complainants against retaliation\n• Mandatory training for all faculty and staff\n• Regular policy review every three years\n\nCSUFA Secretary Maria Santos, who served in the policy review committee, noted: \"This policy reflects our collective commitment to maintaining a safe and respectful learning environment. The inclusion of provisions on cyber harassment and third-party complaints addresses modern realities.\"\n\nThe association encourages all members to:\n1. Attend the upcoming mandatory training sessions\n2. Familiarize themselves with reporting procedures\n3. Model respectful behavior in all interactions\n4. Support colleagues who may be victims of harassment\n\nCSUFA reminds members that the association can provide confidential support and assistance to any member involved in harassment cases.\n\nFor support or inquiries, contact the CSUFA Welfare Committee.\n\n###",
                'contact_person' => 'Maria Santos',
                'contact_email' => 'secretary@csufa.edu.ph',
                'contact_phone' => '0918-234-5678',
                'status' => 'Published',
                'views_count' => 287,
                'published_at' => now()->subDays(6),
                'created_by' => $adminId,
            ],
            [
                'release_number' => 'PR-2026-003',
                'title' => 'Position Paper on Proposed Hybrid Work Arrangement for Faculty',
                'release_type' => 'Position Paper',
                'content' => "POSITION PAPER\nJanuary 25, 2026\n\nCSUFA POSITION ON PROPOSED HYBRID WORK ARRANGEMENT FOR FACULTY\n\nI. BACKGROUND\n\nThe COVID-19 pandemic has demonstrated that certain academic functions can be effectively performed through remote work. As the university considers institutionalizing hybrid work arrangements, CSUFA presents this position paper to articulate the faculty's perspective.\n\nII. CSUFA POSITION\n\nCSUFA SUPPORTS a carefully designed hybrid work policy with the following principles:\n\nA. WORK-LIFE BALANCE\n- Hybrid arrangements should improve faculty work-life balance\n- Core hours should allow flexibility for personal obligations\n- Asynchronous work options for research and administrative tasks\n\nB. PRODUCTIVITY & QUALITY\n- Clear deliverables and performance metrics\n- Proper tools and technology support\n- Regular assessment of student learning outcomes\n\nC. EQUITY & INCLUSION\n- Equal opportunities regardless of work location\n- Consider faculty with disabilities or caregiving responsibilities\n- Fair evaluation criteria for all work modalities\n\nD. INFRASTRUCTURE SUPPORT\n- Provision of necessary equipment (laptops, internet allowance)\n- Technical support for online teaching platforms\n- Secure access to university systems\n\nIII. RECOMMENDATIONS\n\n1. Pilot program for one semester before full implementation\n2. Maximum of 40% remote work for teaching faculty\n3. Full remote option for administrative/research days\n4. Regular review of policy effectiveness\n5. Faculty input in policy refinement\n\nIV. CONCLUSION\n\nCSUFA believes that a well-implemented hybrid work arrangement can benefit both faculty and students while maintaining academic quality. We stand ready to collaborate with the administration in developing detailed guidelines.\n\nSubmitted by:\nCSUFA Executive Board\n\n###",
                'contact_person' => 'Dr. Ricardo dela Cruz',
                'contact_email' => 'president@csufa.edu.ph',
                'contact_phone' => '0917-123-4567',
                'status' => 'Published',
                'views_count' => 198,
                'published_at' => now()->subDays(3),
                'created_by' => $adminId,
            ],
        ];

        foreach ($releases as $release) {
            Release::firstOrCreate(['release_number' => $release['release_number']], $release);
        }

        $this->command->info('✓ Seeded 3 releases');
    }

    private function seedTrainingAnnouncements(int $adminId): void
    {
        $trainings = [
            [
                'training_number' => 'TRN-2026-001',
                'title' => 'Research Methodology Workshop: From Concept to Publication',
                'training_type' => 'Workshop',
                'content' => "A comprehensive 3-day workshop designed to equip faculty members with essential research skills, from developing research concepts to publishing in reputable journals.\n\nThis workshop is part of CSUFA's commitment to enhancing the research capabilities of its members and supporting the university's research agenda.",
                'speaker_facilitator' => 'Dr. Jose Maria Reyes - Research Director, Philippine Association of Research Educators',
                'speaker_bio' => 'Dr. Reyes has over 20 years of experience in educational research with more than 50 publications in ISI-indexed journals. He has conducted research methodology workshops in over 30 universities across Southeast Asia.',
                'date_start' => now()->addDays(30)->format('Y-m-d H:i:s'),
                'date_end' => now()->addDays(32)->format('Y-m-d H:i:s'),
                'venue' => 'CSU Graduate School Conference Room, Main Campus',
                'mode' => 'Face-to-face',
                'max_participants' => 40,
                'registration_deadline' => now()->addDays(25)->format('Y-m-d'),
                'registration_fee' => 500.00,
                'target_participants' => 'All CSUFA members interested in research',
                'learning_objectives' => "1. Identify researchable problems and formulate research questions\n2. Design appropriate research methodology\n3. Apply statistical tools for data analysis\n4. Write research manuscripts following journal guidelines\n5. Navigate the peer review process successfully",
                'requirements' => "1. Must be a CSUFA member in good standing\n2. Bring laptop with MS Office and SPSS/JASP installed\n3. Come with a research idea or ongoing research project\n4. Commit to all 3 days of the workshop",
                'contact_person' => 'Dr. Ricardo dela Cruz',
                'contact_details' => 'president@csufa.edu.ph | 0917-123-4567',
                'status' => 'Open for Registration',
                'views_count' => 156,
                'published_at' => now()->subDays(5),
                'created_by' => $adminId,
            ],
            [
                'training_number' => 'TRN-2026-002',
                'title' => 'Effective Classroom Management in the New Normal',
                'training_type' => 'Seminar',
                'content' => "A one-day seminar focusing on practical strategies for managing face-to-face, online, and hybrid classes. Learn from experienced educators who have successfully adapted to the changing educational landscape.\n\nThis seminar addresses common challenges faced by faculty in maintaining student engagement and discipline across different learning modalities.",
                'speaker_facilitator' => 'Prof. Elena Magbanua - Education Specialist, Department of Education Region 02',
                'speaker_bio' => 'Prof. Magbanua is a recognized expert in classroom management with 25 years of teaching experience. She has trained over 5,000 teachers across Cagayan Valley and has authored three books on educational management.',
                'date_start' => now()->addDays(45)->format('Y-m-d H:i:s'),
                'date_end' => now()->addDays(45)->format('Y-m-d H:i:s'),
                'venue' => 'Zoom Meeting (Link will be provided upon registration)',
                'mode' => 'Online',
                'max_participants' => 100,
                'registration_deadline' => now()->addDays(40)->format('Y-m-d'),
                'registration_fee' => 0.00,
                'target_participants' => 'All CSUFA members',
                'learning_objectives' => "1. Apply evidence-based classroom management strategies\n2. Utilize technology tools for student engagement\n3. Handle challenging student behaviors effectively\n4. Create inclusive learning environments\n5. Develop personalized classroom management plans",
                'requirements' => "1. Must be a CSUFA member\n2. Stable internet connection for online participation\n3. Webcam and microphone enabled during sessions",
                'contact_person' => 'Maria Santos',
                'contact_details' => 'secretary@csufa.edu.ph | 0918-234-5678',
                'status' => 'Open for Registration',
                'views_count' => 89,
                'published_at' => now()->subDays(3),
                'created_by' => $adminId,
            ],
            [
                'training_number' => 'TRN-2026-003',
                'title' => 'Financial Literacy and Investment Planning for Educators',
                'training_type' => 'Seminar',
                'content' => "A practical seminar on personal finance management specifically designed for faculty members. Learn how to maximize your salary, understand government benefits, and plan for a secure retirement.\n\nCovering topics from budgeting to investing, this seminar will help you make informed financial decisions.",
                'speaker_facilitator' => 'Mr. Antonio Cruz, CFA - Certified Financial Planner, Wealth Management Consultant',
                'speaker_bio' => 'Mr. Cruz is a Certified Financial Analyst with 15 years of experience in wealth management. He specializes in financial planning for government employees and educators.',
                'date_start' => now()->addDays(60)->format('Y-m-d H:i:s'),
                'date_end' => now()->addDays(60)->format('Y-m-d H:i:s'),
                'venue' => 'CSU Multi-Purpose Hall, Carig Campus + Zoom (Hybrid)',
                'mode' => 'Hybrid',
                'max_participants' => 150,
                'registration_deadline' => now()->addDays(55)->format('Y-m-d'),
                'registration_fee' => 200.00,
                'target_participants' => 'All CSUFA members',
                'learning_objectives' => "1. Create and maintain a personal budget\n2. Understand GSIS benefits and retirement options\n3. Evaluate investment vehicles (stocks, bonds, mutual funds)\n4. Build an emergency fund and manage debt\n5. Plan for children's education and retirement",
                'requirements' => "1. Must be a CSUFA member\n2. Bring calculator\n3. For online participants: stable internet connection",
                'contact_person' => 'Juan dela Cruz',
                'contact_details' => 'treasurer@csufa.edu.ph | 0919-345-6789',
                'status' => 'Open for Registration',
                'views_count' => 67,
                'published_at' => now()->subDays(1),
                'created_by' => $adminId,
            ],
        ];

        foreach ($trainings as $training) {
            TrainingAnnouncement::firstOrCreate(['training_number' => $training['training_number']], $training);
        }

        $this->command->info('✓ Seeded 3 training announcements');
    }
}
