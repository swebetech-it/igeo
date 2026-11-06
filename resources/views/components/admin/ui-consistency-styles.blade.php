<!-- Unified Admin UI Styles for Consistent Design System -->
<style>
    /* Base Card Styles */
    .admin-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .admin-card:hover {
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
    }

    .admin-card-header {
        background: linear-gradient(135deg, #f0f9f0 0%, #dcf2df 100%);
        padding: 1.5rem;
        border-bottom: 1px solid rgba(149, 216, 158, 0.5);
    }

    .admin-card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f4d24;
        margin: 0;
    }

    /* Form Elements */
    .admin-input, .admin-select, .admin-textarea {
        background: rgba(240, 249, 240, 0.8);
        border: 2px solid #b9e5be;
        border-radius: 15px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        width: 100%;
    }

    .admin-input:focus, .admin-select:focus, .admin-textarea:focus {
        border-color: #0067ff;
        box-shadow: 0 0 0 3px rgba(0, 103, 255, 0.1);
        background: white;
        outline: none;
    }

    .admin-label {
        display: block;
        font-weight: 600;
        color: #1f4d24;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    /* Buttons */
    .admin-btn {
        border: none;
        border-radius: 15px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .admin-btn-primary {
        background: linear-gradient(135deg, #0067ff 0%, #4fc05d 100%);
        color: white;
        box-shadow: 0 8px 25px rgba(0, 103, 255, 0.4);
    }

    .admin-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(0, 103, 255, 0.5);
        color: white;
    }

    .admin-btn-secondary {
        background: linear-gradient(135deg, #dcf2df 0%, #b9e5be 100%);
        color: #1f4d24;
        box-shadow: 0 8px 25px rgba(79, 192, 93, 0.2);
    }

    .admin-btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(79, 192, 93, 0.3);
        color: #1f4d24;
    }

    .admin-btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
    }

    .admin-btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(239, 68, 68, 0.5);
        color: white;
    }

    .admin-btn-success {
        background: linear-gradient(135deg, #4fc05d 0%, #3f9a4a 100%);
        color: white;
        box-shadow: 0 8px 25px rgba(79, 192, 93, 0.4);
    }

    .admin-btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(79, 192, 93, 0.5);
        color: white;
    }

    /* Status Badges */
    .admin-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .admin-badge-pending {
        background: linear-gradient(135deg, #ff8b00 0%, #cc6f00 100%);
        color: white;
    }

    .admin-badge-approved, .admin-badge-received {
        background: linear-gradient(135deg, #4fc05d 0%, #3f9a4a 100%);
        color: white;
    }

    .admin-badge-paid {
        background: linear-gradient(135deg, #0067ff 0%, #0052cc 100%);
        color: white;
    }

    .admin-badge-rejected {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .admin-badge-overdue {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .admin-badge-completed {
        background: linear-gradient(135deg, #4fc05d 0%, #3f9a4a 100%);
        color: white;
    }

    .admin-badge-draft {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
    }

    /* Tables */
    .admin-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .admin-table thead {
        background: linear-gradient(135deg, #0067ff 0%, #0052cc 100%);
    }

    .admin-table thead th {
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
        padding: 1rem;
        border: none;
    }

    .admin-table tbody tr {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(5px);
        transition: all 0.3s ease;
    }

    .admin-table tbody tr:hover {
        background: rgba(0, 103, 255, 0.05);
        transform: scale(1.01);
    }

    .admin-table tbody td {
        padding: 1rem;
        border: none;
        border-bottom: 1px solid rgba(185, 229, 190, 0.5);
    }

    .admin-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Action Buttons */
    .admin-action-btn {
        border: none;
        border-radius: 10px;
        padding: 0.5rem 0.8rem;
        margin: 0 0.2rem;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        cursor: pointer;
    }

    .admin-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .admin-action-btn-edit {
        background: linear-gradient(135deg, #ff8b00 0%, #cc6f00 100%);
        color: white;
    }

    .admin-action-btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .admin-action-btn-view {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        color: white;
    }

    .admin-action-btn-success {
        background: linear-gradient(135deg, #4fc05d 0%, #3f9a4a 100%);
        color: white;
    }

    /* Empty States */
    .admin-empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(135deg, #f0f9f0 0%, #dcf2df 100%);
        border-radius: 20px;
        margin: 2rem 0;
    }

    .admin-empty-state-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem;
        background: linear-gradient(135deg, #0067ff 0%, #4fc05d 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        box-shadow: 0 20px 40px rgba(0, 103, 255, 0.3);
    }

    .admin-empty-state-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f4d24;
        margin-bottom: 1rem;
    }

    .admin-empty-state-description {
        font-size: 1.1rem;
        color: #64748b;
        margin-bottom: 2rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }

    /* Filters */
    .admin-filters-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
        padding: 1.5rem;
    }

    .admin-input-group {
        position: relative;
    }

    .admin-input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        z-index: 2;
    }

    .admin-input-with-icon {
        padding-left: 3rem;
    }

    /* Responsive Grid */
    .admin-grid {
        display: grid;
        gap: 1.5rem;
    }

    .admin-grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }

    .admin-grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .admin-grid-cols-3 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .admin-grid-cols-4 {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    /* Responsive Breakpoints */
    @media (max-width: 768px) {
        .admin-grid-cols-2,
        .admin-grid-cols-3,
        .admin-grid-cols-4 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .admin-card-header {
            padding: 1rem;
        }

        .admin-table {
            display: block;
            overflow-x: auto;
        }

        .admin-btn {
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 1024px) {
        .admin-grid-cols-4 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>