-- =============================================
-- Populate career_skills from required_skills_json
-- Run this AFTER seed_careers.sql.
-- This is what makes matching actually work: get_matches.php joins
-- career_skills -> skills to score interest/strength overlap, so without
-- this step the seeded careers would have roadmap/courses but no skill
-- links, and every career would score the same flat "no data" baseline.
-- =============================================

INSERT INTO career_skills (career_id, skill_id, importance_level)
SELECT
    c.id AS career_id,
    s.id AS skill_id,
    10 - jt.idx AS importance_level  -- first listed skill = most important
FROM careers c
JOIN JSON_TABLE(
    c.required_skills_json,
    '$[*]' COLUMNS (
        idx FOR ORDINALITY,
        skill_name VARCHAR(100) PATH '$'
    )
) AS jt
JOIN skills s ON s.name = jt.skill_name
WHERE c.required_skills_json IS NOT NULL
ON DUPLICATE KEY UPDATE importance_level = VALUES(importance_level);
