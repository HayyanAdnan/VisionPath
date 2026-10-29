-- =============================================
-- SEED DATA: starter set of skills + careers
-- Run this AFTER schema.sql and migration_roadmap_fix.sql
-- Safe to run once. If you re-run it, delete existing rows first
-- or it will create duplicates (titles aren't UNIQUE by default).
-- =============================================

-- ── 1. Skills (referenced by interest_skill_map and career_skills) ──
INSERT INTO skills (name, category) VALUES
('Programming', 'Technology'), ('Web Development', 'Technology'),
('Software Engineering', 'Technology'), ('Cloud Computing', 'Technology'),
('Cybersecurity', 'Technology'), ('Mobile Development', 'Technology'),
('Python', 'Technology'), ('SQL', 'Technology'),
('Machine Learning', 'Technology'), ('Deep Learning', 'Technology'),
('Research', 'Science'), ('Lab Techniques', 'Science'),
('Data Analysis', 'Data'), ('Biology', 'Science'), ('Chemistry', 'Science'),
('Graphic Design', 'Design'), ('UI/UX Design', 'Design'),
('Illustration', 'Design'), ('3D Modeling', 'Design'),
('Finance', 'Business'), ('Strategy', 'Business'),
('Project Management', 'Business'), ('Accounting', 'Business'), ('Sales', 'Business'),
('Patient Care', 'Healthcare'), ('Medical Knowledge', 'Healthcare'),
('Anatomy', 'Healthcare'), ('Clinical Skills', 'Healthcare'),
('Statistics', 'Data'),
('Digital Marketing', 'Marketing'), ('Content Creation', 'Marketing'),
('Social Media', 'Marketing'), ('Branding', 'Marketing'),
('Sustainability', 'Environment'), ('Environmental Science', 'Environment'),
('Communication', 'Soft Skills'), ('Curriculum Design', 'Education'), ('Mentoring', 'Education'),
('Game Design', 'Technology'),
('Counselling', 'Healthcare'),
('Journalism', 'Marketing'),
('Mechanical Design', 'Engineering'), ('CAD', 'Engineering'), ('Problem Solving', 'Soft Skills'),
('Physics', 'Science'), ('Mathematics', 'Science'),
('Legal Research', 'Law'), ('Critical Thinking', 'Soft Skills')
ON DUPLICATE KEY UPDATE name = name;

-- ── 2. Careers ──
-- (avg_salary/ranges are illustrative starting points in PKR/month -
--  adjust to your target market/currency before going live)

INSERT INTO careers
(title, description, category, sub_category, growth_rate, avg_salary, salary_range_low, salary_range_high,
 ai_replacement_risk, status, health_score, day_to_day, education_path,
 roadmap_json, courses_json, required_skills_json)
VALUES

('Software Engineer',
 'Designs, builds, and maintains software applications and systems.',
 'Technology', 'Software Development', 18.5, 150000, 80000, 350000,
 'low', 'active', 95,
 'Writing and reviewing code, debugging, attending stand-ups, collaborating with designers and product managers, deploying features.',
 'Bachelor\'s in CS or self-taught + portfolio',
 JSON_ARRAY(
   JSON_OBJECT('title','Programming Foundations','desc','Learn a language (Python/JS), variables, loops, functions, OOP basics.','duration','2 months'),
   JSON_OBJECT('title','Data Structures & Algorithms','desc','Arrays, linked lists, trees, sorting, time complexity.','duration','2 months'),
   JSON_OBJECT('title','Web/App Development','desc','Build full projects with a frontend, backend, and database.','duration','3 months'),
   JSON_OBJECT('title','Version Control & Collaboration','desc','Git, GitHub, code review, working in a team workflow.','duration','1 month'),
   JSON_OBJECT('title','Build a Portfolio','desc','3-5 polished projects deployed and on GitHub.','duration','2 months'),
   JSON_OBJECT('title','Interview Prep & Job Hunt','desc','LeetCode practice, mock interviews, applying.','duration','2 months')
 ),
 JSON_ARRAY(
   JSON_OBJECT('name','CS50: Introduction to Computer Science','platform','edX / Harvard','level','Beginner'),
   JSON_OBJECT('name','The Odin Project (Full Stack)','platform','Free / Self-paced','level','Beginner'),
   JSON_OBJECT('name','Meta Front-End Developer Certificate','platform','Coursera','level','Intermediate'),
   JSON_OBJECT('name','Grokking the Coding Interview','platform','Educative','level','Advanced')
 ),
 JSON_ARRAY('Programming','Web Development','Software Engineering','Problem Solving')
),

('Data Scientist',
 'Analyzes complex data to extract insights and build predictive models.',
 'Technology', 'Data', 22.0, 180000, 100000, 400000,
 'low', 'active', 96,
 'Cleaning datasets, building models, visualizing findings, presenting insights to stakeholders.',
 'Bachelor\'s/Master\'s in Stats, CS, or related + projects',
 JSON_ARRAY(
   JSON_OBJECT('title','Statistics & Math Foundations','desc','Probability, statistics, linear algebra basics.','duration','2 months'),
   JSON_OBJECT('title','Python for Data Science','desc','Pandas, NumPy, data wrangling.','duration','2 months'),
   JSON_OBJECT('title','Machine Learning Fundamentals','desc','Regression, classification, model evaluation.','duration','3 months'),
   JSON_OBJECT('title','Data Visualization & Storytelling','desc','Matplotlib, Tableau/PowerBI, communicating insights.','duration','1 month'),
   JSON_OBJECT('title','Real Projects & Kaggle','desc','End-to-end projects on real datasets.','duration','3 months'),
   JSON_OBJECT('title','Specialize (NLP/CV/etc) & Job Hunt','desc','Pick a niche, build a portfolio, apply.','duration','2 months')
 ),
 JSON_ARRAY(
   JSON_OBJECT('name','Google Data Analytics Certificate','platform','Coursera','level','Beginner'),
   JSON_OBJECT('name','Python for Everybody','platform','Coursera / Michigan','level','Beginner'),
   JSON_OBJECT('name','IBM Data Science Professional Certificate','platform','Coursera','level','Intermediate'),
   JSON_OBJECT('name','Deep Learning Specialization','platform','Coursera / DeepLearning.AI','level','Advanced')
 ),
 JSON_ARRAY('Data Analysis','Statistics','Machine Learning','Python')
),

('Cybersecurity Analyst',
 'Protects systems and networks from cyber threats and breaches.',
 'Technology', 'Security', 19.0, 160000, 90000, 320000,
 'low', 'active', 93,
 'Monitoring systems for threats, running vulnerability scans, responding to incidents, writing security reports.',
 'Bachelor\'s in CS/IT + security certifications (Security+, CEH)',
 JSON_ARRAY(
   JSON_OBJECT('title','Networking & OS Fundamentals','desc','TCP/IP, Linux, Windows administration.','duration','2 months'),
   JSON_OBJECT('title','Security Fundamentals','desc','CIA triad, common attack types, basic hardening.','duration','2 months'),
   JSON_OBJECT('title','Tools & Practice','desc','Wireshark, Nmap, SIEM basics, home lab.','duration','2 months'),
   JSON_OBJECT('title','Certifications','desc','CompTIA Security+, then CEH or similar.','duration','3 months'),
   JSON_OBJECT('title','Hands-on CTFs & Labs','desc','TryHackMe / HackTheBox practice.','duration','2 months'),
   JSON_OBJECT('title','Job Hunt & Specialization','desc','Pick SOC analyst / pentest / GRC track.','duration','2 months')
 ),
 JSON_ARRAY(
   JSON_OBJECT('name','Google Cybersecurity Certificate','platform','Coursera','level','Beginner'),
   JSON_OBJECT('name','CompTIA Security+','platform','CompTIA','level','Intermediate'),
   JSON_OBJECT('name','TryHackMe Pre-Security Path','platform','TryHackMe','level','Beginner'),
   JSON_OBJECT('name','Certified Ethical Hacker (CEH)','platform','EC-Council','level','Advanced')
 ),
 JSON_ARRAY('Cybersecurity','Problem Solving','Programming')
),

('UI/UX Designer',
 'Designs intuitive, user-friendly digital interfaces and experiences.',
 'Design', 'Product Design', 15.0, 120000, 60000, 280000,
 'low', 'active', 90,
 'User research, wireframing, prototyping in Figma, usability testing, working closely with developers.',
 'Bootcamp/self-taught + strong portfolio (degree optional)',
 JSON_ARRAY(
   JSON_OBJECT('title','Design Fundamentals','desc','Color theory, typography, layout, visual hierarchy.','duration','1 month'),
   JSON_OBJECT('title','UX Research & Process','desc','User personas, journey maps, usability testing.','duration','2 months'),
   JSON_OBJECT('title','Tools: Figma/Adobe XD','desc','Wireframes, prototypes, design systems.','duration','2 months'),
   JSON_OBJECT('title','Case Studies','desc','3-4 full case studies for your portfolio.','duration','3 months'),
   JSON_OBJECT('title','Collaboration with Devs','desc','Handoff, design tokens, basic HTML/CSS literacy.','duration','1 month'),
   JSON_OBJECT('title','Portfolio & Job Hunt','desc','Polish portfolio site, apply, interview with case studies.','duration','2 months')
 ),
 JSON_ARRAY(
   JSON_OBJECT('name','Google UX Design Certificate','platform','Coursera','level','Beginner'),
   JSON_OBJECT('name','Figma for UI/UX','platform','YouTube / DesignCourse','level','Beginner'),
   JSON_OBJECT('name','UX Research & Strategy','platform','Coursera / CalArts','level','Intermediate'),
   JSON_OBJECT('name','Advanced Prototyping','platform','Udemy','level','Advanced')
 ),
 JSON_ARRAY('UI/UX Design','Graphic Design','Communication')
),

('Digital Marketing Specialist',
 'Plans and runs online marketing campaigns across channels.',
 'Marketing', 'Digital Marketing', 14.0, 100000, 50000, 250000,
 'medium', 'active', 85,
 'Running ad campaigns, writing content, analyzing performance metrics, managing social media.',
 'Bachelor\'s in any field + certifications (degree optional)',
 JSON_ARRAY(
   JSON_OBJECT('title','Marketing Fundamentals','desc','Funnels, branding basics, target audiences.','duration','1 month'),
   JSON_OBJECT('title','SEO & Content','desc','Keyword research, on-page SEO, content strategy.','duration','2 months'),
   JSON_OBJECT('title','Paid Ads','desc','Google Ads, Meta Ads, budgeting and targeting.','duration','2 months'),
   JSON_OBJECT('title','Analytics','desc','Google Analytics, tracking conversions, reporting.','duration','1 month'),
   JSON_OBJECT('title','Run Real Campaigns','desc','Manage a real or simulated campaign end-to-end.','duration','2 months'),
   JSON_OBJECT('title','Specialize & Job Hunt','desc','Pick SEO/Paid/Social niche, build case studies, apply.','duration','2 months')
 ),
 JSON_ARRAY(
   JSON_OBJECT('name','Google Digital Marketing & E-commerce','platform','Coursera','level','Beginner'),
   JSON_OBJECT('name','HubSpot Content Marketing Certification','platform','HubSpot Academy','level','Beginner'),
   JSON_OBJECT('name','Meta Social Media Marketing','platform','Coursera','level','Intermediate'),
   JSON_OBJECT('name','Google Ads Certification','platform','Google Skillshop','level','Advanced')
 ),
 JSON_ARRAY('Digital Marketing','Content Creation','Social Media','Branding')
),

('Registered Nurse',
 'Provides direct patient care, administers treatment, supports recovery.',
 'Healthcare', 'Clinical Care', 12.0, 90000, 50000, 180000,
 'low', 'active', 92,
 'Monitoring patients, administering medication, coordinating with doctors, patient education.',
 'BSN/Nursing diploma + licensing exam',
 JSON_ARRAY(
   JSON_OBJECT('title','Pre-Nursing Foundations','desc','Biology, anatomy, chemistry basics.','duration','3 months'),
   JSON_OBJECT('title','Nursing School','desc','Formal BSN or diploma program (most of the timeline).','duration','2-4 years'),
   JSON_OBJECT('title','Clinical Rotations','desc','Hands-on hospital/clinic experience across departments.','duration','1 year'),
   JSON_OBJECT('title','Licensing Exam Prep','desc','NCLEX or local equivalent exam preparation.','duration','2 months'),
   JSON_OBJECT('title','Specialization','desc','Choose ICU, pediatrics, ER, etc.','duration','Ongoing'),
   JSON_OBJECT('title','Job Placement','desc','Apply to hospitals/clinics, onboarding.','duration','1-2 months')
 ),
 JSON_ARRAY(
   JSON_OBJECT('name','Anatomy & Physiology','platform','Khan Academy','level','Beginner'),
   JSON_OBJECT('name','BSN Program','platform','Accredited University','level','Intermediate'),
   JSON_OBJECT('name','NCLEX-RN Prep Course','platform','UWorld / Kaplan','level','Advanced')
 ),
 JSON_ARRAY('Patient Care','Medical Knowledge','Anatomy','Clinical Skills')
),

('Doctor (General Physician)',
 'Diagnoses and treats illnesses, provides preventive healthcare advice.',
 'Healthcare', 'Medicine', 10.0, 200000, 100000, 600000,
 'low', 'active', 94,
 'Patient consultations, diagnosis, prescribing treatment, referrals, continuous learning.',
 'MBBS + house job + licensing (long path, 5-7+ years)',
 JSON_ARRAY(
   JSON_OBJECT('title','Pre-Medical Foundations','desc','Biology, Chemistry, Physics at intensive level.','duration','2 years'),
   JSON_OBJECT('title','Medical School (MBBS)','desc','Core medical education and theory.','duration','5 years'),
   JSON_OBJECT('title','Clinical Rotations','desc','Hands-on training across specialties.','duration','1-2 years'),
   JSON_OBJECT('title','House Job / Internship','desc','Supervised practice in a hospital.','duration','1 year'),
   JSON_OBJECT('title','Licensing Exam','desc','Pass the national licensing exam.','duration','3 months'),
   JSON_OBJECT('title','Choose GP or Specialize','desc','Start practicing or pursue a specialty residency.','duration','Ongoing')
 ),
 JSON_ARRAY(
   JSON_OBJECT('name','MBBS Program','platform','Medical University','level','Beginner'),
   JSON_OBJECT('name','Clinical Skills Training','platform','Hospital-affiliated','level','Intermediate'),
   JSON_OBJECT('name','Licensing Exam Prep','platform','Country-specific board','level','Advanced')
 ),
 JSON_ARRAY('Medical Knowledge','Patient Care','Anatomy','Clinical Skills')
),

('Mechanical Engineer',
 'Designs, analyzes, and tests mechanical systems and machines.',
 'Engineering', 'Mechanical', 9.0, 130000, 70000, 280000,
 'medium', 'active', 87,
 'CAD design, running simulations, testing prototypes, working with manufacturing teams.',
 'Bachelor\'s in Mechanical Engineering',
 JSON_ARRAY(
   JSON_OBJECT('title','Math & Physics Foundations','desc','Calculus, mechanics, thermodynamics basics.','duration','6 months'),
   JSON_OBJECT('title','Engineering Degree Core','desc','Core mechanical engineering coursework.','duration','3 years'),
   JSON_OBJECT('title','CAD & Simulation Tools','desc','SolidWorks/AutoCAD, FEA basics.','duration','3 months'),
   JSON_OBJECT('title','Internship/Practical Projects','desc','Real-world design or manufacturing experience.','duration','6 months'),
   JSON_OBJECT('title','Specialization','desc','Robotics, automotive, HVAC, etc.','duration','Ongoing'),
   JSON_OBJECT('title','Job Hunt & Licensing','desc','Apply for roles, pursue PE license if relevant.','duration','2-3 months')
 ),
 JSON_ARRAY(
   JSON_OBJECT('name','Mechanical Engineering Degree','platform','Accredited University','level','Beginner'),
   JSON_OBJECT('name','SolidWorks Essentials','platform','Udemy','level','Intermediate'),
   JSON_OBJECT('name','Finite Element Analysis','platform','Coursera','level','Advanced')
 ),
 JSON_ARRAY('Mechanical Design','CAD','Problem Solving','Mathematics')
),

('Financial Analyst',
 'Analyzes financial data to guide business and investment decisions.',
 'Business', 'Finance', 11.0, 140000, 70000, 320000,
 'medium', 'active', 88,
 'Building financial models, analyzing reports, forecasting, presenting to stakeholders.',
 'Bachelor\'s in Finance/Business + certifications (CFA helpful)',
 JSON_ARRAY(
   JSON_OBJECT('title','Finance & Accounting Basics','desc','Financial statements, accounting principles.','duration','2 months'),
   JSON_OBJECT('title','Excel & Financial Modeling','desc','Build models, forecasting, valuation basics.','duration','2 months'),
   JSON_OBJECT('title','Core Finance Coursework','desc','Corporate finance, markets, investment principles.','duration','4 months'),
   JSON_OBJECT('title','Certifications','desc','Start CFA Level 1 or equivalent.','duration','6 months'),
   JSON_OBJECT('title','Internship/Practical Projects','desc','Real model-building experience.','duration','3 months'),
   JSON_OBJECT('title','Job Hunt','desc','Apply to firms, prep for case-study interviews.','duration','2 months')
 ),
 JSON_ARRAY(
   JSON_OBJECT('name','Financial Markets','platform','Coursera / Yale','level','Beginner'),
   JSON_OBJECT('name','Financial Modeling & Valuation','platform','Wall Street Prep','level','Intermediate'),
   JSON_OBJECT('name','CFA Level 1 Prep','platform','Kaplan Schweser','level','Advanced')
 ),
 JSON_ARRAY('Finance','Accounting','Data Analysis','Strategy')
),

('Graphic Designer',
 'Creates visual content for branding, marketing, and media.',
 'Design', 'Visual Design', 8.0, 90000, 40000, 220000,
 'medium', 'active', 82,
 'Designing logos, layouts, social media graphics, working with client briefs.',
 'Bootcamp/self-taught + strong portfolio (degree optional)',
 JSON_ARRAY(
   JSON_OBJECT('title','Design Fundamentals','desc','Color, typography, composition.','duration','1 month'),
   JSON_OBJECT('title','Tools: Adobe Suite','desc','Photoshop, Illustrator, InDesign.','duration','2 months'),
   JSON_OBJECT('title','Branding Projects','desc','Logo design, brand identity systems.','duration','2 months'),
   JSON_OBJECT('title','Build a Portfolio','desc','5-8 strong pieces across different styles.','duration','2 months'),
   JSON_OBJECT('title','Freelance/Client Work','desc','Take on real briefs, build a client base.','duration','3 months'),
   JSON_OBJECT('title','Job Hunt or Go Freelance','desc','Apply to agencies or build a freelance pipeline.','duration','2 months')
 ),
 JSON_ARRAY(
   JSON_OBJECT('name','Graphic Design Specialization','platform','Coursera / CalArts','level','Beginner'),
   JSON_OBJECT('name','Adobe Illustrator/Photoshop Masterclass','platform','Udemy','level','Intermediate'),
   JSON_OBJECT('name','Branding & Identity Design','platform','Skillshare','level','Advanced')
 ),
 JSON_ARRAY('Graphic Design','Illustration','Communication')
),

('Environmental Scientist',
 'Studies environmental problems and develops solutions to protect ecosystems.',
 'Science', 'Environment', 13.0, 110000, 60000, 240000,
 'low', 'active', 89,
 'Field sampling, lab analysis, writing reports, advising on environmental policy.',
 'Bachelor\'s/Master\'s in Environmental Science',
 JSON_ARRAY(
   JSON_OBJECT('title','Science Foundations','desc','Biology, chemistry, earth science basics.','duration','3 months'),
   JSON_OBJECT('title','Environmental Science Degree','desc','Core coursework and lab work.','duration','3-4 years'),
   JSON_OBJECT('title','Fieldwork & Data Collection','desc','Sampling, monitoring, GIS basics.','duration','6 months'),
   JSON_OBJECT('title','Research Project','desc','Independent or thesis-level research.','duration','6 months'),
   JSON_OBJECT('title','Internship','desc','Work with an NGO, agency, or lab.','duration','3-6 months'),
   JSON_OBJECT('title','Job Hunt / Further Study','desc','Apply to roles or pursue a Master\'s.','duration','2-3 months')
 ),
 JSON_ARRAY(
   JSON_OBJECT('name','Environmental Science Degree','platform','Accredited University','level','Beginner'),
   JSON_OBJECT('name','Intro to GIS','platform','Coursera','level','Intermediate'),
   JSON_OBJECT('name','Sustainability Strategies','platform','edX','level','Advanced')
 ),
 JSON_ARRAY('Environmental Science','Sustainability','Research')
),

('High School Teacher',
 'Educates and mentors students in a chosen subject area.',
 'Education', 'Teaching', 5.0, 70000, 35000, 150000,
 'low', 'active', 80,
 'Lesson planning, classroom teaching, grading, parent-teacher communication.',
 'Bachelor\'s in subject area + teaching certification',
 JSON_ARRAY(
   JSON_OBJECT('title','Subject Mastery','desc','Deep knowledge in your teaching subject.','duration','3-4 years (degree)'),
   JSON_OBJECT('title','Teaching Certification','desc','Education coursework + certification exam.','duration','6-12 months'),
   JSON_OBJECT('title','Classroom Practicum','desc','Student teaching under a mentor teacher.','duration','3-6 months'),
   JSON_OBJECT('title','Lesson Planning Skills','desc','Curriculum design, assessment design.','duration','2 months'),
   JSON_OBJECT('title','Classroom Management','desc','Behavior management, engagement techniques.','duration','Ongoing'),
   JSON_OBJECT('title','Job Placement','desc','Apply to schools, interview, onboard.','duration','1-2 months')
 ),
 JSON_ARRAY(
   JSON_OBJECT('name','Subject Degree Program','platform','Accredited University','level','Beginner'),
   JSON_OBJECT('name','Teaching Certification Course','platform','Education Board','level','Intermediate'),
   JSON_OBJECT('name','Classroom Management Strategies','platform','Coursera','level','Advanced')
 ),
 JSON_ARRAY('Curriculum Design','Communication','Mentoring')
),

('Game Developer',
 'Designs and builds video games, from mechanics to visuals.',
 'Technology', 'Game Development', 16.0, 130000, 60000, 300000,
 'medium', 'active', 86,
 'Writing gameplay code, designing levels, optimizing performance, working with artists.',
 'Bachelor\'s in CS/Game Dev or self-taught + shipped projects',
 JSON_ARRAY(
   JSON_OBJECT('title','Programming Foundations','desc','C#/C++, OOP basics.','duration','2 months'),
   JSON_OBJECT('title','Game Engine Basics','desc','Unity or Unreal Engine fundamentals.','duration','3 months'),
   JSON_OBJECT('title','Game Design Principles','desc','Mechanics, level design, player psychology.','duration','2 months'),
   JSON_OBJECT('title','Build Small Games','desc','Ship 3-5 small complete games.','duration','3 months'),
   JSON_OBJECT('title','3D/2D Art Basics','desc','Enough art skill to prototype or collaborate with artists.','duration','2 months'),
   JSON_OBJECT('title','Portfolio & Job Hunt','desc','Polish a flagship project, apply to studios.','duration','2 months')
 ),
 JSON_ARRAY(
   JSON_OBJECT('name','Complete C# Unity Game Developer','platform','Udemy','level','Beginner'),
   JSON_OBJECT('name','Unreal Engine Developer Course','platform','Udemy','level','Intermediate'),
   JSON_OBJECT('name','Game Design Specialization','platform','Coursera / CalArts','level','Advanced')
 ),
 JSON_ARRAY('Game Design','Programming','3D Modeling')
),

('Psychologist / Counsellor',
 'Helps individuals manage mental health and emotional challenges.',
 'Healthcare', 'Mental Health', 14.0, 100000, 50000, 250000,
 'low', 'active', 91,
 'Conducting therapy sessions, assessments, case notes, referrals.',
 'Bachelor\'s + Master\'s in Psychology/Counselling + licensing',
 JSON_ARRAY(
   JSON_OBJECT('title','Psychology Foundations','desc','Core psychology theory and research methods.','duration','3-4 years (degree)'),
   JSON_OBJECT('title','Master\'s Specialization','desc','Clinical, counselling, or educational psychology.','duration','2 years'),
   JSON_OBJECT('title','Supervised Practicum','desc','Hands-on client work under supervision.','duration','1 year'),
   JSON_OBJECT('title','Therapeutic Techniques','desc','CBT, talk therapy, assessment tools.','duration','Ongoing'),
   JSON_OBJECT('title','Licensing','desc','Pass licensing exam/registration requirements.','duration','3-6 months'),
   JSON_OBJECT('title','Practice or Employment','desc','Join a clinic or start private practice.','duration','Ongoing')
 ),
 JSON_ARRAY(
   JSON_OBJECT('name','Psychology Degree Program','platform','Accredited University','level','Beginner'),
   JSON_OBJECT('name','Intro to CBT Techniques','platform','Coursera','level','Intermediate'),
   JSON_OBJECT('name','Clinical Supervision & Practicum','platform','University-affiliated','level','Advanced')
 ),
 JSON_ARRAY('Counselling','Communication','Research')
),

('Content Writer / Journalist',
 'Researches and writes articles, stories, and content for media.',
 'Marketing', 'Content', 7.0, 75000, 35000, 180000,
 'high', 'active', 70,
 'Researching topics, writing drafts, editing, pitching stories, meeting deadlines.',
 'Bachelor\'s in Journalism/English (or strong portfolio)',
 JSON_ARRAY(
   JSON_OBJECT('title','Writing Fundamentals','desc','Grammar, style, structure across formats.','duration','1 month'),
   JSON_OBJECT('title','Research & Fact-Checking','desc','Source verification, interviewing basics.','duration','1 month'),
   JSON_OBJECT('title','Niche Specialization','desc','Pick tech, finance, lifestyle, etc.','duration','2 months'),
   JSON_OBJECT('title','SEO & Digital Writing','desc','Writing for the web, basic SEO.','duration','1 month'),
   JSON_OBJECT('title','Build a Portfolio','desc','Published pieces, guest posts, a personal blog.','duration','3 months'),
   JSON_OBJECT('title','Pitch & Job Hunt','desc','Pitch to publications or apply to content roles.','duration','2 months')
 ),
 JSON_ARRAY(
   JSON_OBJECT('name','Writing Skills Specialization','platform','Coursera','level','Beginner'),
   JSON_OBJECT('name','SEO Content Writing','platform','HubSpot Academy','level','Intermediate'),
   JSON_OBJECT('name','Journalism Essentials','platform','edX','level','Advanced')
 ),
 JSON_ARRAY('Content Creation','Communication','Journalism')
),

('Aerospace Engineer',
 'Designs aircraft, spacecraft, and related systems.',
 'Engineering', 'Aerospace', 10.0, 170000, 90000, 380000,
 'low', 'emerging', 88,
 'Designing components, running simulations, testing prototypes, ensuring safety compliance.',
 'Bachelor\'s/Master\'s in Aerospace Engineering',
 JSON_ARRAY(
   JSON_OBJECT('title','Physics & Math Foundations','desc','Mechanics, calculus, fluid dynamics basics.','duration','6 months'),
   JSON_OBJECT('title','Aerospace Engineering Degree','desc','Core coursework: aerodynamics, propulsion, structures.','duration','4 years'),
   JSON_OBJECT('title','CAD & Simulation Tools','desc','CATIA/SolidWorks, CFD basics.','duration','3 months'),
   JSON_OBJECT('title','Internship','desc','Work with an aerospace company or research lab.','duration','6 months'),
   JSON_OBJECT('title','Specialization','desc','Propulsion, structures, avionics, etc.','duration','Ongoing'),
   JSON_OBJECT('title','Job Hunt','desc','Apply to aerospace firms or research institutions.','duration','2-3 months')
 ),
 JSON_ARRAY(
   JSON_OBJECT('name','Aerospace Engineering Degree','platform','Accredited University','level','Beginner'),
   JSON_OBJECT('name','Introduction to Aerodynamics','platform','MIT OpenCourseWare','level','Intermediate'),
   JSON_OBJECT('name','Spacecraft Dynamics & Control','platform','Coursera','level','Advanced')
 ),
 JSON_ARRAY('Physics','Mathematics','Mechanical Design','CAD')
),

('Corporate Lawyer',
 'Advises businesses on legal matters and handles contracts/compliance.',
 'Law', 'Corporate Law', 6.0, 180000, 80000, 500000,
 'medium', 'active', 84,
 'Drafting contracts, advising clients, research, negotiations, compliance review.',
 'Bachelor\'s + Law degree (JD/LLB) + bar exam',
 JSON_ARRAY(
   JSON_OBJECT('title','Pre-Law Foundations','desc','Strong reading/writing/critical thinking base.','duration','3-4 years (degree)'),
   JSON_OBJECT('title','Law School','desc','Core legal coursework (JD/LLB).','duration','3 years'),
   JSON_OBJECT('title','Internships/Clerkships','desc','Work with law firms or in-house legal teams.','duration','6-12 months'),
   JSON_OBJECT('title','Bar Exam Prep','desc','Intensive exam preparation.','duration','3-6 months'),
   JSON_OBJECT('title','Bar Exam & Licensing','desc','Pass the bar and get licensed to practice.','duration','3-6 months'),
   JSON_OBJECT('title','Join a Firm or Practice','desc','Start as an associate, build expertise.','duration','Ongoing')
 ),
 JSON_ARRAY(
   JSON_OBJECT('name','Law Degree Program','platform','Accredited Law School','level','Beginner'),
   JSON_OBJECT('name','Contract Law Fundamentals','platform','Coursera','level','Intermediate'),
   JSON_OBJECT('name','Bar Exam Prep Course','platform','Local Bar Review','level','Advanced')
 ),
 JSON_ARRAY('Legal Research','Critical Thinking','Communication')
),

('Cloud Solutions Architect',
 'Designs scalable cloud infrastructure for organizations.',
 'Technology', 'Cloud', 24.0, 220000, 120000, 450000,
 'low', 'emerging', 97,
 'Designing cloud architecture, optimizing costs, ensuring security, migrating systems.',
 'Bachelor\'s in CS/IT + cloud certifications (AWS/Azure/GCP)',
 JSON_ARRAY(
   JSON_OBJECT('title','Networking & Systems Basics','desc','Servers, networking, Linux fundamentals.','duration','2 months'),
   JSON_OBJECT('title','Pick a Cloud Platform','desc','AWS, Azure, or GCP fundamentals.','duration','3 months'),
   JSON_OBJECT('title','Core Certification','desc','AWS Solutions Architect Associate or equivalent.','duration','3 months'),
   JSON_OBJECT('title','Hands-on Projects','desc','Deploy real infrastructure, IaC with Terraform.','duration','3 months'),
   JSON_OBJECT('title','Advanced Certification','desc','Professional-level cloud architect cert.','duration','3 months'),
   JSON_OBJECT('title','Job Hunt','desc','Apply for cloud/DevOps/architect roles.','duration','2 months')
 ),
 JSON_ARRAY(
   JSON_OBJECT('name','AWS Cloud Practitioner','platform','AWS Training','level','Beginner'),
   JSON_OBJECT('name','AWS Solutions Architect Associate','platform','AWS Training / A Cloud Guru','level','Intermediate'),
   JSON_OBJECT('name','Terraform & Infrastructure as Code','platform','Udemy','level','Advanced')
 ),
 JSON_ARRAY('Cloud Computing','Software Engineering','Problem Solving')
),

('AI/ML Engineer',
 'Builds and deploys machine learning models into production systems.',
 'Technology', 'Artificial Intelligence', 28.0, 200000, 110000, 450000,
 'low', 'emerging', 98,
 'Training models, deploying ML pipelines, optimizing performance, collaborating with data scientists.',
 'Bachelor\'s/Master\'s in CS, Math, or related + strong project portfolio',
 JSON_ARRAY(
   JSON_OBJECT('title','Python & Math Foundations','desc','Linear algebra, calculus, Python programming.','duration','2 months'),
   JSON_OBJECT('title','Machine Learning Fundamentals','desc','Supervised/unsupervised learning, model evaluation.','duration','3 months'),
   JSON_OBJECT('title','Deep Learning','desc','Neural networks, CNNs, RNNs, transformers.','duration','3 months'),
   JSON_OBJECT('title','MLOps & Deployment','desc','Deploying models, monitoring, scaling.','duration','2 months'),
   JSON_OBJECT('title','Real Projects','desc','End-to-end ML projects, possibly open source contributions.','duration','3 months'),
   JSON_OBJECT('title','Specialize & Job Hunt','desc','NLP, computer vision, or MLOps focus + apply.','duration','2 months')
 ),
 JSON_ARRAY(
   JSON_OBJECT('name','Machine Learning Specialization','platform','Coursera / Stanford','level','Beginner'),
   JSON_OBJECT('name','Deep Learning Specialization','platform','Coursera / DeepLearning.AI','level','Intermediate'),
   JSON_OBJECT('name','MLOps Specialization','platform','Coursera','level','Advanced')
 ),
 JSON_ARRAY('Machine Learning','Python','Deep Learning','Data Analysis')
);
