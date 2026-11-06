# PDF Download Features for Reports

This document explains the PDF download functionality that has been implemented for all report types in the DSS Engineering management system.

## Overview

PDF download functionality has been added to all four report types:
1. Project Reports
2. Financial Reports
3. Resource Reports
4. Executive Reports

Each report now includes a "Download PDF" button that generates a professional-looking PDF document containing the current report data.

## Implementation Details

### Backend Implementation

Each Livewire component now includes:
1. **PDF Generation Method**: A `downloadPdf()` method that:
   - Collects the current report data based on selected filters
   - Prepares the data for PDF rendering
   - Uses Barryvdh\DomPDF to generate the PDF
   - Returns a stream download response

2. **Required Import**: Added `use Barryvdh\DomPDF\Facade\Pdf;` to each component

### Frontend Implementation

Each report view now includes:
1. **Download Button**: A new button in the filter section with:
   - Red background color to stand out
   - PDF icon for visual identification
   - Wire click handler to trigger the download method

2. **PDF Templates**: Custom Blade templates for each report type:
   - Professional styling with company header
   - Properly formatted tables with alternating row colors
   - Report information including date range and filters
   - Confidentiality notice in footer

## Features by Report Type

### Project Reports
- **Progress Report**: Shows project names, budgets, costs, income, progress bars, and statuses
- **Financial Report**: Displays project expenses, income, received amounts, profit/loss, and transaction counts

### Financial Reports
- **Budget vs Actual**: Compares project budgets with actual costs and income
- **Cash Flow**: Shows daily cash flow with running balances
- **Expense Breakdown**: Categorizes expenses by type and project

### Resource Reports
- **Material Consumption**: Tracks material usage by project
- **Equipment Usage**: Monitors equipment utilization and costs
- **Labor Report**: Analyzes labor costs and usage

### Executive Reports
- **Company Performance**: Summary cards for key metrics and top performing projects
- **Project Profitability**: Detailed profitability analysis for all projects

## Usage Instructions

1. Navigate to any report section in the admin dashboard
2. Apply desired filters (project, date range, etc.)
3. Select the report type using the tabs
4. Click the "Download PDF" button
5. The PDF will be generated and downloaded automatically

## Technical Notes

- All PDFs include a header with the company name and report title
- Report period and filters are displayed for context
- Tables are properly formatted with borders and alternating row colors
- Currency values are formatted with 2 decimal places
- Profit/loss values are color-coded (green for profit, red for loss)
- All PDFs include a confidentiality notice in the footer

## File Structure

```
resources/views/livewire/
├── project-reports-pdf.blade.php
├── financial-reports-pdf.blade.php
├── resource-reports-pdf.blade.php
└── executive-reports-pdf.blade.php
```

Each PDF template is specifically designed to match the data structure of its corresponding report type.