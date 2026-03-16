<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use Illuminate\Support\Str;

class TrainingSeeder extends Seeder
{
    public function run(): void
    {
        $trainings = [
            [
                'title' => 'Agile Overview for Executives and Managers',
                'slug' => 'agile-overview-for-executives-and-managers',
                'meta_title' => 'Agile Overview for Executives and Managers | Agile Training',
                'meta_description' => 'Understand your new roles and responsibilities in an Agile world. Learn scaling concepts and the organizational impacts of Agile migration.',
                'type' => 'training',
                'short_description' => 'Understand new roles and responsibilities in an Agile world.',
                'content' => "When engineering teams move to Agile methods, managers and executives often wonder what their new roles and responsibilities will be in this new world.\n\nSelf-organizing Agile teams still need guidance and assistance in achieving goals, however, and managers must support these teams by providing direction, assisting Scrum Masters to remove impediments, and helping program management and business needs fit into the iterative cycle.",
                'learning_objectives' => "Attendees will learn the characteristics of Agile processes, the benefits of adopting Agile development, scaling concepts, how to “go Agile” with a geographically distributed workforce, and organizational impacts of an Agile migration.",
                'audience' => 'Executives, Directors, Managers, Product/Program Managers, and other leadership figures who need to understand the organizational shifts that accompany an Agile migration.',
                'prerequisites' => 'No prerequisites.',
                'length' => 'One day.',
                'topics' => [
                    'Curriculum' => [
                        'Agile Enterprise', 'Why Agile?', 'Introduction to Scrum', 'Agile Transformation', 
                        'Requirements', 'Cross-Team & Release Planning', 'Agile + Waterfall: Hybrid Projects', 
                        'Distributed Teams', 'Organizational Impacts', 'Portfolio Management', 
                        'Budgeting', 'Agile Hardware Development', 'Kanban'
                    ]
                ],
                'sort_order' => 1,
            ],
            [
                'title' => 'Agile Software Development with Scrum',
                'slug' => 'agile-software-development-with-scrum-training',
                'meta_title' => 'Agile Software Development with Scrum Training Course',
                'meta_description' => 'Hands-on Scrum training for software development teams. Learn sprint planning, story mapping, and tracking progress in this immersive 2-day class.',
                'type' => 'training',
                'short_description' => 'Practical details of the Scrum process framework, as applied to the development of software products.',
                'content' => "The Scrum process framework is well-suited for teams that engage in product development.\n\nThis class trains attendees in the practical details of the Scrum process framework, as applied to the development of software products.",
                'learning_objectives' => "Attendees learn and experience all of the practical, hands-on skills required for a Scrum Team to plan and implement work in a Sprint.\n\nAttendees also receive an understanding of the drivers and benefits of Scrum, and its place in the context of the larger world of project management.",
                'audience' => 'Software Developers, QA personnel, Scrum Masters, Product Owners, Project Managers, Product Managers, and managers of the other attendees.',
                'prerequisites' => 'No prerequisites.',
                'length' => 'Two days.',
                'topics' => [
                    'Introduction to Scrum' => ['Classical and Agile project management', 'Building the Right Things vs. Building Things Right', 'Scrum Overview'],
                    'Requirements' => ['Progressive Elaboration', 'Stories', 'Definition of Done', 'Epics'],
                    'Estimation' => ['Planning Poker', 'Affinity Estimation', 'Units for Estimation'],
                    'Task Decomposition' => ['Task Breakdowns', 'Task Estimation'],
                    'Planning Sprints' => ['Scheduling', 'Velocity Forecasting', 'Scope Definition'],
                    'Tracking Sprint Progress' => ['Tracking Data', 'Burndown Chart'],
                    'Releases' => ['The Release Planning Horizon', 'Potentially Shippable Increments', 'Hardening Sprints'],
                    'Distributed Organizations' => ['Co-Location versus Distribution', 'Best Practices for Distributed Organizations'],
                    'Time Boxes & Meetings' => ['Sprint', 'Backlog Grooming', 'Sprint Planning', 'Daily Stand-Up', 'Review', 'Retrospective', 'Five-hour sample Scrum Project']
                ],
                'sort_order' => 2,
            ],
            [
                'title' => 'Agile Hardware Development with Scrum',
                'slug' => 'agile-hardware-development-with-scrum',
                'meta_title' => 'Agile Hardware Development with Scrum | Expert Training',
                'meta_description' => 'Master the application of Scrum to hardware development. Learn how to shorten time-to-market and manage physical product complexity with Agile methods.',
                'type' => 'training',
                'short_description' => 'Practical details of the Scrum process framework, as applied to the development of hardware products.',
                'content' => "The Scrum process framework is well-suited for teams that engage in product development.\n\nThis class trains attendees in the practical details of the Scrum process framework, as applied to the development of hardware products.",
                'learning_objectives' => "Attendees learn and experience all of the practical, hands-on skills required for a Scrum Team to plan and implement work in a Sprint.\n\nAttendees also receive an understanding of the drivers and benefits of Scrum, and its place in the context of the larger world of project management.",
                'audience' => 'Hardware and software engineers, Scrum Masters, Product Owners, Project Managers, Product Managers, and managers of the other attendees.',
                'prerequisites' => 'No prerequisites.',
                'length' => 'Two days.',
                'topics' => [
                    'Introduction to Scrum' => ['Classical and Agile project management', 'Building the Right Things vs. Building Things Right', 'Scrum Overview'],
                    'Requirements' => ['Progressive Elaboration', 'Stories', 'Definition of Done', 'Epics'],
                    'Estimation' => ['Planning Poker', 'Affinity Estimation', 'Units for Estimation'],
                    'Task Decomposition' => ['Task Breakdowns', 'Task Estimation'],
                    'Planning Sprints' => ['Scheduling', 'Velocity Forecasting', 'Scope Definition'],
                    'Tracking Sprint Progress' => ['Tracking Data', 'Burndown Chart'],
                    'Releases' => ['The Release Planning Horizon', 'Potentially Shippable Increments', 'Hardening Sprints'],
                    'Distributed Organizations' => ['Co-Location versus Distribution', 'Best Practices for Distributed Organizations'],
                    'Time Boxes & Meetings' => ['Sprint', 'Backlog Grooming', 'Sprint Planning', 'Daily Stand-Up', 'Review', 'Retrospective', 'Five-hour sample Scrum Project']
                ],
                'sort_order' => 3,
            ],
            [
                'title' => 'Agile Project Management with Kanban',
                'slug' => 'agile-project-management-with-kanban',
                'meta_title' => 'Agile Project Management with Kanban Course',
                'meta_description' => 'Learn how to implement Kanban to manage rapidly-changing priorities and optimize repeatable workflows in request-driven environments.',
                'type' => 'training',
                'short_description' => 'Ideal for groups dealing with rapidly-changing priorities and repeatable workflows.',
                'content' => "The Kanban process is ideal for groups that have one or more of these characteristics:\n\n* They must respond effectively to requests for work, and do not the control content and timing of the requests\n* They must deal with rapidly-changing priorities that may preempt previous requests\n* They can do much of their work with one or more repeatable workflows\n\nKanban is commonly used in Customer Support, IT Operations, Software Maintenance, Marketing, and other request-driven environments.",
                'learning_objectives' => "Attendees will learn the concepts behind Kanban, how and why to constrain Work-in-Process, the four varieties of Kanban tracking systems, roles and responsibilities for a Kanban process, and Kanban metrics.",
                'audience' => 'People who do the hands-on work, and people who prioritize or manage the work.',
                'prerequisites' => 'No prerequisites.',
                'length' => 'One day.',
                'topics' => [
                    'Introduction to Kanban' => ['History', 'Drivers', 'Basic Workflow Concepts', 'Practical Kanban', 'Roles and Responsibilities'],
                    'How Queues Behave in Kanban' => ['Queue Types', 'Little’s Law', 'Behavior under Load', 'Batch Size versus Throughput'],
                    'The Four Flavors of Kanban' => ['Different Types of Flow', 'Requirements Artifacts', 'Tracking Systems', 'Work-in-Process Constraints'],
                    'Tracking' => ['Basic Status Information', 'Cumulative Flow Charts', 'Kanban Metrics'],
                    'Kanban Roles and Time Boxes' => ['Roles and Responsibilities', 'Standard Meetings and Agendas'],
                    'Implementing Kanban' => ['Workflow Visualization', 'WIP Constraints', 'Measuring and Improving Performance'],
                    'Complete Kanban Exercise' => ['Two and one-half hour Kanban Project']
                ],
                'sort_order' => 4,
            ],
            [
                'title' => 'Agile Program Management',
                'slug' => 'agile-program-management-training',
                'meta_title' => 'Agile Program Management Training | Large-Scale Scrum',
                'meta_description' => 'Learn to organize large-scale Agile development processes for products requiring synchronized work across multiple Scrum Teams.',
                'type' => 'training',
                'short_description' => 'Organize a large-scale Agile development process for multiple Scrum Teams.',
                'content' => "This class focuses on how to organize a large-scale Agile development process for products that require synchronized and collaborative work by multiple Scrum Teams.\n\nGuidance for working with non-Agile teams is also included.",
                'learning_objectives' => "Attendees will learn how to form effective Scrum Teams for large organizations, define and decompose requirements for major product features, plan Release cycles for multiple Teams, manage and track work and cross-Team dependencies, and enable distributed organizations to function as effectively as possible.",
                'audience' => 'Program Managers, Product Managers, Scrum Masters, Product Owners, line managers, and executives who make staffing and resource-allocation decisions.',
                'prerequisites' => 'Attendees must have attended an Agile Software Development with Scrum, Agile Hardware Development with Scrum, or equivalent class.',
                'length' => 'One day.',
                'topics' => [
                    'Program-Level Overview' => ['Levels of Governance', 'Program-Level Ceremonies', 'Program-Management Structure', 'Program-Level Roles', 'Scaling Parameters and Values'],
                    'Team Definition' => ['Fundamentals of Team Organization', 'Feature Teams', 'Client-Server Teams', 'Component Teams', 'Tuckman Model for Team Evolution'],
                    'Requirements Development' => ['Artifacts', 'Product Backlog defined', 'Detail & Predictability vs. Time', 'Organization of a Product Backlog', 'Epics in depth', 'Techniques for Epic Decomposition'],
                    'Estimation' => ['Affinity Estimation'],
                    'Releases and Release Planning' => ['Release Schedule', 'Forecasting Velocity', 'Release Planning', 'Buffering', 'Hybrid Projects'],
                    'Tracking' => ['Burn-Up Chart', 'Scope Modification'],
                    'Ceremonies' => ['Release Planning', 'Release Backlog Grooming', 'Product Owner Scrum-of-Scrums meeting', 'Team Scrum-of-Scrums', 'Release Review', 'Release Retrospective'],
                    'Distributed Organizations' => ['Co-Location versus Distribution', 'Best Practices for Distributed Organizations']
                ],
                'sort_order' => 5,
            ],
            [
                'title' => 'Agile Portfolio Management',
                'slug' => 'agile-portfolio-management-course',
                'meta_title' => 'Agile Portfolio Management Course for Executives',
                'meta_description' => 'Practical techniques for managing business initiative portfolios. Learn budgeting, risk management, and capitalization in Agile environments.',
                'type' => 'training',
                'short_description' => 'Practical techniques for the effective management of Portfolios of business initiatives.',
                'content' => "Agile Portfolio Management presents simple and practical techniques for the effective management of Portfolios of business initiatives.\n\nThese techniques are relevant for product development in any industry, including hardware and software product development.\n\n*Note: Starred (*) topics are only available in the two-day version of this class.*",
                'learning_objectives' => "Attendees will learn best practices for developing Portfolios of business initiatives, defining selection criteria for initiatives, scheduling and resource planning for initiatives, monitoring and revising initiatives in development, and budgeting and capitalization.",
                'audience' => 'C-Level Executives, PMO Directors, Directors of Product Management, and others involved in critical decisions about where to invest an organization’s resources when developing products and services.',
                'prerequisites' => 'No prerequisites, but a basic understanding of Agile concepts will be useful.',
                'length' => 'One or two days.',
                'topics' => [
                    'Introduction to Portfolios and Governance' => ['Overview of Agile Portfolio Management', 'Flow and Ceremonies', 'Roles and Responsibilities', 'Standard Meetings and Agendas', 'Estimation', 'Scope Definition and Decomposition'],
                    'Organizational Strategy*' => ['Mission', 'Vision', 'Strategic Objectives'],
                    'Portfolio Charter*' => ['Purpose', 'Alignment', 'Objectives'],
                    'Initiatives and Business Cases' => ['Initiatives and Business Cases Defined', 'Audiences', 'Format'],
                    'Decision Factors' => ['Discrete vs. Composite Factors', 'Categories', 'Defining and Estimating ROI'],
                    'Return-Related Factors' => ['Common Factors', 'Affinity Estimation Technique', 'Net Present Value'],
                    'Investment-Related Factors' => ['Common Factors', 'Estimation Techniques', 'Effort', 'Cost'],
                    'Risk*' => ['Definition of Risk', 'Impacts', 'Categories', 'Risk Management Strategies'],
                    'Making Portfolio Decisions' => ['Decision Techniques'],
                    'Scheduling and Resource Planning' => ['Flow of Planning', 'Classic Resource Planning', 'Challenges with Classic Resource Planning', 'Agile Resource Planning', 'Scheduling Initiative Work'],
                    'Tracking and Managing Initiatives' => ['Scope Decomposition: Portfolio to Program to Project', 'Tracking Progress', 'Monitoring, Revising, and Terminating Initiatives'],
                    'Budgeting and Capitalization' => ['Classic Budgeting', 'Issues with Classic Budgeting', 'Agile Budgeting', 'Drivers for Capitalization', 'Tax Implications of Capitalization', 'What can (and can’t) be Capitalized']
                ],
                'sort_order' => 6,
            ],
            [
                'title' => 'Advanced Product Owner',
                'slug' => 'advanced-product-owner-training',
                'meta_title' => 'Advanced Product Owner Training Course',
                'meta_description' => 'In-depth Product Owner training to refine high-level concepts into fine-grained product specifications, roadmaps, and story maps.',
                'type' => 'training',
                'short_description' => 'In-depth training in the complete set of skills Product Owners require to refine a high-level product concept.',
                'content' => "The Advanced Product Owner class provides in-depth training in the complete set of skills Product Owners require to refine a high-level product concept into fine-grained and implementable product specifications.\n\n*Note: Starred (*) topics are only available in the two-day version of this class.*",
                'learning_objectives' => "Attendees will learn how to create product visions, define user workflows, and decompose major features into the fine-grained specifications required for product development.\n\nThey will also learn how to do Release Planning and develop product roadmaps.\n\nThe class exercise to develop a release plan may use a standard example or a client’s actual product.",
                'audience' => 'Product Owners, Product Managers, Scrum Masters, Project Managers, and anyone else who needs to understand how to create and manage product requirements.',
                'prerequisites' => 'Attendees must have attended an Agile Software Development with Scrum, Agile Hardware Development with Scrum, or equivalent class.',
                'length' => 'One or two days.',
                'topics' => [
                    'Product Owner Defined' => ['Product Owner Responsibilities', 'Product Team'],
                    'Product Vision' => ['Five Cycles of Planning', 'Product Vision'],
                    'User Story Lifecycle' => ['From Features to Stories', 'User Scenarios', 'Roles and Personas', 'Flows and Business Process Diagrams'],
                    'Story Mapping' => ['Story Maps defined', 'Release Planning with Story Maps'],
                    'Scaling to Large Requirements' => ['Epics'],
                    'Product Backlog' => ['Product Backlog defined', 'Detail & Predictability vs. Time', 'Organization of a Product Backlog', 'Prioritization Techniques', 'Single-Team Release Planning'],
                    'Scaling to Large Organizations' => ['Product / Team Hierarchies', 'Cross-Team Planning', 'Dependency Mapping', 'Cross-Team Development and Coordination'],
                    'Roadmap' => ['Roadmaps defined', 'How to Create Roadmaps', 'Estimation for Roadmap', 'Architecture Planning'],
                    'Release Planning' => ['Releases defined', 'Release Planning Roles', 'Release Planning Meeting', 'Flow of Release Planning', 'Buffering', 'Hardening Sprints', 'Hybrid Projects'],
                    'Advanced Topics in Story Writing*' => ['Epics in depth', 'Techniques for Epic Decomposition'],
                    'UX Design*' => ['How UX Design is done in Scrum'],
                    'Class Project*' => ['Half-Day Exercise to Draft a Release Plan', 'Focus may be client’s product or standard example']
                ],
                'sort_order' => 7,
            ],
        ];


        foreach ($trainings as $data) {
            // Only generate a slug dynamically IF one wasn't explicitly provided (fail-safe)
            $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
            $data['is_active'] = true;
            
            Service::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}