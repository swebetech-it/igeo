# Construction Company Reports System

## Overview
This document describes the reports system implemented for the construction company management system. The system provides various reports for project tracking, financial analysis, resource management, and executive decision making.

## Report Types

### 1. Project Reports
Provides insights into project progress, timelines, and status.

**Access Roles:** Admin, Manager, Finance

**Features:**
- Project Progress Report (planned vs actual)
- Financial Performance by Project
- Project Status Dashboard

### 2. Financial Reports
Detailed financial analysis including budget vs actual costs, cash flow, and expense breakdowns.

**Access Roles:** Admin, Manager, Finance

**Features:**
- Budget vs Actual Cost Analysis
- Cash Flow Reports
- Expense Breakdown by Category
- Billing & Invoice Tracking

### 3. Resource Reports
Tracking of manpower, equipment, and material usage.

**Access Roles:** Admin, Manager

**Features:**
- Manpower Utilization Reports
- Equipment Usage & Downtime Tracking
- Material Consumption Analysis

### 4. Executive Reports
High-level consolidated reports for strategic decision making.

**Access Roles:** Admin, Manager

**Features:**
- Company Performance Overview
- Project Profitability Analysis
- Forecasting & Risk Assessment

## Role-Based Access Control

### User Roles
1. **Administrator (admin)** - Full access to all reports
2. **Manager (manager)** - Full access to all reports
3. **Finance Officer (finance)** - Access to Project and Financial reports
4. **User (user)** - No access to reports

### Access Rules
- Admins and Managers can access all reports
- Finance Officers can access Project and Financial reports
- Regular Users cannot access any reports
- Unauthenticated users are redirected to login

## Implementation Details

### Routes
- `/admin/reports` - Main reports dashboard
- `/admin/reports/project` - Project reports
- `/admin/reports/financial` - Financial reports
- `/admin/reports/resource` - Resource reports
- `/admin/reports/executive` - Executive reports

### Components
- **Livewire Components:** ProjectReports, FinancialReports, ResourceReports, ExecutiveReports
- **Models:** Project, Expense, Income
- **Middleware:** RoleMiddleware for access control

### Database
- Added `role` column to `users` table
- Migration: `2025_09_24_000000_add_role_to_users_table.php`

## Usage Instructions

1. **Login** to the system with appropriate credentials
2. Navigate to the **Reports** section from the dashboard
3. Select the desired report type
4. Apply filters as needed (date range, project, etc.)
5. View or export the report data

## Testing

The system includes comprehensive role-based access control testing:
- Admin users can access all reports
- Manager users can access all reports
- Finance users can access Project and Financial reports only
- Regular users cannot access any reports
- Unauthenticated users are redirected to login

## Future Enhancements

1. **Export Functionality** - PDF, Excel, CSV export options
2. **Scheduled Reports** - Automated report generation and email delivery
3. **Custom Reports** - User-defined report templates
4. **Dashboard Widgets** - Real-time report widgets on main dashboard
5. **Mobile Optimization** - Responsive design for mobile devices

## Troubleshooting

If you encounter access issues:
1. Verify your user role in the database
2. Check that the `role` column is populated correctly
3. Ensure you have the appropriate permissions for your role
4. Contact system administrator for role changes