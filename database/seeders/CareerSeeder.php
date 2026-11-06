<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Career;

class CareerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing careers to avoid duplicates if re-seeding
        // Career::truncate();

        $careers = [
            [
                'title' => 'Key Business Development Manager',
                'department' => 'Commercial & Institutional',
                'location' => 'Dar es Salaam, Tanzania',
                'type' => 'Full-time',
                'description' => "Role Context:\nThis role is structured around measurable commercial performance, combining revenue growth, institutional account management, and strategic market expansion within Tanzania’s pharmaceutical ecosystem.\n\nCore Responsibilities:\n• Develop and implement a comprehensive growth strategy balancing revenue expansion and customer satisfaction\n• Identify new markets, client segments, and emerging institutional opportunities\n• Establish and maintain strong relationships with hospitals, NGOs, pharmacy chains, and government entities\n• Conduct business meetings, product presentations, and proposal development tailored to client needs\n• Drive institutional tender participation and execution\n• Maintain accurate sales, revenue, and invoice records with full compliance\n• Provide post-sale support, client feedback integration, and retention strategies\n• Strengthen long-term client relationships and increase repeat business\n• Support internal capability development through training of junior sales personnel\n\nKey Performance Indicators (KPIs):\n• Monthly revenue target: TZS 204,000,000 with defined gross margin targets\n• Revenue growth: 15%–25% annually\n• Customer satisfaction score: ≥80%\n• Minimum of 8 client meetings per month with ≥40% conversion rate\n• At least 5 tailored product/service presentations monthly\n• Client retention rate: ≥90%\n• Submission of at least 3 tenders per month with 25–35% success rate\n• Identification of at least 3 new market opportunities quarterly\n\nDeliverables:\n• Growth strategy document within the first 90 days\n• Monthly market research and opportunity reports\n• Weekly and monthly sales performance reports\n• Tender tracking and pipeline management system\n• Client feedback logs and post-sale engagement records\n• Documented client interaction database and relationship tracking\n• Training and development reports for junior staff",
                'is_active' => true,
                'deadline' => '2026-04-15',
            ],
            [
                'title' => 'Key Account Manager (KAM)',
                'department' => 'Institutional Sales',
                'location' => 'Dar es Salaam, Tanzania',
                'type' => 'Full-time',
                'description' => "Role Purpose:\nThe Key Account Manager will function as a strategic revenue owner responsible for managing and expanding key institutional accounts at Godlove Pharmaceutical Tanzania Limited, including hospitals, NGOs, government entities, and corporate pharmacy chains. The role integrates commercial execution, regulatory compliance, and long-term account development.\n\nKey Responsibilities:\n• Develop and implement strategic account relationship management frameworks\n• Build and maintain executive-level relationships with procurement teams, pharmacists, and hospital administrators\n• Drive revenue growth through structured account planning, cross-selling, and contract management\n• Lead institutional tender participation and execution\n• Deliver accurate sales forecasting (3–6 months horizon) and support commercial planning processes\n• Establish client advocacy and issue resolution mechanisms to protect and grow revenue\n• Coordinate cross-functionally with supply chain, regulatory, finance, and marketing teams\n• Generate market intelligence on competitor pricing, procurement trends, and new business opportunities\n\nCore Deliverables:\n• Documented account engagement plans and stakeholder mapping\n• Forecast accuracy ≥85% with minimized stock-outs\n• Quarterly business review (QBR) reports and client satisfaction tracking ≥85%\n• Diversified and stable revenue streams across institutional segments\n• Monthly market intelligence reports and opportunity identification",
                'is_active' => true,
                'deadline' => '2026-04-15',
            ],
            [
                'title' => 'CUSTOMER CARE & TELE-SALES EXECUTIVE (PHARMA DISTRIBUTION)',
                'department' => 'Pharma Distribution',
                'location' => 'Dar es Salaam, Tanzania',
                'type' => 'Full-time',
                'description' => "Role Focus:\nOwn tele-sales conversion, inbound service quality, and order accuracy while supporting receivables discipline and regulatory compliance within a fast-paced pharmaceutical distribution model (where 30–50% of repeat orders are tele-driven).\n\nCore Deliverables:\n•	Convert outbound calls into revenue through upselling and cross-selling of priority SKUs\n•	Maintain ≥95% same-day order confirmations with ≤1% error rate\n•	Manage customer inquiries and resolve issues within defined service timelines (≤48 hours)\n•	Support credit control and reduce Days Sales Outstanding (DSO) through structured follow-ups\n•	Ensure 100% compliance with TMDA promotional and pharmacovigilance requirements\n\nCandidate Profile:\n•	Diploma/Degree in Pharmacy, Business, or related field\n•	1–3 years in tele-sales, call center, or pharma sales environments\n•	Strong communication, negotiation, and CRM/ERP proficiency\n•	High accuracy, data-driven mindset, and compliance discipline\n\nPerformance Environment:\nTarget-driven, high call-volume role with direct impact on revenue, customer retention, and cash flow optimization.",
                'is_active' => true,
                'deadline' => '2026-04-15',
            ],
            [
                'title' => 'SALES REPRESENTATIVE (PHARMACEUTICAL DISTRIBUTION)',
                'department' => 'Pharmaceutical Distribution',
                'location' => 'Dar es Salaam, Tanzania',
                'type' => 'Full-time',
                'description' => "Key Responsibilities:\n•	Achieve and exceed sales targets while maintaining margin discipline\n•	Acquire and manage pharmacy, clinic, and hospital accounts\n•	Drive customer retention and increase order value through strategic product mix.\n•	Monitor market trends, competitor activity, and customer performance\n•	Ensure compliance with pharmaceutical regulations and company policies\n\nRequirements:\n•	Bachelor’s degree in Pharmacy, Business, Marketing, or related field\n•	2–5 years’ experience in pharmaceutical sales/distribution\n•	Strong understanding of pricing, margins, and supply chain dynamics\n•	Proficiency in CRM systems and data-driven sales management\n\nWhat We Offer:\n•	Competitive base salary + performance-based commission\n•	Structured incentives tied to revenue, margin, and customer growth\n•	Opportunity to work in a performance-oriented, growth-focused environment",
                'is_active' => true,
                'deadline' => '2026-04-15',
            ],
        ];

        foreach ($careers as $career) {
            Career::updateOrCreate(['title' => $career['title']], $career);
        }
    }
}
