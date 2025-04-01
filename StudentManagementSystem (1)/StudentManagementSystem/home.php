<php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLP Student Management System - Home</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .home-container {
            min-height: 80vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px;
            text-align: center;
        }
        .header-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .logo-container {
            margin-bottom: 10px;
        }

        .system-header h1 {
            margin-top: 0;
        }
        
        .welcome-section {
            max-width: 800px;
            margin-bottom: 40px;
        }
        
        .welcome-section h1 {
            font-size: 42px;
            color: #3498db;
            margin-bottom: 20px;
        }
        
        .welcome-section p {
            font-size: 18px;
            line-height: 1.8;
            color: #555;
            margin-bottom: 30px;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            width: 100%;
            max-width: 1200px;
            margin-bottom: 40px;
        }
        
        .feature-card {
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background-color: #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            color: white;
            font-size: 36px;
        }
        
        .feature-card h3 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #2c3e50;
        }
        
        .feature-card p {
            color: #666;
            line-height: 1.6;
        }
        
        .getS-container {
            margin-top: 30px;
            display: flex;
            gap: 20px;
        }
        
        .getS-btn {
            padding: 15px 30px;
            font-size: 18px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .getS-primary {
            background-color: #3498db;
            color: white;
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }
        
        .getS-primary:hover {
            background-color: #2980b9;
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(52, 152, 219, 0.4);
        }
        
        .getS-secondary {
            background-color: #f9f9f9;
            color: #333;
            border: 2px solid #e0e4e8;
        }
        
        .getS-secondary:hover {
            background-color: #f1f1f1;
            transform: translateY(-3px);
        }
        
        @media (max-width: 768px) {
            .getS-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header class="system-header">
        <div class="header-content">
            <div class="logo-container">
                <img src="PLP LOGO.png" alt="PLP Logo" class="logo-placeholder">
            </div>
            <h1>PLP Student Management System</h1>
        </div>
    </header>

    <div class="container home-container">
        <div class="welcome-section">
            <h1>Welcome to PLP Student Management System</h1>
            <p>A comprehensive solution for educators to track student performance, manage grades, and monitor academic progress across all educational levels. A intuitive interface makes student management simple and efficient.</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">➕</div>
                <h3>Add Students</h3>
                <p>Easily add new students to the system with detailed information including personal details and academic records. Supports primary, secondary, and tertiary education levels.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Track Performance</h3>
                <p>Monitor student performance with comprehensive scoring systems tailored to different educational levels. View detailed breakdowns of grades across subjects.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">✏️</div>
                <h3>Manage Records</h3>
                <p>Update student information, modify grades, and manage academic records with our user-friendly interface. Keep all student data organized in one place.</p>
            </div>
        </div>
        
        <div class="getS-container">
            <a href="index.php" class="getS-btn getS-primary">Get Started</a>
        </div>
        
</body>
</html>