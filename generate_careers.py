import json
import random

# Career categories with real job titles
career_data = {
    "Technology": {
        "titles": [
            "Software Engineer", "Data Scientist", "Web Developer", "Mobile Developer",
            "DevOps Engineer", "Cloud Architect", "Cybersecurity Analyst", "AI Engineer",
            "Machine Learning Engineer", "Full Stack Developer", "Frontend Developer",
            "Backend Developer", "Database Administrator", "Network Engineer",
            "Systems Administrator", "IT Support Specialist", "QA Engineer",
            "Automation Engineer", "Blockchain Developer", "Game Developer",
            "AR/VR Developer", "IoT Engineer", "Embedded Systems Engineer",
            "Site Reliability Engineer", "Data Engineer", "Analytics Engineer",
            "Business Intelligence Developer", "ETL Developer", "Data Architect",
            "Solution Architect", "Technical Lead", "Engineering Manager",
            "Platform Engineer", "Security Engineer", "Penetration Tester",
            "Security Analyst", "Compliance Officer", "Privacy Engineer",
            "Cloud Engineer", "Infrastructure Engineer", "Release Engineer",
            "Build Engineer", "Test Automation Engineer", "Performance Engineer",
            "Scalability Engineer", "Distributed Systems Engineer", "Systems Engineer"
        ],
        "salary_low": [55000, 60000, 65000, 70000, 75000, 80000, 85000, 90000],
        "salary_high": [90000, 100000, 110000, 120000, 130000, 140000, 150000, 160000, 170000],
        "growth": [8, 10, 12, 15, 18, 20, 22, 25, 28, 30, 35],
        "ai_risk": ["low", "medium"],
        "skills": [
            ["JavaScript", "Python", "Java"],
            ["Python", "R", "SQL"],
            ["HTML", "CSS", "JavaScript"],
            ["Swift", "Kotlin", "Java"],
            ["AWS", "Docker", "Kubernetes"],
            ["AWS", "Azure", "GCP"],
            ["Network Security", "Python", "Linux"],
            ["Python", "TensorFlow", "PyTorch"],
            ["Python", "ML", "Statistics"],
            ["JavaScript", "React", "Node.js"],
            ["HTML", "CSS", "React"],
            ["Java", "Spring", "SQL"],
            ["SQL", "Oracle", "MongoDB"],
            ["Cisco", "Linux", "Network"],
            ["Linux", "Windows", "Scripting"],
            ["Hardware", "Windows", "Mac"],
            ["Testing", "Python", "Selenium"],
            ["Python", "Automation", "Scripting"],
            ["Solidity", "Ethereum", "Blockchain"],
            ["Unity", "C#", "Game Design"],
            ["Unity", "Unreal", "C++"],
            ["C++", "Python", "IoT"],
            ["C", "C++", "Embedded"],
            ["AWS", "Python", "Linux"],
            ["Python", "SQL", "Spark"],
            ["Python", "SQL", "Tableau"],
            ["Power BI", "SQL", "DAX"],
            ["Python", "SQL", "ETL"],
            ["SQL", "Data Modeling", "Python"],
            ["Architecture", "Cloud", "Solutions"],
            ["Leadership", "Technical", "Strategy"],
            ["Management", "Agile", "Scrum"],
            ["Kubernetes", "Docker", "AWS"],
            ["Security", "Python", "Linux"],
            ["Kali Linux", "Python", "Security"],
            ["Risk Assessment", "Compliance", "Audit"],
            ["Privacy", "GDPR", "Security"],
            ["AWS", "Terraform", "Python"],
            ["Infrastructure", "Automation", "Linux"],
            ["CI/CD", "Jenkins", "Git"],
            ["Jenkins", "Maven", "Gradle"],
            ["Testing", "Python", "Selenium"],
            ["Performance", "Testing", "Optimization"],
            ["Distributed Systems", "Cloud", "Scalability"],
            ["Distributed Systems", "Go", "Java"],
            ["Systems", "Linux", "Administration"]
        ]
    },
    "Business": {
        "titles": [
            "Business Analyst", "Product Manager", "Project Manager", "Scrum Master",
            "Operations Manager", "Management Consultant", "Strategy Analyst",
            "Financial Analyst", "Investment Banker", "Risk Manager",
            "Compliance Officer", "Auditor", "Accountant", "Tax Consultant",
            "Entrepreneur", "Startup Founder", "Venture Capitalist",
            "Business Development Manager", "Sales Director", "Marketing Manager",
            "Brand Manager", "Digital Marketing Specialist", "SEO Specialist",
            "Content Strategist", "Social Media Manager", "PR Specialist"
        ],
        "salary_low": [45000, 50000, 55000, 60000, 65000, 70000],
        "salary_high": [70000, 80000, 90000, 100000, 110000, 120000, 130000],
        "growth": [5, 7, 8, 10, 12, 14, 15, 18],
        "ai_risk": ["medium", "high"],
        "skills": [
            ["Analytics", "Excel", "SQL"],
            ["Product Strategy", "Agile", "Analytics"],
            ["Project Management", "Agile", "Scrum"],
            ["Scrum", "Agile", "Facilitation"],
            ["Operations", "Management", "Strategy"],
            ["Consulting", "Strategy", "Analytics"],
            ["Strategy", "Analytics", "Excel"],
            ["Financial Analysis", "Excel", "Finance"],
            ["Finance", "Accounting", "Investing"],
            ["Risk Management", "Analytics", "Compliance"],
            ["Compliance", "Legal", "Audit"],
            ["Auditing", "Accounting", "Finance"],
            ["Accounting", "QuickBooks", "Excel"],
            ["Tax", "Accounting", "Finance"],
            ["Entrepreneurship", "Leadership", "Strategy"],
            ["Entrepreneurship", "Leadership", "Innovation"],
            ["Investing", "Finance", "Strategy"],
            ["Sales", "Leadership", "Strategy"],
            ["Sales", "Leadership", "Strategy"],
            ["Marketing", "Strategy", "Leadership"],
            ["Brand Strategy", "Marketing", "Leadership"],
            ["Digital Marketing", "SEO", "Analytics"],
            ["SEO", "Analytics", "Content"],
            ["Content Strategy", "Writing", "Analytics"],
            ["Social Media", "Marketing", "Content"],
            ["PR", "Communications", "Media"]
        ]
    },
    "Healthcare": {
        "titles": [
            "Doctor", "Nurse", "Medical Researcher", "Pharmacist",
            "Dentist", "Veterinarian", "Physiotherapist", "Psychologist",
            "Psychiatrist", "Radiologist", "Surgeon", "Anesthesiologist",
            "Pediatrician", "Cardiologist", "Neurologist", "Oncologist",
            "General Practitioner", "Medical Lab Technician", "Healthcare Administrator",
            "Public Health Specialist", "Epidemiologist", "Clinical Research Coordinator"
        ],
        "salary_low": [50000, 55000, 60000, 65000, 70000, 75000, 80000],
        "salary_high": [80000, 90000, 100000, 110000, 120000, 130000, 140000, 150000],
        "growth": [6, 8, 10, 12, 14, 16, 18],
        "ai_risk": ["low", "medium"],
        "skills": [
            ["Medicine", "Diagnostics", "Patient Care"],
            ["Patient Care", "Medical", "Compassion"],
            ["Research", "Medicine", "Science"],
            ["Pharmacy", "Medicine", "Chemistry"],
            ["Dentistry", "Patient Care", "Surgery"],
            ["Veterinary", "Animal Care", "Surgery"],
            ["Physical Therapy", "Patient Care", "Exercise"],
            ["Psychology", "Therapy", "Mental Health"],
            ["Psychiatry", "Mental Health", "Medicine"],
            ["Radiology", "Imaging", "Medicine"],
            ["Surgery", "Medicine", "Patient Care"],
            ["Anesthesiology", "Medicine", "Patient Care"],
            ["Pediatrics", "Medicine", "Patient Care"],
            ["Cardiology", "Medicine", "Patient Care"],
            ["Neurology", "Medicine", "Patient Care"],
            ["Oncology", "Medicine", "Patient Care"],
            ["Medicine", "Patient Care", "General Practice"],
            ["Lab Science", "Medical", "Research"],
            ["Healthcare", "Management", "Administration"],
            ["Public Health", "Research", "Community"],
            ["Epidemiology", "Research", "Public Health"],
            ["Clinical Research", "Medical", "Research"]
        ]
    },
    "Engineering": {
        "titles": [
            "Civil Engineer", "Mechanical Engineer", "Electrical Engineer",
            "Chemical Engineer", "Aerospace Engineer", "Biomedical Engineer",
            "Environmental Engineer", "Industrial Engineer", "Materials Engineer",
            "Petroleum Engineer", "Structural Engineer", "Geotechnical Engineer",
            "Transport Engineer", "Manufacturing Engineer", "Robotics Engineer",
            "Nanotechnology Engineer", "Nuclear Engineer", "Marine Engineer"
        ],
        "salary_low": [55000, 60000, 65000, 70000, 75000],
        "salary_high": [80000, 90000, 100000, 110000, 120000, 130000],
        "growth": [4, 5, 6, 7, 8, 9, 10],
        "ai_risk": ["medium"],
        "skills": [
            ["Structural Design", "AutoCAD", "Construction"],
            ["Mechanics", "AutoCAD", "Manufacturing"],
            ["Electronics", "Circuit Design", "Systems"],
            ["Chemistry", "Process Design", "Manufacturing"],
            ["Aerodynamics", "Manufacturing", "Design"],
            ["Biomedical", "Engineering", "Medical"],
            ["Environmental Science", "Engineering", "Compliance"],
            ["Manufacturing", "Optimization", "Systems"],
            ["Materials Science", "Manufacturing", "Engineering"],
            ["Petroleum", "Geology", "Engineering"],
            ["Structural Engineering", "Design", "Construction"],
            ["Geotechnical", "Soil Science", "Construction"],
            ["Transportation", "Planning", "Engineering"],
            ["Manufacturing", "Process", "Optimization"],
            ["Robotics", "AI", "Engineering"],
            ["Nanotech", "Materials", "Science"],
            ["Nuclear Physics", "Engineering", "Safety"],
            ["Marine", "Engineering", "Design"]
        ]
    },
    "Design": {
        "titles": [
            "Graphic Designer", "UX Designer", "UI Designer",
            "Product Designer", "Interior Designer", "Architect",
            "Landscape Architect", "Fashion Designer", "Industrial Designer",
            "Game Designer", "Motion Designer", "Visual Designer",
            "Web Designer", "Brand Designer", "Packaging Designer",
            "Exhibition Designer", "Jewelry Designer", "Costume Designer"
        ],
        "salary_low": [35000, 40000, 45000, 50000, 55000, 60000],
        "salary_high": [55000, 60000, 65000, 70000, 75000, 80000, 85000, 90000],
        "growth": [4, 5, 6, 7, 8, 9, 10],
        "ai_risk": ["high"],
        "skills": [
            ["Adobe Suite", "Creativity", "Design"],
            ["UX Research", "Figma", "User Testing"],
            ["UI Design", "Figma", "Sketch"],
            ["Product Design", "UX", "UI"],
            ["Interior Design", "AutoCAD", "Space Planning"],
            ["Architecture", "AutoCAD", "Design"],
            ["Landscape Design", "AutoCAD", "Design"],
            ["Fashion Design", "Illustration", "Sewing"],
            ["Industrial Design", "CAD", "3D Modeling"],
            ["Game Design", "Unity", "Creativity"],
            ["Motion Design", "After Effects", "Animation"],
            ["Visual Design", "Adobe Suite", "Branding"],
            ["Web Design", "HTML", "CSS"],
            ["Branding", "Creative", "Design"],
            ["Packaging Design", "Adobe Suite", "Creativity"],
            ["Exhibition Design", "Space Planning", "3D"],
            ["Jewelry Design", "CAD", "Creativity"],
            ["Costume Design", "Sewing", "Creativity"]
        ]
    },
    "Education": {
        "titles": [
            "Teacher", "Professor", "Academic Advisor",
            "Curriculum Developer", "Instructional Designer", "Educational Consultant",
            "School Principal", "Special Education Teacher", "ESL Teacher",
            "University Administrator", "Librarian", "Museum Educator",
            "Online Course Creator", "Tutoring Center Director"
        ],
        "salary_low": [35000, 40000, 45000, 50000, 55000],
        "salary_high": [50000, 55000, 60000, 65000, 70000, 75000, 80000],
        "growth": [3, 4, 5, 6, 7, 8],
        "ai_risk": ["medium", "high"],
        "skills": [
            ["Teaching", "Curriculum", "Communication"],
            ["Teaching", "Research", "Academic Writing"],
            ["Advising", "Education", "Counseling"],
            ["Curriculum Design", "Education", "Instructional Design"],
            ["Instructional Design", "Learning", "Technology"],
            ["Education", "Consulting", "Analysis"],
            ["Education", "Leadership", "Administration"],
            ["Special Education", "Teaching", "Inclusion"],
            ["ESL", "Teaching", "Communication"],
            ["Education", "Administration", "Leadership"],
            ["Library Science", "Research", "Information"],
            ["Education", "Teaching", "Museum"],
            ["Online Learning", "Instructional Design", "Technology"],
            ["Tutoring", "Education", "Leadership"]
        ]
    },
    "Law": {
        "titles": [
            "Lawyer", "Judge", "Corporate Counsel",
            "Criminal Lawyer", "Civil Rights Lawyer", "Environmental Lawyer",
            "Family Lawyer", "Intellectual Property Lawyer", "International Lawyer",
            "Legal Assistant", "Paralegal", "Compliance Officer",
            "Legal Analyst", "Court Reporter", "Mediator"
        ],
        "salary_low": [45000, 50000, 55000, 60000, 65000, 70000],
        "salary_high": [70000, 80000, 90000, 100000, 110000, 120000, 130000, 140000],
        "growth": [5, 6, 7, 8, 9, 10],
        "ai_risk": ["medium"],
        "skills": [
            ["Legal Research", "Litigation", "Advocacy"],
            ["Legal Analysis", "Judgment", "Leadership"],
            ["Corporate Law", "Legal Research", "Compliance"],
            ["Criminal Law", "Litigation", "Legal Research"],
            ["Civil Rights", "Legal Research", "Advocacy"],
            ["Environmental Law", "Legal Research", "Advocacy"],
            ["Family Law", "Mediation", "Advocacy"],
            ["IP Law", "Legal Research", "Advocacy"],
            ["International Law", "Legal Research", "Advocacy"],
            ["Legal Research", "Administration", "Support"],
            ["Legal Research", "Administration", "Support"],
            ["Compliance", "Legal Research", "Regulations"],
            ["Legal Analysis", "Research", "Compliance"],
            ["Legal Reporting", "Transcription", "Attention to Detail"],
            ["Mediation", "Conflict Resolution", "Communication"]
        ]
    }
}

def generate_careers():
    careers = []
    career_id = 1
    
    for category, data in career_data.items():
        titles = data["titles"]
        salary_low_list = data["salary_low"]
        salary_high_list = data["salary_high"]
        growth_list = data["growth"]
        ai_risk_list = data["ai_risk"]
        skills_list = data["skills"]
        
        # Generate multiple careers per title with variations
        for title in titles:
            # Create 2-3 variations per title
            for variation in range(random.randint(1, 2)):
                if len(skills_list) > 0:
                    skills = random.choice(skills_list)
                else:
                    skills = ["General", "Communication", "Teamwork"]
                
                # Generate realistic salary ranges
                salary_low = random.choice(salary_low_list)
                salary_high = salary_low + random.randint(20000, 60000)
                
                # Generate growth rate
                growth = random.choice(growth_list) + random.randint(-3, 5)
                growth = max(2, min(45, growth))
                
                # Generate AI risk
                ai_risk = random.choice(ai_risk_list)
                
                # Generate description based on title
                descriptions = {
                    "Software Engineer": "Design, develop, and maintain software systems and applications.",
                    "Data Scientist": "Extract insights from complex data using advanced analytics.",
                    "Web Developer": "Create and maintain websites and web applications.",
                    # Add more specific descriptions
                }
                description = descriptions.get(title, f"Professional {title} working in the {category} industry.")
                
                # Generate education requirement
                education_options = ["Bachelor's Degree", "Master's Degree", "PhD", "Certification", "Associate's Degree"]
                education = random.choice(education_options)
                
                career = {
                    "id": career_id,
                    "title": title,
                    "category": category,
                    "salary_range_low": salary_low,
                    "salary_range_high": salary_high,
                    "growth_rate": growth,
                    "ai_replacement_risk": ai_risk,
                    "description": description,
                    "skills": skills,
                    "education": education,
                    "match_score": random.randint(40, 90)
                }
                careers.append(career)
                career_id += 1
                
                if career_id > 1000:
                    break
            if career_id > 1000:
                break
        if career_id > 1000:
            break
    
    return careers[:1000]

# Generate 1000 careers
careers_data = generate_careers()

# Save to JSON file
with open('careers_1000.json', 'w') as f:
    json.dump(careers_data, f, indent=2)

print(f"Generated {len(careers_data)} careers")
print("Sample career:", json.dumps(careers_data[0], indent=2))