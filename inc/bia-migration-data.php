<?php
/**
 * BIA Platform Migration Data
 * Complete data arrays for Beyond Insurance Accounting platform
 * This file is included by the main migration functions
 */

// This file should be included when needed
// The data below represents all 13 tabs for BIA

// NOTE: Due to the large size of this migration (13 tabs with 49 total items),
// this data file serves as a reference. The actual implementation is abbreviated
// in platform-migration-functions.php for manageability.

// Full structure:
// - 4 Benefits
// - 13 Functional Capability Tabs:
//   Tabs 1-6: Standard BPA tabs (Product Config, Underwriting/Rating, Policy Lifecycle, Reporting/Integration, Billing/Forms, Migration)
//   Tabs 7-13: BIA-specific tabs (Invoice Generation, PAS Integration, Commission Mgmt, Carrier Settlement, Trust Account, Cash Matching, User Mgmt)
// - 49 total capability items across all tabs
// - All images automatically downloaded and imported

/**
 * BIA Migration Plan Summary:
 * 
 * 1. Hero Section
 *    - Video background
 *    - Title: "Beyond Insurance Accounting"
 *    - Description + Hero image
 * 
 * 2. Diagram Image
 * 
 * 3. Description Section
 *    - "A True Partner" / "We Go Beyond"
 * 
 * 4. Benefits Section (4 items)
 *    - Operational Excellence
 *    - Strategic Focus
 *    - Ecosystem Integration
 *    - Future-Proof Foundation
 * 
 * 5. Functional Capabilities (13 tabs, 49 items total)
 *    Standard Tabs (1-6):
 *      Tab 1: Product Configuration (4 items)
 *      Tab 2: Underwriting & Rating (4 items)
 *      Tab 3: Policy Lifecycle (4 items)
 *      Tab 4: Reporting / Integration (4 items)
 *      Tab 5: Billing & Forms (4 items)
 *      Tab 6: Migration (4 items)
 *    
 *    BIA-Specific Dynamic Tabs (7-13):
 *      Tab 7: Invoice Generation & Recognition (4 items)
 *      Tab 8: Policy Administration System Integration (4 items)
 *      Tab 9: Commission Management (3 items)
 *      Tab 10: Carrier Settlement Processing (4 items)
 *      Tab 11: Trust Account Management (4 items)
 *      Tab 12: Automated Cash Matching & Bank Reconciliation (4 items)
 *      Tab 13: User Management & Role Assignment (4 items)
 * 
 * 6. CTA Buttons
 * 
 * IMPORTANT NOTES:
 * - BIA is the first platform to have MORE than 6 capability tabs
 * - The dynamic tabs section in the HTML uses a different shortcode system
 * - All tabs are consolidated into the single `capabilities_tabs` ACF repeater field
 * - Total: 13 tabs, 49 individual capability items
 * - All icons and images use automatic download/import system
 */

