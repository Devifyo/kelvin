<?php
/**
 * Auto-generated FAQ content for the standalone /faq page.
 * Source: "Frequently Asked Questions - v2.docx" (converted via pandoc).
 * Structure: sections -> questions -> rich-HTML answer.
 */
return [
    [
        'id'    => 'general-agile-questions',
        'title' => 'General Agile Questions',
        'questions' => [
            [
                'id' => 'what-is-agile',
                'q'  => 'What is Agile?',
                'a'  => <<<'FAQHTML'
<p>“Agile” is an umbrella term for a collection of processes and practices that optimize for rapid delivery of results in situations where high uncertainty renders more traditional planning and project-management practices ineffective.</p>
<p>Agile processes include Scrum and Kanban. Agile practices include breaking work into small and testable deliverables, fine-grained planning for the near term and coarse-grained planning for the long term, iterative development that incorporates frequent feedback from stakeholders, and the use of cross-functional teams to do the work.</p>
FAQHTML,
            ],
            [
                'id' => 'what-is-scrum',
                'q'  => 'What is Scrum?',
                'a'  => <<<'FAQHTML'
<p>Scrum is an Agile framework designed for product-development work. It arose in the context of software development, but applies to hardware development and any domain where schedule and scope uncertainty are high.</p>
<p>At its core, <strong>Scrum is a lightweight framework for managing complex product development under conditions of uncertainty.</strong> Rather than attempting to create a detailed plan at the beginning of a project and then following it rigidly, Scrum enables teams to make steady progress through short planning and execution cycles, while regularly inspecting results and adapting as new information emerges.</p>
<p>Scrum is built around a few simple ideas:</p>
<ul>
<li><p><strong>Deliver value incrementally.</strong> Instead of waiting until the end of a project to produce a finished product, Scrum encourages teams to produce small increments of the product at regular intervals.</p></li>
<li><p><strong>Work in fixed-length Sprints.</strong> Development proceeds in time-boxed iterations, typically one to four weeks long. At the end of each Sprint, the team demonstrates the deliverables completed in the Sprint.</p></li>
<li><p><strong>Prioritize continuously.</strong> A Product Owner maintains a prioritized Product Backlog, ensuring the team always works on the most valuable items next.</p></li>
<li><p><strong>Inspect and adapt.</strong> Frequent planning, review, and retrospective events allow the team to adjust both the product and its way of working as new information becomes available.</p></li>
<li><p><strong>Empower the team.</strong> Scrum relies on self-organized, cross-functional teams that decide how best to accomplish the work within each Sprint.</p></li>
</ul>
<p>The Scrum framework defines:</p>
<ul>
<li><p><strong>Three Roles:</strong> Product Owner, Scrum Master, and Team members.</p></li>
<li><p><strong>Three Artifacts:</strong> Product Backlog, Sprint Backlog, and Increment.</p></li>
<li><p><strong>Five Ceremonies:</strong> A ceremony is a recurring meeting with a standard agenda. Scrum ceremonies include Backlog Refinement, Sprint Planning, Daily Scrum (or Daily Stand-up), Sprint Review, and Sprint Retrospective.</p></li>
</ul>
<p>One of Scrum's distinguishing characteristics is what it <em>doesn't</em> prescribe. It says very little about estimation techniques, Release Planning, User Stories, Story Points, Burndown charts, or many of the practices people commonly associate with Scrum. Those practices evolved largely within the software community because they addressed common software-development problems. They are useful when they fit the domain, but they are not part of Scrum itself.</p>
<p>Understanding the distinction between the Scrum framework and software-oriented implementation practices is essential when applying Scrum to domains such as hardware development, embedded systems, and multidisciplinary engineering.</p>
FAQHTML,
            ],
            [
                'id' => 'does-scrum-really-work-outside-software-development',
                'q'  => 'Does Scrum really work outside software development?',
                'a'  => <<<'FAQHTML'
<p>Yes, it does. Scrum originated in software development, but its underlying principles apply to any product-development environment characterized by significant uncertainty and the need for frequent inspection and adaptation. I have helped organizations apply Scrum successfully to software development, hardware development, and marketing.</p>
<p>The important point is that while the Scrum framework itself remains largely unchanged, the implementation practices that surround Scrum often need to be adapted to fit the domain in which it is being applied.</p>
FAQHTML,
            ],
            [
                'id' => 'is-scrum-the-same-thing-as-agile',
                'q'  => 'Is Scrum the same thing as Agile?',
                'a'  => <<<'FAQHTML'
<p>No, it is not. Agile is an umbrella term that encompasses a variety of frameworks, methods, and practices for managing work under conditions of uncertainty. Scrum is one of the best-known Agile frameworks, but it is only one of many approaches that fall under the Agile umbrella.</p>
<p>In other words, Scrum is Agile, but Agile is much broader than Scrum.</p>
FAQHTML,
            ],
            [
                'id' => 'how-does-scrum-differ-from-kanban',
                'q'  => 'How does Scrum differ from Kanban?',
                'a'  => <<<'FAQHTML'
<p><strong>Kanban</strong> is designed for environments in which work arrives continuously and is handled as capacity becomes available. It originated in manufacturing and is widely used today by support organizations, IT departments, and software teams with a continuous stream of incoming work. Kanban works best when the incoming work items are broadly similar in size and characteristics, making it possible to manage them as a continuous flow of work.</p>
<p><strong>Scrum</strong>, by contrast, is a framework for product development. It organizes work into fixed-length Sprints and provides defined roles, artifacts, and ceremonies to support planning, execution, inspection, and adaptation. Because Scrum is intended for product development, the work undertaken by a Scrum Team often varies considerably in scope, complexity, and duration.</p>
<p>Kanban intentionally leaves planning, estimation, and roles largely unspecified. As a result, it works well for managing a continuous flow of work, but it provides little inherent support for coordinating multiple product-development teams or managing cross-team dependencies. Those needs are typically addressed through Scrum together with longer-term planning practices such as Release Planning.</p>
<p>Organizations often use Scrum to develop products and Kanban to manage ongoing operational or support work, selecting the framework that best matches the nature of the work being performed.</p>
FAQHTML,
            ],
            [
                'id' => 'what-are-the-biggest-reasons-agile-transformations-fail',
                'q'  => 'What are the biggest reasons Agile transformations fail?',
                'a'  => <<<'FAQHTML'
<p><strong>Successful Agile transformations require much more than introducing Scrum or other Agile practices.</strong> Common reasons transformations fail include:</p>
<ol type="1">
<li><p><strong>Organizational resistance to change.</strong> Agile often requires changes in management practices, organizational structure, and ways of working that people may resist.</p></li>
<li><p><strong>Insufficient executive sponsorship.</strong> Agile transformations require active leadership from senior management. Without visible executive support and participation, organizational barriers often remain unresolved.</p></li>
<li><p><strong>The business is not engaged.</strong> Agile is fundamentally a business initiative, not simply a product-development methodology. Transformations frequently stall when business leaders do not participate in product direction and prioritization.</p></li>
<li><p><strong>The transformation is driven solely by the technology organization.</strong> When Agile is treated as a technology initiative rather than an enterprise-wide business initiative, its benefits are often limited.</p></li>
<li><p><strong>Insufficient education and coaching.</strong> Teams, managers, Product Owners, Scrum Masters, and executives all require training to understand their new roles and responsibilities.</p></li>
<li><p><strong>Applying Agile practices without understanding why they exist.</strong> For example, organizations might adopt Story Points, User Stories, or other popular Agile practices simply because they are considered “best practices,” without first asking what problem those practices were intended to solve—or whether they fit their engineering environment.</p></li>
</ol>
FAQHTML,
            ],
            [
                'id' => 'how-do-you-know-whether-agile-is-working',
                'q'  => 'How do you know whether Agile is working?',
                'a'  => <<<'FAQHTML'
<p>We measure success by the outcomes of the process. An Agile process is working when it produces better engineering and business outcomes than the organization experienced prior to the Agile transformation.</p>
<p>At the organizational level, the success of an Agile transformation is measured by improved business and engineering outcomes. We care about how well system integration proceeds, the frequency of expensive design changes at late stages, whether cross-team coordination is working well (i.e., there are few to no broken dependencies), the reliability of tracking metrics for time horizons longer than a Sprint (e.g., the Burn-up chart), and the extent to which risks are identified and mitigated early so that late-stage surprises are avoided. Organizations that improve these underlying capabilities generally achieve more predictable delivery schedules and shorter time-to-market.</p>
<p>At the team level, Scrum provides useful indicators of day-to-day execution. Burndown charts, Sprint Retrospectives, and feedback from Team members provide valuable insight into whether the team is becoming more effective over time.</p>
FAQHTML,
            ],
            [
                'id' => 'can-agile-work-in-regulated-industries',
                'q'  => 'Can Agile work in regulated industries?',
                'a'  => <<<'FAQHTML'
<p>Yes, Agile works in regulated industries and has been adopted successfully in many such environments. Examples include FDA regulation for medical products, and ISO 9000 quality-management systems. Agile works without difficulty as long as the regulatory framework specifies what must be accomplished (i.e., what outputs the process must produce), rather than how they are accomplished (i.e., specific processes that would conflict with the Agile framework).</p>
<p>The reason is that regulatory frameworks typically specify what evidence, documentation, validation, and traceability must be produced—not the specific development process used to produce them.</p>
<p>In my experience, I have not encountered a regulatory framework whose requirements were inherently incompatible with Agile. The challenge is usually designing an Agile process that satisfies the regulatory requirements, not choosing between Agile and compliance.</p>
FAQHTML,
            ],
            [
                'id' => 'does-agile-replace-project-management',
                'q'  => 'Does Agile replace project management?',
                'a'  => <<<'FAQHTML'
<p>No. Agile is not a replacement for project management. It is a different approach to project management that is appropriate for different kinds of work.</p>
<p>I find it useful to distinguish three categories of work, each of which is best managed using a different approach to project management.</p>
<ul>
<li><p><strong>Predictive</strong>: The work is predictable.</p></li>
<li><p><strong>Adaptive</strong>: The work is characterized by high uncertainty around scope and schedule, but it is possible to create rough plans around both.</p></li>
<li><p><strong>Reactive</strong>: The work has such high uncertainty that meaningful predictions about scope and schedule cannot be made.</p></li>
</ul>
<p>The predictive world is the one most commonly associated with what I call “classic project management.” In this world, the project team creates a project schedule by identifying a set of tasks, estimating them, and sequencing them based on dependencies and resources. Schedule artifacts look like Microsoft Project schedules and Gantt charts, and work is tracked against these artifacts.</p>
<p>Classic project planning fails in the adaptive world, because the high uncertainty destroys classic project schedules rapidly and frequently. Scrum provides a much more effective planning framework. Planning and tracking in Scrum involve defining and estimating deliverables, dividing time into (say, two-week) Sprints which have a forecasted Velocity ceiling, and putting as many deliverables into each Sprint as will fit within the Velocity ceiling. The practice of planning each Sprint at the beginning enables the Scrum team to adapt swiftly to unexpected developments. Progress in the Sprint is measured by Burndown charts, and progress over longer Release cycles can be measured by Burn-up charts.</p>
<p>The reactive world commonly uses the Kanban framework, which does not require estimation or time-boxed planning. Instead, work items are pulled into the first workflow state from a backlog queue when the system has the capacity to start work on a new one. The item transitions between a defined set of workflow states as work is done on it, and transitions out of the workflow on completion. Work is not tracked against a schedule, but Cumulative Flow Diagrams can be used to understand the overall flow and identify bottlenecks.</p>
<p>All three frameworks are forms of project management. They differ not in whether work is managed, but in how the work is planned, tracked, and adapted over time.</p>
<p>The title of “project manager” is typically not used in Scrum, because many of the responsibilities traditionally associated with a project manager are performed by the Scrum Master, together with the Product Owner and Team members.</p>
<p>Kanban likewise does not define a project-manager role or any other formal roles. Organizations assign those responsibilities according to their own needs.</p>
FAQHTML,
            ],
            [
                'id' => 'what-is-progressive-elaboration',
                'q'  => 'What is Progressive Elaboration?',
                'a'  => <<<'FAQHTML'
<p>Every Agile team creates deliverables. Early in the development process, the team's understanding of those deliverables is often incomplete. By the time implementation begins, however, that understanding should be much deeper. The process by which the Scrum Team develops an increasingly complete understanding of what a deliverable is and how it will be created is called <em>progressive elaboration</em>.</p>
<p>Progressive elaboration happens whether or not any specific efforts are focused on making it happen, and may be done well or poorly. In the context of Scrum, though, there are practices that do focus on promoting progressive elaboration in very visible ways.</p>
<ol type="1">
<li><p><strong>Initial definition.</strong> The process starts with the first draft of a Story, which is a written artifact that defines a requirement or a specification for a deliverable.</p></li>
<li><p><strong>Refinement.</strong> In the Backlog Refinement meeting, the Scrum Team reviews and refines each Story to be planned into the next few Sprints, with the focus on refining both the draft language and the Scrum Team’s understanding of the deliverable.</p></li>
<li><p><strong>Estimation.</strong> In the first part of the Sprint Planning meeting, the Planning Poker estimation technique provides additional understanding of the deliverable as the Team members discuss the work required to create the deliverable, in the process of generating their work estimates.</p></li>
<li><p><strong>Task decomposition.</strong> In the second part of the Sprint Planning meeting, the Team members decompose the work of each estimated Story into a set of estimated tasks that they will carry out in order to create and verify the deliverable. The shift from “what is the deliverable” to “how do we make the deliverable” promotes new insights and additional progressive elaboration.</p></li>
</ol>
<p>Progressive elaboration in Scrum is not simply an accident or merely a byproduct of good engineering discussions. It is built into Scrum from the beginning.</p>
<p>The concept of progressive elaboration is equally applicable to software and hardware development. What differs between the two domains is the nature of the deliverables being elaborated, not the need for progressive elaboration itself.</p>
FAQHTML,
            ],
        ],
    ],
    [
        'id'    => 'agile-hardware-and-software-development-questions',
        'title' => 'Agile Hardware and Software Development Questions',
        'questions' => [
            [
                'id' => 'can-scrum-really-work-for-hardware-development',
                'q'  => 'Can Scrum really work for hardware development?',
                'a'  => <<<'FAQHTML'
<p>Yes. I have conducted Agile hardware transformations and introduced Scrum to multiple hardware-development organizations. Scrum worked smoothly in each case because the Scrum framework itself translated well to hardware development. The adaptations that were required were not in Scrum itself, but in many of the software-oriented implementation practices that are commonly associated with Scrum.</p>
FAQHTML,
            ],
            [
                'id' => 'why-do-agile-methods-developed-for-software-often-fail-in-hardware-organizations',
                'q'  => 'Why do Agile methods developed for software often fail in hardware organizations?',
                'a'  => <<<'FAQHTML'
<p>They usually fail because organizations attempt to apply software-oriented implementation practices without considering whether those practices fit the realities of hardware development. Scrum itself generally fits hardware development remarkably well. It is many of the practices that grew up around Scrum in the software world that require adaptation.</p>
FAQHTML,
            ],
            [
                'id' => 'what-changes-when-scrum-is-applied-to-hardware-development',
                'q'  => 'What changes when Scrum is applied to hardware development?',
                'a'  => <<<'FAQHTML'
<p>The Scrum framework itself does not change. The changes occur primarily in the software-oriented implementation practices that have become associated with Scrum. Hardware development introduces engineering realities—such as specialized skills, long lead times, prototype builds, supplier dependencies, and physical integration—that require different implementation practices. The following questions discuss those adaptations in more detail.</p>
FAQHTML,
            ],
            [
                'id' => 'do-hardware-teams-need-user-stories',
                'q'  => 'Do hardware teams need User Stories?',
                'a'  => <<<'FAQHTML'
<p>For the most part, they do not. While User Stories describe user-facing functionality, many hardware deliverables are purely technical and are more naturally described as <em>Technical Stories</em>.</p>
<p>A User Story defines a requirement for a user experience to be implemented in the product, together with acceptance criteria that define when the deliverable is complete. Equivalently, we could say that it is a specification for a user-facing deliverable to be implemented in the product.</p>
<p>In my work, I differ from common practice in that I define a second category of Story that specifies a technical deliverable rather than a user experience. A Technical Story closely resembles a User Story, but lacks a user role and simply describes the deliverable to be produced.</p>
<p>Where software developers think of the parts of a software product in terms of the various behaviors the product contains, hardware engineers think of the parts of a hardware product as the physical components that make it up, along with various design artifacts associated with the physical product.</p>
<p>This means that the natural decomposition of hardware-engineering deliverables is expressed as Technical Stories, and there may be few to no User Stories in that decomposition. For that reason, Technical Stories provide a more natural way to plan and track much of the work performed by hardware-development teams.</p>
<p>Across the Agile hardware transformations I have led, the natural decomposition of the work has been entirely into Technical Stories rather than User Stories.</p>
FAQHTML,
            ],
            [
                'id' => 'what-does-the-definition-of-done-mean',
                'q'  => 'What does the “Definition of Done” mean?',
                'a'  => <<<'FAQHTML'
<p>The Definition of Done is the set of criteria that work for a particular Product Backlog Item must satisfy before the Scrum Team considers the work complete. These criteria ensure that completed work meets agreed-upon standards of quality, verification, and documentation before Team members move on to other work.</p>
<p>The Definition of Done varies from organization to organization because it reflects local engineering practices, quality standards, regulatory requirements, and organizational policies. It may include general criteria that apply to every Story together with additional criteria that apply only to particular classes of engineering deliverables.</p>
<p>The examples below are illustrative rather than exhaustive. Every Scrum Team should develop its own Definition of Done to reflect its products, engineering disciplines, quality system, and regulatory environment. When there are multiple Scrum Teams working within an organization, it is frequently the case that their Definitions of Done will share some items, and contain other items that are specific to particular Scrum Teams.</p>
<p>A representative Definition of Done for a software team might look like this:</p>
<p><strong>Quality Assurance</strong></p>
<ul>
<li><p>All defects found when testing the deliverable must be fixed immediately.</p></li>
<li><p>Acceptance tests must be satisfied.</p>
<ul>
<li><p>Testing is performed in a QA environment.</p></li>
</ul></li>
<li><p>All unit tests must pass.</p></li>
<li><p>All regression tests must pass.</p></li>
</ul>
<p><strong>Organizational Standards</strong></p>
<ul>
<li><p>Code is checked in to repository.</p></li>
<li><p>The application builds successfully from the repository.</p></li>
<li><p>The deployment process completes successfully without errors.</p></li>
<li><p>Code has been reviewed by a peer or the team lead.</p></li>
<li><p>The database design has been reviewed by the Database architect.</p></li>
<li><p>Unit tests have been developed.</p></li>
<li><p>Acceptance tests are automated.</p></li>
<li><p>Continuous-integration server builds app and runs tests.</p></li>
</ul>
<p>A representative Definition of Done for a hardware team might look like this:</p>
<p><strong>Quality Control</strong></p>
<ul>
<li><p>Defects found when testing the deliverable of a Story must be addressed immediately.</p>
<ul>
<li><p>Software defects must be fixed immediately.</p></li>
<li><p>Defects in diagrams must be fixed immediately.</p></li>
<li><p>CAD models cannot be checked into the database with errors.</p></li>
<li><p>Defects in printed circuit boards and hardware assemblies must be fixed to the extent possible. If immediate correction is not practical, the corrective work should be planned explicitly (for example, by writing a Story describing the required corrective action).</p></li>
</ul></li>
<li><p>Calibrate test equipment prior to performing tests.</p></li>
<li><p>Confirm that schematic diagrams satisfy the standard validation checklist.</p></li>
<li><p>Design documents and software must be peer reviewed.</p></li>
<li><p>Confirm software builds successfully after check-ins.</p></li>
</ul>
<p><strong>Organizational Standards</strong></p>
<ul>
<li><p>Design files must be put into the document control system.</p></li>
<li><p>Required design reviews completed.</p></li>
<li><p>Required simulations completed successfully.</p></li>
<li><p>Required calculations documented.</p></li>
<li><p>Test results recorded.</p></li>
<li><p>Traceability updated.</p></li>
<li><p>Configuration management records updated.</p></li>
</ul>
<p>The Definition of Done serves two important purposes within Scrum. First, it establishes a shared understanding of what "complete" means across the Scrum Team. Second, it ensures that work is not reported as complete until it meets the organization's agreed quality standards. This reduces ambiguity during Sprint Reviews and helps prevent partially completed work from accumulating across Sprints.</p>
FAQHTML,
            ],
            [
                'id' => 'what-techniques-should-hardware-teams-use-to-estimate-work-for-a-sprint',
                'q'  => 'What techniques should hardware teams use to estimate work for a Sprint?',
                'a'  => <<<'FAQHTML'
<p>The technique most commonly used to estimate Stories during Sprint Planning is called <em>Planning Poker</em>. While not an official Scrum practice, this technique is very common and works equally well for both hardware and software development.</p>
<p>Planning Poker is a technique for rapid estimation by a set of Team members that avoids the common problem of <em>anchoring</em>, which is the tendency of a group of estimators to simply replicate the answer of the most senior person in the group. Senior people are valuable, but by no means is it reasonable to expect that they will consider all relevant factors in coming up with work estimates. The goal is to capture each Team member’s thinking about what the estimate should be. Thus, the strength of Planning Poker is that it combines the knowledge of the entire Scrum Team while preventing any one individual from dominating the estimation process.</p>
<p>In Planning Poker, each Team member is given a set of numbered cards, typically with the sequence 0, ½, 1, 2, 3, 5, 8, 13, … , which contains numbers drawn from the Fibonacci sequence. The numbers in that sequence are spaced closely enough to provide a useful estimation scale, but the spacing also increases to reflect that larger estimates have greater uncertainty.</p>
<p>Typically, the Product Owner reads the Story aloud. All members of the Scrum Team (including Product Owner and Scrum Master) have a brief discussion about details that are unclear, and then each Team member independently selects an estimate.</p>
<p>The Scrum Master instructs the Team members to each pick a card from the deck, whose number represents the estimate, and lay it face down on the table. When all have done so, the Scrum Master tells them to turn their cards up. The result is a range of estimates that truly represents the perspective of each Team member.</p>
<p>The estimation exercise is followed by a “High-Low” discussion, where the Scrum Master asks the Team members with the lowest estimate to explain their logic, and then asks the Team members with the highest estimate to explain their logic. After a brief discussion to allow everyone to consider the new information, the Team conducts a second round of estimation.</p>
<p>No more than three rounds should be needed. If convergence to a single number hasn’t happened within three rounds, it is unlikely to happen at all, in which case the Scrum Master simply asks the team to select a consensus estimate through a brief informal discussion.</p>
<p>It is important to note that Planning Poker accomplishes more than generating estimates alone. Planning Poker is an excellent tool for producing <em>progressive elaboration</em>, meaning the Scrum Team develops a deeper shared understanding of what the deliverables are and how they will be created. In practice, this progressive elaboration is often as valuable as the estimates themselves.</p>
FAQHTML,
            ],
            [
                'id' => 'what-techniques-should-hardware-teams-use-to-estimate-work-for-a-release-cycle',
                'q'  => 'What techniques should hardware teams use to estimate work for a Release cycle?',
                'a'  => <<<'FAQHTML'
<p>The technique most commonly used to estimate work during Release Planning is called <em>Affinity Estimation</em>. While not an official Scrum practice, it is widely used because it provides estimates much more quickly than Planning Poker. Release Planning often involves multiple Teams working in parallel to estimate work, identify cross-Team dependencies, and construct a coordinated plan.</p>
<p>Because a Release plan typically contains many more Stories than a Sprint plan, using Planning Poker for every Story becomes time-consuming. Affinity Estimation provides a much faster way to estimate a large backlog.</p>
<p>The tradeoff in using the Affinity method is that each Story gets less scrutiny and discussion, but this is usually a worthwhile tradeoff given the time constraints around the planning exercise itself. The longer-term plan for a Release cycle will never be as reliable as the plan for a shorter Sprint anyway, so pursuit of higher accuracy through greater effort spent in estimation usually provides little additional benefit.</p>
<p>Because Affinity Estimation is fast, it leaves more time during Release Planning for the discussions that matter most: identifying dependencies, resolving risks, and constructing a realistic cross-team plan.</p>
<p>Stories are not the only requirements or specification artifacts used in Release Planning. Epics also appear. An Epic is simply a specification for a deliverable which is too large for a Team to complete in a Sprint, written in the same format as a Story. At some point during the Release cycle, the Team members will decompose Epics into Stories to be planned into Sprints. The set of written Stories and Epics should suffice to represent all work to be done in the Release cycle.</p>
<p>In the Affinity method, the Stories and Epics are traditionally printed out on cards or sheets of paper, although electronic tools can also be used. In part one of the method, the Team members sort Stories and Epics by increasing size from left to right on a table, wall, or display. Stories and Epics that are approximately the same size as items already sorted are simply placed in the same column.</p>
<p>In part two, after the sorting is complete, Team members discuss appropriate estimates for the columns and label the columns with estimates, typically using numbers from the Fibonacci sequence. The estimation process is complete when part two is complete.</p>
<p>Planning Poker is best suited to the relatively small number of Stories entering a Sprint, while Affinity Estimation is better suited to the much larger number of Stories and Epics considered during Release Planning.</p>
<p>It is important to note that Affinity Estimation accomplishes more than generating estimates alone. Affinity estimation provides an opportunity for producing <em>progressive elaboration</em>, meaning the participating Teams develop a deeper shared understanding of what the deliverables are and how they will be created. In practice, this progressive elaboration is often as valuable as the estimates themselves.</p>
FAQHTML,
            ],
            [
                'id' => 'what-units-should-hardware-teams-use-in-estimating-work',
                'q'  => 'What units should hardware teams use in estimating work?',
                'a'  => <<<'FAQHTML'
<p>Most Scrum literature recommends estimating work using Story Points. Story Points are intentionally abstract. They are not tied directly to time, effort, or any other measurable quantity. Instead, they represent a team's relative assessment of the size or complexity of a piece of work.</p>
<p>The process begins by establishing a reference scale. Team members compare candidate Stories, arrange them from smaller to larger, and assign values such as 0, ½, 1, 2, 3, 5, 8, and 13.</p>
<p>This sequence includes numbers from the Fibonacci sequence, in which each number is the sum of the previous two. The sequence has the useful property that the spacing between adjacent values grows as the numbers become larger. This reflects the reality that uncertainty increases as work becomes larger and more complex.</p>
<p>The result is a scale that is fine enough to support planning, but coarse enough to avoid time-wasting discussions about whether a Story should be estimated as a 10 or an 11. As uncertainty grows, the scale intentionally becomes less precise.</p>
<p>Future Stories are then estimated by comparing them to previously sized work.</p>
<p>This approach works for many software teams. The reason it works, however, is rarely discussed.</p>
<p>For a team to create and use a shared reference scale, Team members must possess enough overlapping knowledge to develop a shared intuition about the relative size of the work being estimated.</p>
<p>Software teams often satisfy this condition. The members of a Scrum team may be cross-functional, but they frequently function as what Agile practitioners call <em>generalizing specialists</em>. They possess a primary area of expertise while still understanding the work performed by their teammates well enough to participate meaningfully in estimation discussions.</p>
<p>Hardware teams are also cross-functional, but the Team members are typically not generalizing specialists. Instead, they have specialties such as electrical engineering, mechanical engineering, firmware, optics, antenna design, and so forth, and generally do not understand each other’s area of expertise well enough to participate meaningfully in estimation discussions that focus around particular specialties.</p>
<p>For example, a mechanical engineer could not reliably estimate an electronics task, and an electrical engineer could not reliably estimate an optical task.</p>
<p>The higher degree of specialization in hardware teams prevents the formation of a shared reference scale required to define and use Story Points as units for estimation.</p>
<p>What the different Team members do share is a common understanding of effort, which can be expressed in units of <em>Person-Days</em>, where one Person-Day means eight hours of engineering time spent working on a deliverable. Note that a deliverable estimated at one Person-Day will typically require more than one calendar day to complete because Team members must also spend time in meetings, reviews, and other activities that are part of normal engineering work.</p>
<p>The choice between Story Points and Person-Days is not a question of Agile maturity or Scrum compliance. It is a question of selecting estimation units that fit the characteristics of the Team performing the work. Hardware teams generally find Person-Days more useful than Story Points as units of estimation.</p>
<p>Note that Planning Poker and Affinity Estimation can be used without modification for either system of units.</p>
FAQHTML,
            ],
            [
                'id' => 'how-does-velocity-forecasting-work',
                'q'  => 'How does Velocity forecasting work?',
                'a'  => <<<'FAQHTML'
<p>Velocity is the amount of work a Team accomplishes in a Sprint. The Velocity of a past Sprint is computed by summing the estimates of the work completed in that Sprint. The Scrum Master must forecast the Velocity of the next Sprint to be planned, because the value is used as a ceiling for how much work to plan into the Sprint.</p>
<p>If the units of work estimation are Story Points, then two methods are available. One common heuristic is "yesterday's weather," which uses the previous Sprint's Velocity, and the other is the "last three days' weather," which uses the average Velocity of the previous three Sprints.</p>
<p>If the units of work estimation are Person-Days, then two other methods are available. While the above methods could be used, in practice this seldom occurs, because the following methods account more directly for the availability of individual Team members. The <em>resource model</em> approach in particular handles differences in the availability of each Team member’s working time in a way that no other approach does.</p>
<p>The focus-factor method is useful when a quick approximation is needed, while the resource model is preferred when a more accurate forecast is required. In particular, the focus-factor method is commonly employed in Release Planning to forecast the Velocities of the Sprints in the Release cycle.</p>
<p>For a two-week Sprint containing ten working days, the focus-factor method estimates Velocity using the formula</p>
<p>Velocity = (focus factor) × (# Team members) × 10.</p>
<p>This is useful for a quick estimate, in which case I use a focus factor of 0.6.</p>
<p>The resource-model method estimates each Team member’s individual available working time in the Sprint, taking into account Scrum meetings and other activities that take away from hands-on engineering time, and sums the numbers across all Team members. The resource-model method generally provides a more accurate forecast because it reflects the actual availability of individual Team members during the Sprint.</p>
<p>Because Person-Day estimates are tied directly to engineering capacity, Velocity can be forecast from the Team's available engineering time rather than relying solely on historical performance.</p>
FAQHTML,
            ],
            [
                'id' => 'how-long-should-a-sprint-be-for-hardware-development',
                'q'  => 'How long should a Sprint be for hardware development?',
                'a'  => <<<'FAQHTML'
<p>The general guidance for Scrum is that Sprints should be from one to four weeks long. In practice, I have almost always seen two-week Sprints and recommend them for both hardware and software development. Two weeks is short enough that problems become visible quickly and corrective action can be taken before significant schedule slippage accumulates, yet long enough that Scrum ceremonies do not consume an excessive fraction of the Team's engineering time.</p>
<p>The Sprint length should be chosen to establish a sustainable planning, execution, and review cadence, not to match hardware build cycles. Long-lead items and prototype builds are managed through Release Planning rather than by extending Sprint length.</p>
<p>A common misconception is that two-week Sprints are too short because the Scrum Team is expected to deliver new user-visible functionality at the end of every Sprint. This misconception comes largely from software practices, where that goal is often pursued, although even there it is not always achievable. What matters is not that every Sprint produces a new user-visible capability, but that planned work can be decomposed into small engineering deliverables that can each be completed and verified within a few days. This is entirely achievable in hardware development.</p>
<p>Extending Sprint length to accommodate long hardware activities generally delays feedback without eliminating the underlying scheduling problem.</p>
FAQHTML,
            ],
            [
                'id' => 'how-does-sprint-planning-work',
                'q'  => 'How does Sprint Planning work?',
                'a'  => <<<'FAQHTML'
<p>Sprint Planning produces a plan for the next Sprint, and is typically done the morning of the first day, or a day or two before the first day of the Sprint.</p>
<p>The prerequisites for Sprint Planning are that</p>
<ul>
<li><p>The whole Scrum Team has reviewed and refined the Stories to be addressed in Sprint Planning in a preceding Backlog Refinement meeting to ensure they are both well-written and well-understood.</p></li>
<li><p>The Product Owner has ranked (sequenced) a set of Product Backlog Items (PBIs, meaning Stories and Defect reports).</p></li>
<li><p>The Scrum Master has forecast the Team’s Velocity for the Sprint.</p></li>
</ul>
<p>The details of Sprint Planning vary across practitioners, but the following two-part model is widely used and is the one I prefer. The details depend on whether the product is a software product or a hardware product. Part one estimates PBIs that do not yet have estimates, while part two generates a task breakdown for each PBI.</p>
<p>The details of Sprint Planning depend primarily on the degree of skill specialization within the Scrum Team. Typical software teams and highly specialized hardware teams often plan differently.</p>
<p>In part one for software products, the Product Owner reads each PBI in rank order. The Scrum Master facilitates a Planning Poker estimation session for each, records the estimate, and moves each into the Sprint Backlog (Sprint scope) until the latter is filled to the Velocity ceiling. A certain amount of adjustment of the Sprint Backlog to properly fill it to the Velocity ceiling is common, as some PBIs may be swapped in or out for that purpose.</p>
<p>In part one for hardware products, or even for software products where skill specialization is high and cross-functional understanding across specializations is low, the simple model above does not work, because it does not account for the specialization of skills. Team members cannot substitute for each other or work outside their areas of specialization. This means that the simple model above is no longer adequate, and can result in over- or under-allocating individual Team member’s available capacity for doing work in the Sprint.</p>
<p>Instead, the Scrum Master computes each Team member's available engineering time for the Sprint as part of the resource-model Velocity forecast. Unlike the simpler software model, highly specialized hardware teams benefit from allocating PBIs to individual Team members during Sprint Planning to ensure that each Team member’s specialized capacity is used appropriately.</p>
<p>Part two is the same for both types of product development. The Team members decompose the work of each PBI into tasks, and estimate each task in hours. This may be done in a group meeting, or by Team members doing the decomposition for their own PBIs. If the latter, the Scrum Team reconvenes, examines the task numbers, and makes any final adjustments to the Sprint backlog required by the task estimates.</p>
<p>Finally, note that PBI estimation and task decomposition do not simply produce the Sprint plan. Together, they also provide the final stages of progressive elaboration before implementation begins.</p>
FAQHTML,
            ],
            [
                'id' => 'how-is-progress-tracked-in-a-sprint',
                'q'  => 'How is Progress Tracked in a Sprint?',
                'a'  => <<<'FAQHTML'
<p>All of the tasks planned into the Sprint are known, as are their effort estimates in hours. The total planned effort for the Sprint is simply the sum of the task estimates. We can track progress in the Sprint by using a Burndown chart to show estimated effort remaining versus planned effort remaining at the end of each day.</p>
<p>For a two week (say) Sprint, with ten working days, the Burndown chart will contain eleven columns: one for Day 0 (at the end of the Sprint planning meeting), and one each for the ends of Days 1—10. If we draw a line connecting the total effort remaining as of Day 0 to zero at the end of Day 10, we get a downward-sloping diagonal line (the <em>ideal progress line</em>) which shows the planned work remaining at the end of each working day in the Sprint. Drawing a bar at each day whose height is the sum of all of the estimates for tasks not completed by the end of the day allows us to compare the actual progress against the plan. (Whether the actual work remaining is drawn as a bar chart or a line chart, it can be compared directly with the ideal progress line.)</p>
<p>If the bar is below the ideal progress line, work is ahead of schedule. If it is above the line, work is behind schedule. If work is behind schedule for multiple days running by mid-Sprint, it is unlikely that the Team will catch up and get down to zero for Day 10. It is easy to extrapolate the likely state for Day 10 simply by holding a straight edge up to the chart and drawing a best-fit line to Day 10.</p>
<p>If the extrapolation shows that the Team will not be able to finish the planned scope by the end of the Sprint, the whole Scrum Team should meet as soon as this is clear, and discuss with the Product Owner which Product Backlog Items to remove from scope so that the remaining work can be completed.</p>
<p>While the PBIs are removed from scope, their contributions should remain in the Burndown chart, so that it accurately preserves the history of the Sprint, and shows that the Team did not complete everything in their plan.</p>
<p>Adjusting Sprint scope when new information becomes available is not a failure of Scrum or the Scrum Team. It is one of the mechanisms Scrum provides for adapting to uncertainty while still completing a usable increment of the product.</p>
FAQHTML,
            ],
            [
                'id' => 'how-is-the-length-of-a-hardware-release-cycle-determined',
                'q'  => 'How is the length of a Hardware Release cycle determined?',
                'a'  => <<<'FAQHTML'
<p>Software and hardware organizations typically determine the length of their Release cycles in fundamentally different ways.</p>
<p>Software organizations most commonly develop on a cadence, with a Release cycle chosen to be, say, a three-month interval, at the end of which they release to production whatever they have developed in the last three months. Software organizations generally avoid planning Releases around fixed scope because delays in developing planned features—which inevitably occur—lead stakeholders to request that additional high-priority features be included in the current Release rather than waiting for the next one.</p>
<p>That demand leads to further delays in the current Release cycle, which leads to more demands to include features. The result can be a vicious cycle of continually expanding scope and slipping release dates. In extreme cases, this cycle can paralyze an organization so thoroughly that they cannot release at all.</p>
<p>The cadence model works in software because software development accretes features over time. Roughly speaking, you can release at any point and have a bigger feature set than you did the last time you released. That is not the case for hardware development, which accretes design elements over time, not features. Hardware development, by contrast, is much closer to a fixed-scope model because the product is generally not complete until all of the required design elements have been finished and integrated. This imposes a stronger planning constraint than is typically encountered in software development.</p>
<p>Precisely because the scope of a hardware Release cycle is fixed in most hardware organizations, the duration of the Release cycle cannot usually be determined in advance. Instead, it emerges during Release Planning as the work is allocated across Sprints and the projected completion date becomes apparent. As development proceeds, that forecast is continually refined as additional information becomes available.</p>
FAQHTML,
            ],
            [
                'id' => 'how-does-release-planning-work',
                'q'  => 'How does Release Planning work?',
                'a'  => <<<'FAQHTML'
<p>Release Planning produces the plan for the next Release cycle. More importantly, it provides a structured forum in which the engineering organization makes the key technical, scheduling, and coordination decisions that guide the Release.</p>
<p>The Release plan is a long-term plan that spans multiple Sprints and provides a framework for coordinating the work across the entire cycle. Release Planning is typically conducted during the week before the first Sprint in the cycle begins.</p>
<p>The primary purpose of Release Planning is not to predict the future perfectly, which is not possible. The purpose is to produce an adequate plan which exposes risks, dependencies, and resource conflicts early enough that they can be resolved before implementation begins.</p>
<p>Release Planning is particularly important for hardware development because it must account for the acquisition of long lead-time items, risk management, and prototype planning. All of these activities require planning well beyond the boundaries of the first Sprint.</p>
<p>The prerequisites for Release Planning are that</p>
<ul>
<li><p>The start date of the Release cycle has been selected.</p></li>
<li><p>For software development, the end date of the Release cycle has been selected. For hardware development, the end date of the Release cycle emerges from the planning process as the work is allocated across Sprints. It is not specified in advance.</p></li>
<li><p>The Product Owner has ranked (sequenced) a set of Product Backlog Items (PBIs, meaning Stories and Defect reports).</p></li>
<li><p>The Scrum Master has forecast the Team’s Velocity for each Sprint in the Release cycle. This velocity typically includes a “Velocity buffer,” in that the value to be used in Release Planning is (say) 70% of the actual Velocity forecast would be.</p></li>
<li><p>The Release planning board has been laid out on a wall, table, or electronic tool.</p></li>
<li><p>Additional supplies required have been provided (paper, pens, tape, dependency ribbons, etc.).</p></li>
</ul>
<p>For the sake of clarity, I will assume a physical Release planning board is laid out on a wall. The board has rows and columns, marked (say) by tape. Each Team has a row, and each Sprint has a column. The columns should be large enough that all PBIs planned into a Sprint can be taped into a column in rank order during the planning work. The dates of each Sprint’s first day are written at the top of the board at the start of each column. The forecast Sprint Velocity is posted for each Sprint for each Scrum Team in the same area that the PBIs will appear.</p>
<p>Scrum Teams occupy the topmost rows. If there are teams that do not use Scrum but use, say, classic project management or Kanban, they occupy the bottom rows. These teams do not work in Sprints, but they still plan against the same calendar dates provided at the top of the chart, and will position their work items accordingly.</p>
<p>After the meeting starts, each team uses Affinity Estimation to estimate the work of any PBIs for which they do not already have estimates. After they have finished estimating their PBIs, or finished estimating enough that they can start filling in the planning board, each team places its PBIs or work items on the planning board, working from left to right. Scrum Teams take care not to fill their Sprints beyond their Velocity ceilings, while other teams are responsible for using their own planning mechanisms to ensure a reasonable plan.</p>
<p>A key driver for Release Planning is to identify cross-team dependencies. There should be visible links between predecessor items and successor items when dependencies cross team boundaries. I favor the use of chevron-patterned ribbon for this purpose, as the chevrons provide visible direction when they point from predecessor item to successor item.</p>
<p>Planning often reveals dependencies that flow backward through the initial schedule. This is normal and simply indicates that some work items need to be moved before the plan is finalized.</p>
<p>Dependencies between PBIs or work items within a particular team may be handled simply by sequencing them appropriately, although there is nothing wrong with making the dependencies explicit by using the ribbons.</p>
<p>When everyone involved agrees that the plan is complete (meaning that further work on it is not likely to yield meaningful improvements), the plan should then be captured for future use. This is most commonly done by using Agile project management software, as the physical plan can easily be damaged and the board space often cannot be dedicated to hosting the plan indefinitely.</p>
<p>As with other planning activities, Release Planning provides an opportunity for progressive elaboration as the participants develop a deeper shared understanding of the work, its dependencies, and the way it will be carried out.</p>
FAQHTML,
            ],
            [
                'id' => 'how-is-progress-tracked-in-a-release-cycle',
                'q'  => 'How is Progress Tracked in a Release Cycle?',
                'a'  => <<<'FAQHTML'
<p>All of the Product Backlog Items planned into the Release cycle are known, as are their estimates in Person-Days or Story Points. The total planned work for the Release cycle is simply the sum of the PBI estimates.</p>
<p>The Burn-up chart contains two curves. The first shows the cumulative work accomplished in the Release cycle to date, computed as the sum of the estimates for all planned PBIs completed to date. The second shows the total work for the planned Release scope, which is the sum of the estimates for all PBIs planned into the Release cycle.</p>
<p>I describe the scope line as a curve because it does not simply represent a single scope value taken from the Release planning meeting. Instead, it shows the evolution of scope over time, as the inevitable departures from the original Release plan accumulate.</p>
<p>By approximately the halfway point in the Release cycle, the completed-work and planned-scope curves typically show relatively reliable trends, and straight-line extrapolations can be used to forecast where they will intersect. That intersection point is a forecast for when the likely scope of the Release cycle will be completed.</p>
<p>Rarely will the forecast completion date be the desired date, but at least we will know well before the planned completion date if the Release is likely to finish late. If the forecast is for work to complete at a time later than we wish, we have four options:</p>
<ol type="1">
<li><p>Accept the slippage</p></li>
<li><p>Decrease the scope</p></li>
<li><p>Increase the delivery capacity of the existing organization</p></li>
<li><p>Increase the resources</p></li>
</ol>
<p>Increasing delivery capacity means enabling the existing organization to accomplish more work with the resources already available. It may be possible to increase the delivery capacity of the existing organization without adding resources. Examples include:</p>
<ul>
<li><p>Eliminating unnecessary meetings</p></li>
<li><p>Deferring lower-priority work</p></li>
<li><p>Resolving organizational bottlenecks</p></li>
<li><p>Improving tooling</p></li>
<li><p>Removing external blockers</p></li>
</ul>
<p>and this is always worth investigation.</p>
<p>There is no unique answer as to which of these options to take. The answers must be based on an analysis of the situation. I can say that software products can more readily decrease scope than hardware products.</p>
<p>I can also say that, in my experience, increasing resources is seldom feasible, as it is rarely the case that more resources are available in the organization. Adding resources often slows development initially while new Team members learn the product, architecture, and development process. Any forecast should account for the temporary reduction in productivity while new Team members become effective contributors.</p>
FAQHTML,
            ],
            [
                'id' => 'can-hardware-teams-swarm-like-software-teams',
                'q'  => 'Can hardware teams swarm like software teams?',
                'a'  => <<<'FAQHTML'
<p>No. Swarming depends on Team members having sufficient overlap in their technical skills that they can readily move from one Product Backlog Item to any other. Software teams often possess that overlap. Hardware teams generally do not.</p>
<p>The same specialization that makes Person-Days more appropriate than Story Points also limits the effectiveness of swarming.</p>
<p>Swarming is a particular strategy for assigning the members of a Scrum team to Product Backlog Items (deliverables to be produced) in a Sprint. It optimizes for completing the maximum value in a Sprint, in the face of substantial uncertainty about how much work each item entails. In other words, it is a risk-mitigation technique for coping with uncertainty.</p>
<p>Suppose a Sprint contains five Product Backlog Items ranked in priority order. During Sprint Planning, the Team initially assigns small groups of Team members (swarms) to the highest-priority items. As work is completed during the Sprint, Team members move to the highest-priority remaining items where they can contribute most effectively.</p>
<p>The logic behind swarming is that the best way to ensure that the Scrum Team completes the greatest value in the Sprint is for them to finish the highest-ranked items as swiftly as possible. This way, they complete the top and most valuable subset of the planned items even if they cannot finish all planned items.</p>
<p>The size of each swarm is constrained by the nature of the work to be done. For each item, there is a maximum number of people who can truly work in parallel on it. Exceeding this limit simply slows down work, and creates idle time.</p>
<p>Scrum advocates the use of cross-functional teams. The intent is that each Scrum Team builds some aspect of the product, and has the appropriate skills to do so. As a Scrum Team’s typical deliverables require multiple skills to complete, we ensure that the Team members have all the requisite skills across their membership.</p>
<p>Some degree of specialization is inevitable, and swarming discussions naturally account for it. The degree of specialization of skills has a large impact on how swarming works.</p>
<p>The members of software-development teams commonly have skills such as coding in one or more languages, user-interface development, database development, test-plan development, test-case development, test automation, and so forth.</p>
<p>The members of hardware-development teams commonly have skills such as mechanical engineering, electrical engineering, firmware development, and occasional specialties such as antenna design.</p>
<p>Software-team members can often be characterized as generalizing specialists. Each Team member has a deep specialty in some area but can do things of modest difficulty in other areas.</p>
<p>Hardware-team members are not commonly generalizing specialists. Mastery of each single discipline is sufficiently challenging that no one person is likely to be able to work outside of his or her specialty.</p>
<p>In my experience, software teams commonly have swarm sizes of two or three Team members, whereas hardware teams often have swarm sizes of one because specialization limits meaningful parallel work on the same deliverable. In other words, the concept of swarming as a way to reduce risk and optimize the value delivered in a Sprint is not nearly as effective in a hardware context as it is in a software context.</p>
<p>These shifts make it worthwhile to address the question as to whether swarming can be enabled by changing some other aspect of the Scrum Teams. The answer is, “Yes,” but at a prohibitive cost.</p>
<p>Swarming is a tactic for improving delivery, not an objective in its own right. If enabling swarming became the objective, we could reorganize teams around engineering disciplines. We could make one Scrum Team of mechanical engineers, another of electrical engineers, and so forth. The overlap in skills among members of the same team would make swarming possible for each team’s work.</p>
<p>However, that achievement comes with a large cost. It means that individual Scrum Teams are not focused on building aspects of the product, but on engineering disciplines. This means that different Scrum Teams must collaborate closely and frequently in order to complete the product. In effect, we’ve pushed the cost of cross-skill collaboration into Program Management, which focuses on how to manage multiple teams with cross-team dependencies, instead of keeping it inside each team.</p>
<p>The cost of collaboration is much higher across team boundaries than within team boundaries, which increases the overall cost of developing products. This shift increases the time spent on collaboration, makes change more difficult to manage, and increases the risk of failed handoffs by moving more interactions across team boundaries.</p>
<p>In software development, organizing Scrum Teams to have cross-functional membership and allocating work via swarming works well. In hardware development, these two things are at odds, and only one can be accomplished. The goal is always to develop a successful product at minimal cost, as quickly as possible, so I favor having cross-functional skills on teams over the ability to swarm. Risk and cost increase if we insist on forming single-skill teams in order to enable swarming within them.</p>
FAQHTML,
            ],
            [
                'id' => 'what-should-the-product-owner-role-look-like',
                'q'  => 'What should the Product Owner role look like?',
                'a'  => <<<'FAQHTML'
<p>Product Owners are ultimately responsible for deciding what deliverables the Team will produce, and in what order. They maintain a strong focus on user needs, business objectives, and market requirements, and guide the Team toward building useful and competitive products.</p>
<p>In software development, Product Owners commonly come from a business analyst or product marketing background, and have good insights into the usability of their products. They write the bulk (sometimes all) of the User Stories the Team members need, and these User Stories comprise the majority of Product Backlog Items that the Team members will implement. Team members write a minority of Technical Stories describing architectural and infrastructural deliverables that cannot easily be expressed as User Stories.</p>
<p>As a result, the Product Owner does much more Story writing than any one Team member will do.</p>
<p>In hardware development, Product Owners commonly come from a hardware-engineering background, and must do so in order to understand the complex physical deliverables that constitute hardware products. Because hardware Product Owners routinely make decisions involving engineering tradeoffs, component selection, interfaces, manufacturability, and technical feasibility, they require substantially deeper technical expertise than is typical in software organizations.</p>
<p>This depth of insight into the engineering world, combined with their responsibility to drive product definition, means that Product Owners often have not only the ability, but also the responsibility, to function as part-time Team members and carry out engineering tasks. Their engineering participation also helps ensure that product decisions remain grounded in technical realities while preserving close alignment between product definition and implementation.</p>
<p>Since there are typically few to no User Stories in the hardware world, Technical Stories become the dominant specification artifact. Because Team members can write Technical Stories, they share the load of doing so with the Product Owner.</p>
<p>The result is that Product Owners in the hardware world write fewer Stories (a minority) and Team members write more (a majority) compared to Product Owners in the software world, where the trends are reversed.</p>
<p>These different patterns affect how Product Owners allocate their responsibilities across multiple teams. In my experience, a full-time software Product Owner can often support two or three teams concurrently, while a hardware Product Owner who is also contributing technical work is unlikely to support more than a single team.</p>
FAQHTML,
            ],
            [
                'id' => 'how-do-hardware-teams-handle-long-lead-time-components',
                'q'  => 'How do hardware teams handle long lead-time components?',
                'a'  => <<<'FAQHTML'
<p>In hardware development, some deliverables require substantially more lead time than others because they depend on fabrication, procurement, supplier schedules, testing, or regulatory activities. These long lead-time items often become the primary drivers of the project schedule.</p>
<p>Examples of long-lead time items include:</p>
<ul>
<li><p>custom tooling</p></li>
<li><p>injection molds</p></li>
<li><p>PCB fabrication and assembly</p></li>
<li><p>prototype builds</p></li>
<li><p>regulatory testing</p></li>
<li><p>environmental qualification</p></li>
<li><p>EMC compliance testing (including EMI emissions and immunity testing)</p></li>
<li><p>supplier qualification</p></li>
<li><p>agency approvals</p></li>
<li><p>custom machined parts</p></li>
<li><p>custom optics</p></li>
</ul>
<p>Sometimes these items come from external suppliers, and sometimes they come from within the organization itself. What they have in common is that they require long-term planning in order to be available when needed, and the failure to plan them into the Release cycle at the appropriate times leads to delays.</p>
<p>Planning begins by identifying the long lead-time items the Scrum Team will need. I suggest treating this like a brainstorming session with all members of the Scrum Team during Release Planning, and getting a solid list of items before proceeding with the next step.</p>
<p>The next step is to identify when the items will be needed. Sometimes it is possible to specify a particular calendar date from the start, but often the identification of when an item will be needed is an output from Release Planning, not an input to it. We see in which Sprint a Story depends on an item, and work backwards to when it needs to be ordered, when work needs to be done to produce it, and so forth.</p>
<p>For long lead-time items produced entirely by the team, it may be sufficient to work backwards through the chain of Stories needed to produce the item, and sequence those into the Release plan as appropriate. In this case, lead time emerges from the plan itself.</p>
<p>For long lead-time items produced by external suppliers, it is necessary to know what the lead time is. This will likely require communicating with the supplier to determine the supplier’s expected lead time. (I would strongly recommend allowing some buffer time around the resulting estimate, and ordering the item sufficiently earlier than the quoted lead time would imply.)</p>
<p>In this latter case, the Release plan should contain a Story about placing the order in the appropriate Sprint, so that the order is placed at the appropriate time. The advantage to making order placement an explicit part of the Release plan is that this guarantees that the order will be placed when needed, whereas maintaining a separate list of things to be done risks missing some of them.</p>
<p>Likewise, I recommend placing a Story about taking delivery of the item in the Sprint where the delivery is expected. This Story should have an estimate of zero, and serves as a tracked reminder that the delivery is expected during that Sprint. It is completed only after the item has been received (or its arrival otherwise confirmed). If the delivery Story cannot be completed because the item has not arrived, the delay immediately becomes visible and can be escalated before it affects downstream work.</p>
<p>By representing procurement and delivery as explicit Stories, supplier dependencies become visible within the Release plan without requiring suppliers themselves to appear as participants in the plan.</p>
<p>In many cases, the delivery Story should be followed by a Story to verify that the delivered item satisfies the need, especially when the component is being used for the first time or comes from a new supplier.</p>
<p>These order and delivery Stories bookend the procurement process and ensure that neither placing the order nor confirming delivery is overlooked, while the optional verification Story confirms that the delivered item is suitable for its intended use.</p>
FAQHTML,
            ],
            [
                'id' => 'does-every-sprint-have-to-produce-a-new-user-visible-capability-in-the-product',
                'q'  => 'Does every Sprint have to produce a new user-visible capability in the product?',
                'a'  => <<<'FAQHTML'
<p>No, although it is commonly assumed that the answer is yes. It is certainly desirable to have each Sprint produce something new, testable, high quality, and useful in a software product. That said, it is not always possible, and taking the desire too seriously can cause unfortunate distortions to the development work.</p>
<p>Some Sprints are heavily focused on software infrastructure work because that is what is needed, and there are no user-facing behaviors that result from them. In this scenario, what can happen is for the Scrum Team to schedule too much work into the Sprint, not finish it all, carry over the unfinished work into the next Sprint, repeating the pattern until something user-visible finally emerges. Or they simply “break the rule” and feel like failures, because they have been told by various sources that they must produce something user-facing in every Sprint.</p>
<p>The situation is more extreme in hardware development. Here the focus on building design artifacts, prototype circuits and mechanical components means that there is nothing usable until a reasonably complete prototype has been assembled, something that happens late in a Release cycle. In this world, the goal of building something usable in every Sprint is simply impossible.</p>
<p>Although a Sprint may not produce a user-visible capability, it should still produce valuable progress. Completing design artifacts, reducing technical uncertainty, validating engineering assumptions, or preparing for a prototype build are all legitimate Sprint outcomes because they reduce project risk and move the product toward completion.</p>
<p>In hardware development, the user-visible increment is often the prototype build rather than the output of an individual Sprint. Individual Sprints contribute to that build by creating the design artifacts, components, and subsystems needed to make the prototype successful.</p>
FAQHTML,
            ],
            [
                'id' => 'how-should-prototype-builds-fit-into-scrum',
                'q'  => 'How should prototype builds fit into Scrum?',
                'a'  => <<<'FAQHTML'
<p>Prototype builds are among the most important planned deliverables in a hardware Release cycle because they provide the earliest opportunity to validate engineering assumptions under realistic conditions.</p>
<p>The key rule is to prototype early and often. Each prototype should be designed to answer one or more specific engineering questions. A prototype whose purpose is unclear is unlikely to provide maximum value.</p>
<p>Instead of assuming that a particular design for a printed circuit board will work, and continuing design work based on that assumption, build the board, test it, and learn from the results. All prototypes are experiments, and the earlier we conduct experiments and learn from them, the fewer unpleasant surprises we will have later on.</p>
<p>It is tempting to assume that a design will work, and continue investing in additional design work based on that hypothesis. This is a very risky style of work, though, because it implicitly assumes that there are no “unknown unknowns.” In reality, one cannot know if there are unknown unknowns in the absence of running into them. Prototyping, even on small scales, is how we find these unknowns and are well-positioned to deal with them while the cost is small.</p>
<p>As work progresses through the Release cycle, prototyping should continue, increasingly on larger scales. The printed circuit board verified two Sprints ago now resides in a chassis with other components and enables some of the product’s functionality to be tested in a breadboard setting.</p>
<p>Towards the end of the Release cycle, the goal is to build a complete and working prototype. Depending on which Release cycle this is, the prototype may be</p>
<ol type="1">
<li><p>A functional prototype—the first version to provide the desired functionality, but not intended for manufacture.</p></li>
<li><p>A manufacturing prototype—the first version built using manufacturing-oriented processes to validate manufacturability. Experience often reveals that additional refinements are needed before production.</p></li>
<li><p>The final engineering design—the final engineering design released to manufacturing.</p></li>
</ol>
<p>Prototyping always tests hypotheses and mitigates risks. One of the biggest mistakes a Scrum team can make is to pare back prototyping on the theory that it costs time that cannot be afforded. The result is likely to be late-stage design changes that are much more costly than prototyping would have been.</p>
FAQHTML,
            ],
            [
                'id' => 'how-do-you-manage-technical-risks-with-scrum',
                'q'  => 'How do you manage technical risks with Scrum?',
                'a'  => <<<'FAQHTML'
<p>A Scrum process does not eliminate risks. It exposes risks early, while there is still time to respond to them. That said, there is substantial value in being pro-active about identifying and mitigating risks as early as possible.</p>
<p>Traditional project management often treats risk management as a separate activity involving risk registers, probability estimates, and periodic reviews. While those practices have value, Scrum manages much of technical risk by changing the development process itself.</p>
<p>One of the central principles of Scrum is to expose uncertainty early. The purpose of many Scrum practices is not productivity—it is uncertainty reduction. The earlier a risk becomes visible, the less expensive it usually is to address.</p>
<p>Several Agile and engineering practices contribute directly to this objective.</p>
<ul>
<li><p><strong>Release Planning</strong> exposes cross-team dependencies, resource conflicts, and schedule risks before implementation begins.</p></li>
<li><p><strong>Progressive Elaboration</strong> improves the Scrum Team's understanding of deliverables before work starts.</p></li>
<li><p><strong>Planning Poker</strong> exposes differing assumptions among Team members before implementation begins.</p></li>
<li><p><strong>Prototype builds</strong> test engineering hypotheses and uncover unknown problems while design changes are still relatively inexpensive.</p></li>
<li><p><strong>Sprint Reviews</strong> provide frequent opportunities to validate that development is moving in the desired direction.</p></li>
<li><p><strong>Retrospectives</strong> identify weaknesses in the development process so they can be corrected before they become chronic problems.</p></li>
</ul>
<p>Perhaps the greatest contribution of Agile to risk management is that it encourages engineering organizations to validate assumptions early and continuously rather than allowing uncertainty to accumulate until late stages of development. In hardware development, where late-stage design changes can be extremely expensive, this shift in philosophy can substantially reduce project risk.</p>
<p>While the above practices naturally reduce risk, I also recommend making risk identification an explicit part of Release Planning. In this period, the Scrum Team writes Stories and Epics (Product Backlog Items) about deliverables to be produced in the Release cycle. The Team should identify all significant technical and project risks and write Stories describing the work needed to investigate or mitigate those that warrant action during the Release cycle.</p>
<p>The Project Management Institute defines four strategies of risk management:</p>
<ul>
<li><p>Avoid: Take action to ensure the risk is not encountered.</p></li>
<li><p>Transfer: Delegate the risk management responsibility to a different organization or person.</p></li>
<li><p>Accept: Decide that the risk will be accepted without further mitigation, and be prepared to deal with the consequences if it occurs.</p></li>
<li><p>Mitigate: Take steps to minimize the impact of the risk as much as practical.</p></li>
</ul>
<p>These four strategies provide a useful framework for deciding how each identified risk should be handled. Not all risks require Stories to be written about them. Some simply require management decisions, such as accepting the risk associated with using a new supplier or adding schedule contingency around an uncertain delivery date.</p>
<p>I favor identifying risks as a brainstorming process, where the goal is to get as many risks identified as possible. Next, classify each identified risk according to the four strategies above. Finally, write the Stories about the work needed to investigate or mitigate all identified risks that require planned action by the Scrum Team, and come up with a rough concept of where in the Release cycle they can be addressed (which should be as early as possible). The resulting Release plan should be front-loaded with risk-mitigation Stories whenever practical, because risks are least expensive to resolve early in the project.</p>
<p>The list of possible risks is long. Some examples of risk categories include:</p>
<ul>
<li><p><strong>Technical risks</strong> — the design may not work as intended.</p></li>
<li><p><strong>Integration risks</strong> — independently developed subsystems may not work together.</p></li>
<li><p><strong>Long lead-time risks</strong> — critical components or prototype hardware may not arrive when needed.</p></li>
<li><p><strong>Supplier risks</strong> — Suppliers may deliver late, deliver components that are defective or unsuitable for the intended application, or discontinue critical components.</p></li>
<li><p><strong>Requirements risks</strong> — the Team may misunderstand or incorrectly implement the product requirements.</p></li>
<li><p><strong>Schedule risks</strong> — the work may take longer than anticipated, delaying the Release.</p></li>
</ul>
<p>Here again is the philosophy that all work to be performed by the Scrum Team should appear as Stories in the Release and Sprint plans. Not every identified risk requires such work, however. Some risks are accepted, transferred, or otherwise handled through management decisions rather than engineering effort. The purpose of risk identification is to make conscious decisions about which risks require planned engineering work and which do not.</p>
FAQHTML,
            ],
            [
                'id' => 'how-does-quality-control-work',
                'q'  => 'How does quality control work?',
                'a'  => <<<'FAQHTML'
<p>Quality Control is fundamental to any product-development process. Hardware and software development, however, organize this function quite differently.</p>
<p>I have to start by defining terms. In software development, the role responsible for testing the product, and either confirming it works or finding defects in it, is referred to as the <em>Quality Assurance</em> role. In hardware development, this function is commonly referred to as <em>Quality Control</em>. People who sit on one side of the divide commonly find the terminology on the other side confusing. Throughout this discussion, I will use each term in its conventional context.</p>
<p>In software, the common pattern is that Scrum Teams implement a majority of User Stories and a minority of Technical Stories in an average Sprint. (Technical Stories describe non-user-facing deliverables and do not reference user roles.) In organizations that have dedicated QA personnel, they commonly test the deliverables of User Stories by exercising their new functionality through the product’s user interface.</p>
<p>Software developers typically test or verify the deliverables of Technical Stories themselves, or in consultation with other Team members, as their deliverables do not generate user-facing behavior in the product. For example, a Technical Story whose deliverable is an architecture document might undergo review by other Team members to verify that it is appropriately complete and suitable to the need.</p>
<p>A key point to note here is that the QA personnel perform testing on a Story-level basis. They may also be called upon to perform whole-product testing in a final round before the product is delivered to customers, though this practice is not universal.</p>
<p>Why do we not have developers testing their own deliverables from User Stories? The reason is that the software QA skill set is a distinct discipline that is different from the developer skill set, and QA personnel will generally be more effective at finding defects than developers will.</p>
<p>The hardware world is different. Because Technical Stories dominate, there are few, if any, User Stories to be tested. The skills required to test the deliverables of Technical Stories mirror the skills required to do the work for those Stories: electrical engineering, mechanical engineering, firmware, and so forth. There is no equivalent to a Story-level “QC” role other than the engineer who created the deliverable of the Story. Thus, the engineer who created the deliverable is usually the person best qualified to verify that it satisfies the intent of the Story, although peer reviews remain valuable where appropriate.</p>
<p>This does not mean that there is no concept of Quality Control in hardware development. What it does mean is that the function does not sit on a Scrum Team and engage with each Story. Instead, the Quality Control personnel are a separate group that tests and evaluates prototypes once they have enough functionality to be testable.</p>
<p>Unlike software, where individual Stories often produce immediately testable user-facing functionality, hardware products generally cannot be evaluated by Quality Control until enough physical subsystems have been integrated into a working prototype. This engagement with the state of product development is intermittent, rather than continuous. From a software perspective, this reality may seem like a limitation, but it reflects the physical realities of hardware development.</p>
FAQHTML,
            ],
            [
                'id' => 'should-hardware-and-embedded-software-engineers-work-on-the-same-scrum-team',
                'q'  => 'Should hardware and embedded software engineers work on the same Scrum Team?',
                'a'  => <<<'FAQHTML'
<p>In most cases, yes.</p>
<p>Embedded software and hardware development are often so tightly coupled that separating them into different Scrum Teams creates unnecessary coordination overhead. Decisions about hardware interfaces, timing, electrical behavior, memory usage, and board bring-up frequently require close collaboration between hardware and embedded software engineers. Organizing them within the same cross-functional Scrum Team allows many of these decisions to be made quickly within the team rather than through cross-team coordination.</p>
<p>This does not eliminate the challenges of specialization discussed elsewhere in this FAQ. Hardware and embedded software engineers generally remain specialists in their respective disciplines, so Sprint Planning, estimation, and work allocation should still recognize those differences. However, the organizational benefits of keeping closely coupled engineering disciplines within the same Scrum Team generally outweigh the advantages of separating them.</p>
FAQHTML,
            ],
        ],
    ],
    [
        'id'    => 'consulting-and-training-questions',
        'title' => 'Consulting and Training Questions',
        'questions' => [
            [
                'id' => 'what-kinds-of-organizations-do-you-work-with',
                'q'  => 'What kinds of organizations do you work with?',
                'a'  => <<<'FAQHTML'
<p>I work with organizations that develop complex products, with a particular focus on hardware and integrated hardware-software engineering.</p>
FAQHTML,
            ],
            [
                'id' => 'what-industries-have-you-worked-in',
                'q'  => 'What industries have you worked in?',
                'a'  => <<<'FAQHTML'
<p>My experience spans robotics, biotechnology, telecommunications, aerospace, instrumentation, medical devices, and other complex engineering industries.</p>
FAQHTML,
            ],
            [
                'id' => 'how-is-your-approach-different-from-traditional-agile-consulting',
                'q'  => 'How is your approach different from traditional Agile consulting?',
                'a'  => <<<'FAQHTML'
<p>Most Agile consulting has been developed around software organizations. My work focuses on adapting Agile to the realities of hardware and multidisciplinary engineering, including specialized engineering disciplines, long lead-time components, prototype-driven development, Release Planning, dependency management, and technical decision-making. The goal is to improve delivery predictability, reduce late-stage surprises, and strengthen cross-functional coordination in complex engineering environments.</p>
FAQHTML,
            ],
            [
                'id' => 'what-does-an-agile-assessment-involve',
                'q'  => 'What does an Agile assessment involve?',
                'a'  => <<<'FAQHTML'
<p>An Agile assessment begins by working with the sponsor to identify the issues that will be the focal point of the assessment and the people or groups to be interviewed. The next step is to interview the identified individuals and groups to develop an understanding of the issues. The findings are then analyzed to identify recurring patterns, organizational strengths, and opportunities for improvement, followed by development of the assessment report. The report contains conclusions, recommendations, and suggested next steps, which may include an Agile transformation, targeted coaching, training, or other process improvements.</p>
<p>Assessments for a single small group typically take about one week. Assessments involving multiple teams or departments generally take several weeks, depending on the size and complexity of the organization.</p>
<p>For many organizations, the assessment provides the foundation for planning the Agile transformation described in the next question.</p>
FAQHTML,
            ],
            [
                'id' => 'what-does-an-agile-transformation-involve',
                'q'  => 'What does an Agile transformation involve?',
                'a'  => <<<'FAQHTML'
<p>The scope and duration of a transformation depend primarily on the size of the organization undergoing the transformation. Every organization is different, but the examples below illustrate the steps and timelines I commonly see in practice.</p>
<p>If only one Scrum Team is involved (say, eight people), then the work includes planning the transformation, training the Scrum Team, and mentoring the team through its first few Sprints. This can typically be done in about two months.</p>
<p>If the organization includes enough engineers for approximately 15 Scrum teams (say, 120 people), then the transformation typically begins with an organizational assessment and transformation planning. This is followed by leadership training, Scrum training for the development teams, Release Planning and Sprint Planning workshops, and mentoring through two Release cycles.</p>
<p>Assuming three-month Release cycles, the entire sequence typically spans about seven months. By that point, the organization is typically able to continue the transformation with much less external coaching.</p>
<p>The additional time is required not simply because there are more people, but because multiple teams must learn to coordinate, plan together, and execute multiple Release cycles while developing real products. The goal is not simply to complete the transformation as quickly as possible, but to establish sustainable ways of working that continue long after the consultant has left.</p>
<p>Although every organization is different, the transformations I lead generally follow this pattern, with the details varying according to the organization's size, complexity, and level of Agile experience.</p>
FAQHTML,
            ],
            [
                'id' => 'can-you-work-with-teams-that-already-use-scrum',
                'q'  => 'Can you work with teams that already use Scrum?',
                'a'  => <<<'FAQHTML'
<p>Certainly. It is not uncommon that an organization already has Scrum Teams but finds that they are having difficulties and experiencing confusion about how to work effectively with the Scrum framework. For example, they may be trying to use some of the software-oriented practices in a hardware context where they do not fit. In these cases, I will spend some time with the teams and coach them to reduce confusion and improve their effectiveness.</p>
FAQHTML,
            ],
            [
                'id' => 'can-you-help-organizations-that-are-only-beginning-to-explore-agile',
                'q'  => 'Can you help organizations that are only beginning to explore Agile?',
                'a'  => <<<'FAQHTML'
<p>Yes. Many organizations contact me before they have formally adopted Agile. I can help evaluate whether Agile is appropriate for your organization, explain what changes would be involved, and develop a practical adoption strategy.</p>
FAQHTML,
            ],
            [
                'id' => 'do-you-provide-executive-coaching',
                'q'  => 'Do you provide executive coaching?',
                'a'  => <<<'FAQHTML'
<p>I do. I offer a one-day Agile workshop for company leadership, and can also provide one-on-one coaching for executives.</p>
FAQHTML,
            ],
            [
                'id' => 'do-you-offer-on-site-and-remote-consulting',
                'q'  => 'Do you offer on-site and remote consulting?',
                'a'  => <<<'FAQHTML'
<p>I do, although I much prefer on-site consulting because it is much more effective. Remote consulting is adequate for simple question-and-answer sessions, but not for anything more complex. I advocate that clients opt for on-site consulting as much as possible. If I believe that what a client wants requires on-site presence, I will say so.</p>
FAQHTML,
            ],
            [
                'id' => 'what-training-courses-do-you-offer',
                'q'  => 'What training courses do you offer?',
                'a'  => <<<'FAQHTML'
<p>I offer these standard courses:</p>
<ul>
<li><p>Agile Overview for Executives and Managers</p></li>
<li><p>Agile Software Development with Scrum</p></li>
<li><p>Agile Hardware Development with Scrum</p></li>
<li><p>Agile Work Management with Kanban</p></li>
<li><p>Agile Program Management</p></li>
<li><p>Agile Portfolio Management</p></li>
<li><p>Advanced Product Owner</p></li>
</ul>
FAQHTML,
            ],
            [
                'id' => 'how-interactive-are-your-training-classes',
                'q'  => 'How interactive are your training classes?',
                'a'  => <<<'FAQHTML'
<p>My classes encourage questions and discussion throughout. While the course material follows a structured curriculum, participants are encouraged to relate the concepts to their own products, projects, and organizational challenges. These discussions often become one of the most valuable aspects of the class.</p>
FAQHTML,
            ],
            [
                'id' => 'closing-thoughts',
                'q'  => 'Closing Thoughts',
                'a'  => <<<'FAQHTML'
<p>The challenge addressed in this FAQ is not to make hardware development conform to Scrum. It is to adapt commonly taught Scrum practices to the realities of hardware development while preserving the underlying principles of Agile. When those adaptations are made thoughtfully, Scrum provides a highly effective framework for managing the development of hardware and complex hardware-software products, helping engineering organizations make better technical decisions, expose uncertainty earlier, and reduce late-stage surprises.</p>
FAQHTML,
            ],
            [
                'id' => 'how-do-we-get-started',
                'q'  => 'How do we get started?',
                'a'  => <<<'FAQHTML'
<p>Use the Contact form to describe your organization, your products, and the challenges you are trying to address. I typically respond within one working day, and we can then discuss whether an assessment, training, coaching, or consulting engagement would be the best fit.</p>
FAQHTML,
            ],
        ],
    ],
];
