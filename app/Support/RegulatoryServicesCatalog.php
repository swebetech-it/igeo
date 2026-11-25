<?php

namespace App\Support;

use Illuminate\Support\Str;

class RegulatoryServicesCatalog
{
    public static function positioning(): array
    {
        return [
            'headline' => 'Regulatory Affairs & Pharmaceutical Compliance Services',
            'subheadline' => 'Outsourced Regulatory, Market-Entry & Quality Compliance Partner for Tanzania',
            'lead' => 'iGEO Pharmacy & Consulting positions itself as a specialist pharmaceutical regulatory affairs, market-entry and compliance consultancy supporting pharmaceutical manufacturers, international suppliers, Marketing Authorisation Holders (MAHs), importers, distributors, medical device companies, diagnostic laboratories and healthcare institutions seeking to enter or operate in the Tanzanian market.',
            'opportunity' => 'Our role is to serve as a dedicated outsourced regulatory and compliance partner—helping clients navigate the requirements of the Tanzania Medicines and Medical Devices Authority (TMDA), Pharmacy Council, Tanzania Bureau of Standards (TBS) and other relevant national institutions.',
            'focus' => 'We deliver our solutions around eight core regulatory service lines, with particular emphasis on product registration, pharmacovigilance, regulatory representation, import compliance and pharmaceutical quality management.',
            'authorities' => [
                ['code' => 'TMDA', 'name' => 'Tanzania Medicines and Medical Devices Authority'],
                ['code' => 'Pharmacy Council', 'name' => 'Pharmacy Council of Tanzania'],
                ['code' => 'TBS', 'name' => 'Tanzania Bureau of Standards'],
                ['code' => 'NatHREC', 'name' => 'National Health Research Ethics Committee'],
                ['code' => 'COSTECH', 'name' => 'Tanzania Commission for Science and Technology'],
                ['code' => 'NHIF', 'name' => 'National Health Insurance Fund'],
            ],
        ];
    }

    public static function caseStudy(): array
    {
        return [
            'badge' => 'Featured Case Study',
            'title' => 'Market-Entry & Regulatory Pathway Assessment for Neonatal & Critical-Care Medicines',
            'narrative' => 'An international manufacturer wishing to introduce caffeine citrate injection, morphine oral solution or neonatal care products into Tanzania can engage iGEO Pharmacy & Consulting to assess the regulatory pathway, existing registration status, relevant treatment guidelines, prospective institutional demand and distribution opportunities before committing to a registration investment.',
            'products' => [
                'Caffeine Citrate Injection',
                'Morphine Oral Solution',
                'Neonatal Care Products',
            ],
            'pillars' => [
                [
                    'title' => 'Regulatory Pathway & Status',
                    'detail' => 'TMDA classification, dossier pathway, controlled-substance requirements and existing competing registrations.',
                ],
                [
                    'title' => 'Clinical & Guideline Alignment',
                    'detail' => 'Alignment with Tanzania Standard Treatment Guidelines (STG), National Essential Medicines List (NEMLIT) and NHIF coverage.',
                ],
                [
                    'title' => 'Institutional Demand & Distribution',
                    'detail' => 'Assessment of public/private neonatal unit demand, qualified local importers/MAHs and compliant cold/controlled distribution.',
                ],
            ],
        ];
    }

    public static function all(): array
    {
        return [
            [
                'number' => '01',
                'slug' => 'product-registration-and-marketing-authorization',
                'name' => 'Product Registration and Marketing Authorization',
                'short_name' => 'Product Registration & MA',
                'icon' => 'verified',
                'category_badge' => 'Dossiers & TMDA Submissions',
                'authorities' => ['TMDA', 'TBS'],
                'target_clients' => 'International pharmaceutical manufacturers, local manufacturers, medical device & IVD companies, and Marketing Authorisation Holders (MAHs).',
                'description' => 'We support international manufacturers and local pharmaceutical companies seeking to register medicines, biologicals, medical devices and in-vitro diagnostics (IVDs) for commercial distribution in Tanzania.',
                'features' => [
                    'Regulatory pathway assessment for new products.',
                    'Product classification and determination of applicable registration requirements.',
                    'Preparation, compilation and technical review of pharmaceutical registration dossiers.',
                    'CTD/eCTD dossier coordination where applicable.',
                    'Medical device and in-vitro diagnostic (IVD) technical documentation.',
                    'Medical device family, system and group classification and grouping justification.',
                    'Product label, Summary of Product Characteristics (SmPC), Patient Information Leaflet (PIL) and Instructions for Use (IFU) review.',
                    'Coordination of samples, laboratory evaluations and supporting documents.',
                    'Submission coordination through TMDA\'s regulatory portal.',
                    'Management of TMDA queries and responses during assessment.',
                    'Registration renewal, retention and variation applications.',
                ],
                'highlight_note' => 'End-to-end dossier screening reduces TMDA query cycles and accelerates time-to-authorization.',
            ],
            [
                'number' => '02',
                'slug' => 'pharmacovigilance-and-drug-safety-management',
                'name' => 'Pharmacovigilance and Drug Safety Management',
                'short_name' => 'Pharmacovigilance & Drug Safety',
                'icon' => 'health_and_safety',
                'category_badge' => '24/7 Safety Call Center & QPPV Support',
                'authorities' => ['TMDA', 'Pharmacy Council'],
                'target_clients' => 'Foreign pharmaceutical manufacturers, Marketing Authorisation Holders (MAHs), clinical sponsors and healthcare distributors.',
                'description' => 'Foreign pharmaceutical manufacturers and MAHs require ongoing local pharmacovigilance arrangements in Tanzania. iGEO provides both the regulatory infrastructure and dedicated workforce to support clients during initial product registration and continuous post-marketing surveillance—backed by our 24/7 call center and designated local safety focal person.',
                'features' => [
                    'Establishment of pharmacovigilance systems for pharmaceutical companies.',
                    'Development and maintenance of Pharmacovigilance System Master Files (PSMFs).',
                    'Development of Pharmacovigilance SOPs and quality management procedures.',
                    'Appointment and support of designated local pharmacovigilance personnel, subject to applicable professional and regulatory requirements.',
                    'Adverse Drug Reaction (ADR) and Adverse Event reporting.',
                    'Individual Case Safety Report (ICSR) processing and follow-up.',
                    'Periodic Safety Update Reports (PSURs).',
                    'Periodic Benefit-Risk Evaluation Reports (PBRERs), where applicable.',
                    'Risk Management Plan (RMP) development and implementation.',
                    'Pharmacovigilance training for pharmaceutical representatives, healthcare professionals and client teams.',
                    'Signal detection, safety monitoring and literature screening.',
                    'Pharmacovigilance system audits and inspection readiness.',
                    'Product quality complaints, medication errors and safety-related recalls coordination.',
                ],
                'highlight_note' => 'Supported by iGEO\'s active 24/7 safety call center and digital Adverse Event Reporting portal for consistent follow-up and regulatory reporting.',
                'secondary_cta' => [
                    'label' => 'Open Online Adverse Event Portal',
                    'route' => 'public.adverse-event',
                ],
            ],
            [
                'number' => '03',
                'slug' => 'regulatory-representation-and-local-market-entry',
                'name' => 'Regulatory Representation and Local Market Entry Support',
                'short_name' => 'Regulatory Representation & Market Entry',
                'icon' => 'handshake',
                'category_badge' => 'Local Representation & Partnering',
                'authorities' => ['TMDA', 'Pharmacy Council'],
                'target_clients' => 'International pharmaceutical, biological and medical device manufacturers without an in-country regulatory affairs team in Tanzania.',
                'description' => 'Designed specifically for international manufacturers that do not have their own established regulatory affairs teams in Tanzania, providing structured local coordination, partner qualification and legally compliant regulatory representation.',
                'features' => [
                    'Local regulatory affairs coordination for international manufacturers.',
                    'Identification of suitable local MAHs, importers and distribution partners.',
                    'Regulatory representation under formal appointment and within legally permitted roles.',
                    'Coordination of registration documentation between foreign manufacturers and local applicants.',
                    'Establishment of regulatory collaboration agreements.',
                    'Coordination of product registration strategies and timelines.',
                    'Communication and follow-up with regulatory authorities.',
                    'Regulatory compliance monitoring for registered products.',
                    'Support for distribution agreements and regulatory responsibility matrices.',
                    'Local post-registration regulatory support.',
                ],
                'highlight_note' => 'Protects dossier integrity while bridging international manufacturers with vetted local MAHs and distributors.',
            ],
            [
                'number' => '04',
                'slug' => 'import-and-export-regulatory-compliance',
                'name' => 'Import and Export Regulatory Compliance',
                'short_name' => 'Import & Export Compliance',
                'icon' => 'local_shipping',
                'category_badge' => 'Permits, Pre-Shipment & Clearance',
                'authorities' => ['TMDA', 'TBS', 'Pharmacy Council'],
                'target_clients' => 'Pharmaceutical importers, distributors, hospitals, diagnostic laboratories, NGOs and international suppliers.',
                'description' => 'Addressing one of the most operationally critical stages in pharmaceutical supply: verifying and securing full regulatory compliance prior to shipment, port arrival and customs clearance.',
                'features' => [
                    'Import and export permit application support.',
                    'Regulatory review of proforma invoices and commercial invoices.',
                    'Verification of product registration and authorization status.',
                    'Review of Certificates of Analysis (CoA), batch information and expiry dates.',
                    'Review of product labels and storage requirements.',
                    'Import documentation compliance checks.',
                    'Support with controlled medicines and narcotic/psychotropic permit documentation.',
                    'Importation support for medical devices, IVDs and laboratory equipment.',
                    'Coordination with TMDA, importers, clearing and forwarding agents.',
                    'Management of regulatory queries arising during importation.',
                    'Support in handling detained, unpermitted or non-compliant consignments.',
                ],
                'highlight_note' => 'Pre-shipment invoice, CoA and shelf-life verification prevents costly port detentions and demurrage.',
            ],
            [
                'number' => '05',
                'slug' => 'good-distribution-and-storage-practice-consultancy',
                'name' => 'Good Distribution and Storage Practice (GSDP/GDP) Consultancy',
                'short_name' => 'GSDP / GDP Warehouse Consultancy',
                'icon' => 'warehouse',
                'category_badge' => 'Warehouse Quality & Cold Chain',
                'authorities' => ['TMDA', 'Pharmacy Council'],
                'target_clients' => 'Pharmaceutical wholesalers, importers, manufacturers, hospital pharmacies, 3PLs and cold-chain logistics providers.',
                'description' => 'Independent pharmaceutical warehouse quality and distribution compliance consultancy ensuring full adherence to Good Storage and Distribution Practices (GSDP/GDP) across ambient and cold-chain operations.',
                'features' => [
                    'GSDP/GDP compliance assessment.',
                    'Pharmaceutical warehouse quality audits.',
                    'Warehouse layout and storage-condition assessment.',
                    'Development and review of warehouse SOPs.',
                    'Temperature and humidity monitoring system assessment.',
                    'Temperature mapping coordination.',
                    'Cold-chain management procedures.',
                    'FEFO and batch traceability system improvement.',
                    'Inventory management and stock reconciliation controls.',
                    'Quarantine and segregation procedures.',
                    'Returns, recalls and expired-product management.',
                    'Deviation management and CAPA development.',
                    'Supplier and transport-provider qualification.',
                    'Staff GDP training.',
                    'Pre-inspection audit and regulatory inspection readiness.',
                ],
                'highlight_note' => 'Comprehensive readiness from warehouse layout and temperature mapping to FEFO traceability and CAPA closure.',
            ],
            [
                'number' => '06',
                'slug' => 'premises-licensing-gmp-and-quality-management',
                'name' => 'Premises Licensing, GMP and Quality Management Consultancy',
                'short_name' => 'Premises Licensing, GMP & QMS',
                'icon' => 'domain_verification',
                'category_badge' => 'Facility Licensing & QMS',
                'authorities' => ['TMDA', 'Pharmacy Council', 'TBS'],
                'target_clients' => 'Wholesale pharmacies, importing facilities, retail/institutional pharmacies and local or regional pharmaceutical manufacturers.',
                'description' => 'Helping healthcare and pharmaceutical businesses establish, license and maintain compliant premises, Good Manufacturing Practice (GMP) standards and robust Quality Management Systems (QMS).',
                'features' => [
                    'Preparation of regulatory documentation for pharmaceutical premises registration.',
                    'Application support for wholesale pharmaceutical premises and importing facilities.',
                    'Pharmacy and pharmaceutical business licensing support.',
                    'Pharmaceutical manufacturing facility licensing preparation.',
                    'Good Manufacturing Practice (GMP) gap assessments.',
                    'Quality Management System (QMS) development.',
                    'SOP development and implementation.',
                    'Internal quality audits.',
                    'Deviation, change-control and CAPA management.',
                    'Supplier qualification and quality agreements.',
                    'Preparation for TMDA and Pharmacy Council inspections.',
                    'Regulatory compliance training for pharmaceutical personnel.',
                ],
                'highlight_note' => 'Structured inspection readiness and QMS architecture aligned with Pharmacy Council and TMDA standards.',
            ],
            [
                'number' => '07',
                'slug' => 'clinical-research-and-clinical-trial-regulatory-support',
                'name' => 'Clinical Research and Clinical Trial Regulatory Support',
                'short_name' => 'Clinical Trials Regulatory Support',
                'icon' => 'biotech',
                'category_badge' => 'CTA, Ethics & Trial Compliance',
                'authorities' => ['TMDA', 'NatHREC', 'COSTECH'],
                'target_clients' => 'Pharmaceutical sponsors, Contract Research Organizations (CROs), universities, research institutes and international health organizations.',
                'description' => 'Comprehensive clinical trial regulatory pathway coordination, submission management and trial pharmacovigilance for organizations conducting clinical research in Tanzania.',
                'features' => [
                    'Clinical trial regulatory pathway assessment.',
                    'Clinical Trial Application (CTA) documentation coordination.',
                    'Preparation and technical review of clinical trial submissions.',
                    'Regulatory submission and amendment coordination.',
                    'Coordination of TMDA, National Health Research Ethics Committee (NatHREC) and COSTECH requirements, as applicable.',
                    'Clinical trial product import documentation support.',
                    'Regulatory compliance monitoring.',
                    'Safety reporting and pharmacovigilance support during trials.',
                    'Clinical research regulatory training.',
                ],
                'highlight_note' => 'Synchronized multi-agency coordination across TMDA, NatHREC and COSTECH from initial CTA to investigational product import.',
            ],
            [
                'number' => '08',
                'slug' => 'regulatory-intelligence-and-market-access-advisory',
                'name' => 'Regulatory Intelligence, Product Compliance and Market Access Advisory',
                'short_name' => 'Regulatory Intelligence & Market Access',
                'icon' => 'insights',
                'category_badge' => 'Feasibility, STG/NEMLIT & NHIF Access',
                'authorities' => ['TMDA', 'NHIF', 'TBS'],
                'target_clients' => 'Executive commercial teams, international manufacturers, investors, market-access leads and institutional suppliers.',
                'description' => 'Strategic regulatory and commercial intelligence empowering clients to evaluate the Tanzanian pharmaceutical landscape, guideline alignment and institutional demand before committing capital to registration and distribution.',
                'features' => [
                    'Tanzania pharmaceutical regulatory landscape assessments.',
                    'Competitor product registration analysis.',
                    'Identification of unregistered and potentially marketable products.',
                    'Product classification and regulatory pathway analysis.',
                    'Regulatory gap assessments for foreign manufacturers.',
                    'Market-entry feasibility studies.',
                    'Assessment of national treatment guidelines and Essential Medicines List alignment.',
                    'NHIF catalogue and institutional market-access readiness assessments.',
                    'Support for compliance with public-sector and private-sector procurement requirements.',
                    'Regulatory updates and periodic compliance newsletters.',
                    'Product lifecycle management and regulatory change monitoring.',
                ],
                'highlight_note' => 'De-risks market entry by linking regulatory pathway feasibility directly to STG/NEMLIT alignment, NHIF reimbursement and institutional procurement.',
                'includes_case_study' => true,
            ],
        ];
    }

    public static function findBySlugOrId(string $identifier): ?array
    {
        foreach (self::all() as $service) {
            if ($service['slug'] === $identifier || $service['number'] === $identifier || ltrim($service['number'], '0') === $identifier) {
                return $service;
            }
            if (Str::slug($service['name']) === $identifier) {
                return $service;
            }
        }

        // Fallback to database Service record if custom service was added in CMS
        try {
            if (is_numeric($identifier)) {
                $dbService = \App\Models\Service::where('is_active', true)->find((int) $identifier);
            } else {
                $dbService = \App\Models\Service::where('is_active', true)->get()->first(function ($item) use ($identifier) {
                    return Str::slug($item->name) === $identifier;
                });
            }

            if ($dbService) {
                return [
                    'number' => str_pad((string) $dbService->id, 2, '0', STR_PAD_LEFT),
                    'slug' => Str::slug($dbService->name),
                    'name' => $dbService->name,
                    'short_name' => $dbService->name,
                    'icon' => 'medical_services',
                    'category_badge' => 'Specialist Service',
                    'authorities' => ['TMDA', 'Pharmacy Council'],
                    'target_clients' => 'Pharmaceutical manufacturers, importers, distributors and healthcare institutions.',
                    'description' => $dbService->description,
                    'features' => is_array($dbService->features) ? $dbService->features : [],
                    'highlight_note' => 'Tailored regulatory and pharmaceutical consulting delivered by iGEO specialists.',
                ];
            }
        } catch (\Throwable $e) {
            // Database unavailable; catalog remains authoritative
        }

        return null;
    }
}
