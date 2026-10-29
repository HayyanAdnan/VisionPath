-- =============================================
-- MIGRATION: Fix career matching + roadmap data
-- Run this AFTER schema.sql (it only adds things,
-- safe to run on an existing future_skills database)
-- =============================================

-- 1. Add the columns the dashboard needs to show real,
--    career-specific info (instead of hardcoded placeholders)
ALTER TABLE careers ADD COLUMN IF NOT EXISTS required_skills_json JSON NULL;
ALTER TABLE careers ADD COLUMN IF NOT EXISTS roadmap_json JSON NULL;
ALTER TABLE careers ADD COLUMN IF NOT EXISTS courses_json JSON NULL;
ALTER TABLE careers ADD COLUMN IF NOT EXISTS day_to_day TEXT NULL;
ALTER TABLE careers ADD COLUMN IF NOT EXISTS education_path VARCHAR(255) NULL;
ALTER TABLE careers ADD COLUMN IF NOT EXISTS interest_tags JSON NULL;

-- 2. Interest -> Category mapping table.
--    This is the missing piece that makes "scan every career by
--    student interest" actually work. The assessment collects broad
--    interests ("Technology", "Healthcare", "Art & Design"...). This
--    table maps each broad interest to the career categories AND
--    specific skill names it should match against, so matching is
--    meaningful instead of a literal (and always-empty) string compare.
CREATE TABLE IF NOT EXISTS interest_skill_map (
    id INT PRIMARY KEY AUTO_INCREMENT,
    interest VARCHAR(100) NOT NULL,
    skill_name VARCHAR(100) NOT NULL,
    weight DECIMAL(3,2) DEFAULT 1.00,
    INDEX idx_interest (interest)
);

-- Seed the interest -> skill map.
-- (skill_name values correspond to rows in the `skills` table and to
--  entries inside careers.required_skills_json)
INSERT INTO interest_skill_map (interest, skill_name, weight) VALUES
-- Technology
('Technology', 'Programming', 1.00), ('Technology', 'Web Development', 0.90),
('Technology', 'Software Engineering', 1.00), ('Technology', 'Cloud Computing', 0.80),
('Technology', 'Cybersecurity', 0.70), ('Technology', 'Mobile Development', 0.80),
-- Science
('Science', 'Research', 1.00), ('Science', 'Lab Techniques', 1.00),
('Science', 'Data Analysis', 0.70), ('Science', 'Biology', 0.80), ('Science', 'Chemistry', 0.80),
-- Art & Design
('Art & Design', 'Graphic Design', 1.00), ('Art & Design', 'UI/UX Design', 1.00),
('Art & Design', 'Illustration', 0.90), ('Art & Design', '3D Modeling', 0.70),
-- Business
('Business', 'Finance', 1.00), ('Business', 'Strategy', 1.00),
('Business', 'Project Management', 0.80), ('Business', 'Accounting', 0.80), ('Business', 'Sales', 0.70),
-- Healthcare
('Healthcare', 'Patient Care', 1.00), ('Healthcare', 'Medical Knowledge', 1.00),
('Healthcare', 'Anatomy', 0.90), ('Healthcare', 'Clinical Skills', 0.90),
-- Data
('Data', 'Data Analysis', 1.00), ('Data', 'Statistics', 1.00),
('Data', 'Machine Learning', 0.80), ('Data', 'SQL', 0.90),
-- Marketing
('Marketing', 'Digital Marketing', 1.00), ('Marketing', 'Content Creation', 0.90),
('Marketing', 'Social Media', 0.90), ('Marketing', 'Branding', 0.80),
-- Environment
('Environment', 'Sustainability', 1.00), ('Environment', 'Environmental Science', 1.00),
('Environment', 'Research', 0.60),
-- Teaching
('Teaching', 'Communication', 1.00), ('Teaching', 'Curriculum Design', 1.00), ('Teaching', 'Mentoring', 0.90),
-- Gaming
('Gaming', 'Game Design', 1.00), ('Gaming', 'Programming', 0.80), ('Gaming', '3D Modeling', 0.70),
-- Psychology
('Psychology', 'Counselling', 1.00), ('Psychology', 'Communication', 0.70), ('Psychology', 'Research', 0.60),
-- Writing
('Writing', 'Content Creation', 1.00), ('Writing', 'Communication', 0.90), ('Writing', 'Journalism', 0.90),
-- Engineering
('Engineering', 'Mechanical Design', 1.00), ('Engineering', 'CAD', 0.90), ('Engineering', 'Problem Solving', 0.70),
-- Space
('Space', 'Physics', 1.00), ('Space', 'Research', 0.80), ('Space', 'Mathematics', 0.70),
-- Law
('Law', 'Legal Research', 1.00), ('Law', 'Communication', 0.70), ('Law', 'Critical Thinking', 0.90),
-- AI
('AI', 'Machine Learning', 1.00), ('AI', 'Python', 0.90), ('AI', 'Data Analysis', 0.70), ('AI', 'Deep Learning', 0.90);

-- 3. Strength -> Skill mapping.
--    Several of the assessment's strength options ("Creativity",
--    "Leadership", "Coding", "Design", "Empathy", "Organisation",
--    "Teaching", "Writing") don't exist as literal skill names in the
--    `skills` table, so a direct string match against career_skills
--    would silently score 0 for them. This table maps those labels to
--    real skill names, the same way interest_skill_map does for interests.
CREATE TABLE IF NOT EXISTS strength_skill_map (
    id INT PRIMARY KEY AUTO_INCREMENT,
    strength VARCHAR(100) NOT NULL,
    skill_name VARCHAR(100) NOT NULL,
    INDEX idx_strength (strength)
);

INSERT INTO strength_skill_map (strength, skill_name) VALUES
('Problem Solving', 'Problem Solving'),
('Mathematics', 'Mathematics'),
('Communication', 'Communication'),
('Research', 'Research'),
('Sales', 'Sales'),
('Data Analysis', 'Data Analysis'),
('Creativity', 'Illustration'), ('Creativity', 'Graphic Design'), ('Creativity', 'Content Creation'),
('Leadership', 'Project Management'), ('Leadership', 'Strategy'),
('Coding', 'Programming'), ('Coding', 'Software Engineering'),
('Design', 'Graphic Design'), ('Design', 'UI/UX Design'),
('Empathy', 'Patient Care'), ('Empathy', 'Counselling'), ('Empathy', 'Communication'),
('Organisation', 'Project Management'), ('Organisation', 'Accounting'),
('Teaching', 'Curriculum Design'), ('Teaching', 'Mentoring'),
('Writing', 'Content Creation'), ('Writing', 'Journalism');