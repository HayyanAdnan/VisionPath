-- =============================================
-- DATABASE: future_skills
-- Creates the base tables, then extends users
-- with the extra columns this app needs
-- =============================================

-- 0. Base users table (created if it doesn't exist yet)
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    age INT NULL,
    city VARCHAR(100) NULL
);

-- 1. Extended users table (add columns)
ALTER TABLE users ADD COLUMN IF NOT EXISTS interests JSON NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS strengths JSON NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS subjects JSON NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS education VARCHAR(50) NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS career_goal VARCHAR(100) NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS life_goal VARCHAR(100) NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS work_style VARCHAR(100) NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS user_progress JSON NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS finalized_career JSON NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE users ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- 2. Careers table (ALL 13,000+ careers)
CREATE TABLE IF NOT EXISTS careers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    alternate_titles JSON NULL,
    description TEXT,
    category VARCHAR(100),
    sub_category VARCHAR(100),
    growth_rate DECIMAL(5,2) DEFAULT 0,
    growth_source VARCHAR(50),
    job_postings_count INT DEFAULT 0,
    avg_salary DECIMAL(10,2),
    salary_range_low DECIMAL(10,2),
    salary_range_high DECIMAL(10,2),
    ai_replacement_risk ENUM('low','medium','high','critical') DEFAULT 'low',
    automation_potential DECIMAL(5,2),
    tasks_automatable JSON NULL,
    replaced_by_career_id INT NULL,
    status ENUM('active','emerging','declining','critical','archived','restored') DEFAULT 'active',
    health_score DECIMAL(5,2) DEFAULT 100,
    decline_rate DECIMAL(5,2) DEFAULT 0,
    discovered_date DATE,
    last_validated DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status_growth (status, growth_rate DESC),
    INDEX idx_category (category),
    INDEX idx_ai_risk (ai_replacement_risk),
    INDEX idx_health (health_score DESC),
    FOREIGN KEY (replaced_by_career_id) REFERENCES careers(id)
);

-- 3. Skills table
CREATE TABLE IF NOT EXISTS skills (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE NOT NULL,
    category VARCHAR(50),
    is_emerging BOOLEAN DEFAULT FALSE,
    obsolescence_risk DECIMAL(5,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Career-Skills mapping
CREATE TABLE IF NOT EXISTS career_skills (
    career_id INT,
    skill_id INT,
    importance_level INT DEFAULT 5,
    is_required BOOLEAN DEFAULT TRUE,
    PRIMARY KEY (career_id, skill_id),
    FOREIGN KEY (career_id) REFERENCES careers(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
);

-- 5. Career matches cache (for performance)
CREATE TABLE IF NOT EXISTS career_matches (
    student_id INT,
    career_id INT,
    match_score DECIMAL(7,4),
    rank_position INT,
    match_breakdown JSON NULL,
    calculated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP DEFAULT (CURRENT_TIMESTAMP + INTERVAL 7 DAY),
    PRIMARY KEY (student_id, career_id),
    INDEX idx_student_rank (student_id, rank_position),
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (career_id) REFERENCES careers(id) ON DELETE CASCADE
);

-- 6. Career change log (audit)
CREATE TABLE IF NOT EXISTS career_change_log (
    id INT PRIMARY KEY AUTO_INCREMENT,
    career_id INT,
    action ENUM('added','updated','deprecated','archived','restored','replaced'),
    old_status VARCHAR(20),
    new_status VARCHAR(20),
    old_growth_rate DECIMAL(5,2),
    new_growth_rate DECIMAL(5,2),
    reason TEXT,
    performed_by VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (career_id) REFERENCES careers(id) ON DELETE CASCADE,
    INDEX idx_career_action (career_id, action)
);

-- 7. Career health history
CREATE TABLE IF NOT EXISTS career_health_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    career_id INT,
    health_score DECIMAL(5,2),
    decline_rate DECIMAL(5,2),
    job_postings_count INT,
    avg_salary DECIMAL(10,2),
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (career_id) REFERENCES careers(id) ON DELETE CASCADE,
    INDEX idx_career_date (career_id, recorded_at DESC)
);

-- 8. Career replacements tracking
CREATE TABLE IF NOT EXISTS career_replacements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    old_career_id INT,
    new_career_id INT,
    replacement_reason TEXT,
    replacement_date DATE,
    is_complete BOOLEAN DEFAULT FALSE,
    transition_period INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (old_career_id) REFERENCES careers(id) ON DELETE CASCADE,
    FOREIGN KEY (new_career_id) REFERENCES careers(id) ON DELETE CASCADE
);

-- 9. Emerging career sources
CREATE TABLE IF NOT EXISTS emerging_career_sources (
    id INT PRIMARY KEY AUTO_INCREMENT,
    source_url VARCHAR(500) UNIQUE,
    source_name VARCHAR(100),
    last_scraped TIMESTAMP,
    last_career_count INT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- STORED PROCEDURE: Find matching careers
-- =============================================

DELIMITER //

CREATE PROCEDURE find_matching_careers(
    IN p_student_id INT,
    IN p_limit INT
)
BEGIN
    DECLARE v_student_skills JSON;
    DECLARE v_student_interests JSON;
    DECLARE v_student_education VARCHAR(50);
    
    -- Get student profile
    SELECT interests, strengths, education 
    INTO v_student_interests, v_student_skills, v_student_education
    FROM users 
    WHERE id = p_student_id;
    
    -- Calculate match scores for ALL active careers
    SELECT 
        c.id as career_id,
        c.title,
        c.category,
        c.growth_rate,
        c.ai_replacement_risk,
        c.status,
        c.health_score,
        ROUND(
            -- Skills/Interests match (40%)
            (CASE 
                WHEN v_student_interests IS NOT NULL THEN
                    (SELECT COUNT(*) FROM JSON_TABLE(v_student_interests, '$[*]' COLUMNS(interest VARCHAR(100) PATH '$')) AS t
                     WHERE t.interest IN (
                         SELECT s.name FROM career_skills cs
                         JOIN skills s ON cs.skill_id = s.id
                         WHERE cs.career_id = c.id
                     )) / GREATEST(1, (SELECT COUNT(*) FROM career_skills WHERE career_id = c.id)) * 40
                ELSE 20
            END)
            +
            -- Growth & Health (30%)
            (CASE 
                WHEN c.growth_rate > 15 THEN 30
                WHEN c.growth_rate > 5 THEN 25
                WHEN c.growth_rate > 0 THEN 20
                WHEN c.health_score > 80 THEN 15
                ELSE 10
            END)
            +
            -- AI Risk adjustment (30%)
            (CASE 
                WHEN c.ai_replacement_risk = 'low' THEN 30
                WHEN c.ai_replacement_risk = 'medium' THEN 20
                WHEN c.ai_replacement_risk = 'high' THEN 10
                ELSE 5
            END)
        , 2) as match_score
    FROM careers c
    WHERE c.status NOT IN ('archived')
    AND c.health_score > 20
    ORDER BY match_score DESC
    LIMIT p_limit;
    
END //

DELIMITER ;