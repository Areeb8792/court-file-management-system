<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CourtCase;
use App\Models\Hearing;
use App\Models\Document;
use App\Models\ActivityLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@court.gov',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $clerk = User::create([
            'name' => 'Clerk Jane Smith',
            'email' => 'clerk@court.gov',
            'password' => Hash::make('password'),
            'role' => 'clerk',
        ]);

        $judge1 = User::create([
            'name' => 'Judge Hon. Albert Vance',
            'email' => 'judge@court.gov',
            'password' => Hash::make('password'),
            'role' => 'judge',
        ]);

        $judge2 = User::create([
            'name' => 'Judge Hon. Elena Kagan',
            'email' => 'judge2@court.gov',
            'password' => Hash::make('password'),
            'role' => 'judge',
        ]);

        $lawyer1 = User::create([
            'name' => 'Adv. Sarah Jenkins',
            'email' => 'lawyer@court.gov',
            'password' => Hash::make('password'),
            'role' => 'lawyer',
        ]);

        $lawyer2 = User::create([
            'name' => 'Adv. Michael Cho',
            'email' => 'lawyer2@court.gov',
            'password' => Hash::make('password'),
            'role' => 'lawyer',
        ]);

        $publicUser = User::create([
            'name' => 'John Public',
            'email' => 'public@court.gov',
            'password' => Hash::make('password'),
            'role' => 'public',
        ]);

        // 2. Create Cases
        // Case 1: Active, urgent, Criminal case with FIR.
        $case1 = CourtCase::create([
            'case_number' => 'CRT-2026-0001',
            'title' => 'State of New York vs. Robert Dow',
            'description' => 'Criminal charges of grand larceny, bank fraud, and illegal commercial transactions.',
            'category' => 'Criminal',
            'fir_number' => 'FIR-10928/2026',
            'petitioner' => 'State of New York',
            'respondent' => 'Robert Dow',
            'priority' => 'urgent',
            'status' => 'hearing_scheduled',
            'judge_id' => $judge1->id,
            'lawyer_id' => $lawyer1->id,
            'clerk_id' => $clerk->id,
            'filed_at' => Carbon::now()->subDays(5),
        ]);

        // Case 2: Civil, High priority, under review.
        $case2 = CourtCase::create([
            'case_number' => 'CRT-2026-0002',
            'title' => 'Apex Development Corp vs. Green Valley Conservation',
            'description' => 'Land dispute and injunction petition regarding the building permit at Sector 14 woodland area.',
            'category' => 'Civil',
            'fir_number' => null,
            'petitioner' => 'Apex Development Corp',
            'respondent' => 'Green Valley Conservation Agency',
            'priority' => 'high',
            'status' => 'under_review',
            'judge_id' => null,
            'lawyer_id' => $lawyer2->id,
            'clerk_id' => $clerk->id,
            'filed_at' => Carbon::now()->subDays(10),
        ]);

        // Case 3: Closed case with judgment.
        $case3 = CourtCase::create([
            'case_number' => 'CRT-2026-0003',
            'title' => 'Emily Sterling vs. Mark Sterling',
            'description' => 'Petition for dissolution of marriage and child custody settlement dispute.',
            'category' => 'Family',
            'fir_number' => null,
            'petitioner' => 'Emily Sterling',
            'respondent' => 'Mark Sterling',
            'priority' => 'medium',
            'status' => 'closed',
            'judge_id' => $judge2->id,
            'lawyer_id' => $lawyer1->id,
            'clerk_id' => $clerk->id,
            'filed_at' => Carbon::now()->subDays(30),
            'closed_at' => Carbon::now()->subDays(2),
            'judgment' => 'The marriage is dissolved. Sole legal and physical custody of the minor child is awarded to the petitioner, Emily Sterling, with reasonable unsupervised alternate weekend visitation rights to the respondent. Respondent shall pay child support in the amount of $1,200 per month starting June 2026.',
        ]);

        // Case 4: Evidence submitted, Medium priority.
        $case4 = CourtCase::create([
            'case_number' => 'CRT-2026-0004',
            'title' => 'Maria Lopez vs. City Metro Transit',
            'description' => 'Personal injury lawsuit claiming negligence and compensation for a slip-and-fall incident on the central station platform.',
            'category' => 'Civil',
            'fir_number' => null,
            'petitioner' => 'Maria Lopez',
            'respondent' => 'City Metro Transit Authority',
            'priority' => 'medium',
            'status' => 'evidence_submitted',
            'judge_id' => $judge1->id,
            'lawyer_id' => $lawyer2->id,
            'clerk_id' => $clerk->id,
            'filed_at' => Carbon::now()->subDays(15),
        ]);

        // 3. Create Hearings
        // Case 1 Hearings
        Hearing::create([
            'court_case_id' => $case1->id,
            'hearing_date' => Carbon::now()->subDays(2)->hour(10)->minute(0)->second(0),
            'courtroom' => 'Courtroom 3B',
            'judge_id' => $judge1->id,
            'status' => 'rescheduled',
            'remarks' => 'Adjourned to next date due to counsel defense request for additional discovery time.',
        ]);

        Hearing::create([
            'court_case_id' => $case1->id,
            'hearing_date' => Carbon::now()->addDays(1)->hour(10)->minute(30)->second(0),
            'courtroom' => 'Courtroom 3B',
            'judge_id' => $judge1->id,
            'status' => 'scheduled',
            'remarks' => 'Opening arguments and witness testimonies for prosecution.',
        ]);

        // Case 4 Hearings
        Hearing::create([
            'court_case_id' => $case4->id,
            'hearing_date' => Carbon::now()->addDays(3)->hour(14)->minute(0)->second(0),
            'courtroom' => 'Courtroom 1A',
            'judge_id' => $judge1->id,
            'status' => 'scheduled',
            'remarks' => 'Review of physical evidence and CCTV footage from transit station.',
        ]);

        // 4. Create Documents
        // Case 1 Documents
        Document::create([
            'court_case_id' => $case1->id,
            'user_id' => $clerk->id,
            'name' => 'FIR Primary Report.pdf',
            'file_path' => 'documents/fir_robert_dow.pdf',
            'file_size' => 124567,
            'document_type' => 'fir',
            'version' => 1,
        ]);

        Document::create([
            'court_case_id' => $case1->id,
            'user_id' => $lawyer1->id,
            'name' => 'Defense Discovery Exhibits.pdf',
            'file_path' => 'documents/chargesheet.pdf',
            'file_size' => 456789,
            'document_type' => 'evidence',
            'version' => 1,
        ]);

        // Case 3 Documents
        Document::create([
            'court_case_id' => $case3->id,
            'user_id' => $judge2->id,
            'name' => 'Final Divorce Decree & Custody Order.pdf',
            'file_path' => 'documents/decree_sterling.pdf',
            'file_size' => 234567,
            'document_type' => 'judgment',
            'version' => 1,
        ]);

        // 5. Create Activity Logs
        // Case 1 Logs
        ActivityLog::create([
            'user_id' => $clerk->id,
            'court_case_id' => $case1->id,
            'action' => 'Case Filed',
            'details' => 'New case successfully registered with auto-assigned case number CRT-2026-0001.',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(5),
        ]);

        ActivityLog::create([
            'user_id' => $clerk->id,
            'court_case_id' => $case1->id,
            'action' => 'Judge Assigned',
            'details' => 'Judge Hon. Albert Vance assigned to case.',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(4),
        ]);

        ActivityLog::create([
            'user_id' => $clerk->id,
            'court_case_id' => $case1->id,
            'action' => 'Lawyer Assigned',
            'details' => 'Adv. Sarah Jenkins assigned to represent the defense counsel.',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(4),
        ]);

        ActivityLog::create([
            'user_id' => $clerk->id,
            'court_case_id' => $case1->id,
            'action' => 'File Uploaded',
            'details' => 'Uploaded original police FIR Copy (FIR Primary Report.pdf) for evidentiary record.',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(4),
        ]);

        ActivityLog::create([
            'user_id' => $clerk->id,
            'court_case_id' => $case1->id,
            'action' => 'Hearing Scheduled',
            'details' => 'First hearing scheduled for Courtroom 3B with Judge Vance.',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(3),
        ]);

        ActivityLog::create([
            'user_id' => $judge1->id,
            'court_case_id' => $case1->id,
            'action' => 'Hearing Rescheduled',
            'details' => 'Hearing originally set for yesterday was rescheduled to tomorrow at request of counsel.',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(2),
        ]);

        ActivityLog::create([
            'user_id' => $lawyer1->id,
            'court_case_id' => $case1->id,
            'action' => 'File Uploaded',
            'details' => 'Defense attorney uploaded discovery files: Defense Discovery Exhibits.pdf.',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(1),
        ]);

        // Case 3 Logs
        ActivityLog::create([
            'user_id' => $clerk->id,
            'court_case_id' => $case3->id,
            'action' => 'Case Filed',
            'details' => 'Case registered Emily Sterling vs. Mark Sterling.',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(30),
        ]);

        ActivityLog::create([
            'user_id' => $judge2->id,
            'court_case_id' => $case3->id,
            'action' => 'Judgment Rendered',
            'details' => 'Final decree entered, child custody awarded, and case status moved to Closed.',
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subDays(2),
        ]);
    }
}
