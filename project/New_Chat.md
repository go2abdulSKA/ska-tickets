📋 NEW CHAT STARTER KIT

1. Project Context (Copy-Paste This)
   PROJECT: SKA Tickets - Multi-Department Ticketing System
   TECH STACK: Laravel 11 + Livewire 3 + Jetstream + Ubold Theme
   CURRENT TASK: Option C Implementation (DRAFT-xxx numbering for drafts)

BACKGROUND:

-   We have a finance ticket system with sequential numbering per department
-   Issue: Deleting draft tickets creates gaps in sequence (C/A-00001, C/A-00002, [deleted], C/A-00004)
-   Solution: Option C - Drafts get "DRAFT-xxx" IDs, only POSTED tickets get sequential numbers

OPTION C REQUIREMENTS:

1. Draft tickets: ticket_no = 'DRAFT-{uniqid}' (e.g., DRAFT-6789abcd)
2. Posted tickets: ticket_no = Sequential (e.g., C/A-00001, C/A-00002)
3. Drafts can be DELETED freely (no impact on sequence)
4. Posted tickets can only be CANCELLED (keeps number, audit trail)
5. Bulk delete: Max 5 drafts at once with warning
6. "My Drafts" section in ticket list
7. Draft expiry: Auto-delete after X days (configurable in settings)
8. Duplicate posted ticket: Creates new draft with confirmation
9. Preview: "Next number will be C/A-00005 when posted"

CODE STANDARDS:

-   Well-commented code with PHPDoc blocks
-   Follow existing project structure
-   Use existing services (TicketNumberService)
-   Maintain Livewire best practices
-   Use existing Enums (TicketStatus, TicketType, etc.)

2. Files You Should Attach 📎
   Priority 1 (Must Have):
1. app/Livewire/Tickets/Finance/CreateFinanceTicket.php
1. app/Livewire/Tickets/Finance/FinanceTicketList.php
1. app/Services/TicketNumberService.php
1. app/Models/TicketMaster.php
1. app/Enums/TicketStatus.php
   Priority 2 (Very Helpful):
1. resources/views/livewire/tickets/finance/create.blade.php
1. resources/views/livewire/tickets/finance/index.blade.php
1. app/Models/TicketStatusHistory.php
1. database/migrations/2025_10_04_000012_create_ticket_masters_table.php
1. database/migrations/2025_10_04_000015_create_ticket_status_history_table.php
   Priority 3 (Context Only):
1. app/Models/Department.php
1. app/Models/Client.php
1. app/Models/User.php
1. Quick Commands to Get Files from GitHub
   If files are on GitHub, you can share:
   bash# Get specific file content
   curl https://raw.githubusercontent.com/[user]/[repo]/[branch]/app/Livewire/Tickets/Finance/CreateFinanceTicket.php

# Or just share the GitHub raw URL

4. Alternative: Share Key Code Snippets
   If you don't want to share full files, share these specific methods:
   From CreateFinanceTicket.php:

saveDraft() method
saveAndPost() method
createTicket() method
Properties section (public variables)

From FinanceTicketList.php:

delete() method
confirmDelete() method
getTicketsProperty() method
Properties section

5. Database Schema (Important!)
   Share the ticket_masters table structure:
   sql-- Just the CREATE TABLE statement
   -- Or describe the columns
6. What I DON'T Need
   ❌ Full vendor files
   ❌ node_modules content
   ❌ Complete Jetstream/Livewire code
   ❌ Ubold theme files
   ❌ Migration history (just final schema)

📝 OPTIMAL NEW CHAT STARTER
Hi! I'm continuing work on SKA Tickets project.

CURRENT TASK: Implement Option C for ticket numbering

-   Drafts get DRAFT-xxx IDs
-   Posted tickets get sequential numbers (C/A-00001)
-   No gaps in sequential numbering possible

I'm attaching:

1. CreateFinanceTicket.php (current implementation)
2. FinanceTicketList.php (current implementation)
3. TicketNumberService.php
4. TicketMaster.php model
5. Project context document

REQUIREMENTS:
[paste the requirements from section 1 above]

Please provide well-commented code following our existing patterns.
Ready to start!

💡 PRO TIP: Create a Project Brief Document
Create a file called PROJECT_BRIEF.md in your repo:
markdown# SKA Tickets - Project Brief

## Overview

[Project description]

## Tech Stack

-   Laravel 11
-   Livewire 3
-   etc...

## Current State

-   ✅ Completed: [list]
-   🚧 In Progress: [list]
-   ❌ Todo: [list]

## Key Decisions

-   Ticket Numbering: Option C (DRAFT-xxx for drafts)
-   Deletion Policy: Drafts can be deleted, posted can only be cancelled
-   etc...

## File Structure

[Key files and their purposes]

## Conventions

-   Comment style
-   Naming conventions
-   Service patterns
