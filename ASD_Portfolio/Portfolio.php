<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheet.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Portfolio</title>
</head>

<body>
    <nav class="navbar">
        <div class="container">
            <a href="#" class="logo1"></a>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#projects">Projects</a></li>
                <li><a href="#contacts">Contacts</a></li>
            </ul>
            <div class="hamburger-menu">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
        </div>
    </nav>

    <script src="./script.js"></script>

    <div class="home">
        <div id="home" class="container-1">
            <h1>Hello, <br>my name is Vonn</h1>
            <p>I am a web developer, I love creatives and design <br>An aspiring UI/UX Developer</p>
            <a href="./Vonn_CV.pdf" download="Vonn_CV.pdf" class="download-btn">Download CV</a>
        </div>
    </div>

    <div class="abt-home">
        <div id="about" class="container-2">
            <div class="profile">
                
                <img src="./images/icon1.JPG" alt="Your Picture" class="profile-picture">
            </div>

            <h2>About Me</h2>

            <div class="about-details">
                <p>
                    My name is Vonn, I am 20 years old, 
                    and I am currently studying at the University Of Southeastern Philippines. 
                    I took the course BSIT under the major of Business Technology and Management. 
                    Since I was young, I had always been fond of technology and how things work behind the scenes. 
                    I love designing and that is why I hope to be a UI/UX Designer someday!
                </p>
            </div>
        </div>
    </div>

    <div class="proj-home">
        <div id="projects" class="container-3">
            <img src="./images/Metaverse.png" alt="Project 1" class="project-image">
            <div class="project-details">
                <p>Project Description:</p>
                <p class="p1">Metaverse IM is a messaging app inspired by the popular video game Persona 5, offering users a unique experience as Phantom Thieves in their mode of communication. The app immerses users in the dimension of metaverse. This project is a prototype of how the application would look like.</p>
                <a href="https://www.figma.com/proto/8u97F9gBzJd4S3xQDgI9Jk?node-id=0-1&t=U4ATJtFoTycVq64O-6" class="proj-btn">Go to Project</a>
            </div>
                
            <img src="./images/Sayuki.png" alt="Project 2" class="project-image">
            <div class="project-details">
                <p>Project Description:</p>
                <p class="p1">This project is a Home Page of a custom Streaming Platform like Netflix, the project contains the famous panel of the Anime Series Initial D. Reimagined as a spin-off that focuses on the lady drivers of the series.</p>
                <a href="https://www.figma.com/file/oekvqr7NX7hnKEGdbqA6hw/Usui-No-Tenshi%3A-Sayuki?type=design&t=U4ATJtFoTycVq64O-6" class="proj-btn">Go to Project</a>
            </div>
        </div>
    </div>

    <div id="contacts" class="container-4">
        <div class="item">
            <div class="contacts">
                <div class="first-text">Let's get in touch</div>
                <img class="img4" src="./images/contactsimg.jpg-removebg-preview.png" alt="">
                <div class="social-links">
                    <span class="secnd-text">Connect with me:</span>
                    <ul class="social-media">
                        <li><a href="https://www.facebook.com/vonn.escodero?mibextid=JRoKGi"><i class='bx bxl-facebook'></i></a></li>
                        <li><a href="https://www.instagram.com/df2_vx?igsh=MXh1eGtzNjE4NHRyeA=="><i class='bx bxl-instagram-alt'></i></a></li>
                        <li><a href="https://x.com/reverie_1111?t=7ekzMFUPJH4YgTZvqPXSuw&s=09"><i class='bx bxl-twitter'></i></a></li>
                        <li><a href="https://github.com/001Shen"><i class='bx bxl-github'></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="submit-form">
                <h4 class="third-text text">Contact Me</h4>
                <form action="save_messages.php" method="post">
                    <div class="input-box">
                        <input type="text" class="input" name="name" required>
                        <label for="name">Name</label>
                    </div>
                    <div class="input-box">
                        <input type="email" class="input" name="email" required>
                        <label for="email">Email</label>
                    </div>
                    <div class="input-box">
                        <input type="tel" class="input" name="phone" required>
                        <label for="phone">Phone</label>
                    </div>
                    <div class="input-box">
                        <textarea name="message" class="input" required></textarea>
                        <label for="message">Message</label>
                    </div>
                    <input type="submit" class="btn1" value="Submit">
                </form>
            </div>

        </div>
    </div>
<script>
        // Function to update the home content dynamically
        function updateHomeContent() {
            fetch('get_home.php')
                .then(response => response.json())
                .then(data => {
                    document.querySelector('#home h1').innerText = data.header;
                    document.querySelector('#home p').innerText = data.text;
                })
                .catch(error => console.error('Error fetching home content:', error));
        }
    
        // Function to update the about content dynamically
        function updateAboutContent() {
            fetch('get_about.php')
                .then(response => response.json())
                .then(data => {
                    // Update the details text
                    document.querySelector('#about .about-details p').innerText = data.details;
                    
                    // Update the image
                    if(data.image_path) {
                        document.querySelector('#about .profile-picture').src = data.image_path;
                    }
                })
        .catch(error => console.error('Error fetching about content:', error));
    }

    
        // Function to update the projects dynamically
        function updateProjects() {
            fetch('get_projects.php')
                .then(response => response.json())
                .then(data => {
                    const projectsContainer = document.querySelector('#projects .container-3');
                    projectsContainer.innerHTML = ''; // Clear existing content
                    data.forEach(project => {
                        const projectHTML = `
                            <div class="project">
                                <img src="${project.image_path}" alt="" class="project-image">
                                <div class="project-details">
                                    <p>Project Description:</p>
                                    <p class="p1">${project.description}</p>
                                    <a href="${project.project_link}" class="proj-btn">Go to Project</a>
                                </div>
                            </div>
                        `;
                        projectsContainer.insertAdjacentHTML('beforeend', projectHTML);
                    });
                })
                .catch(error => console.error('Error fetching projects:', error));
        }

    
        // Function to update the contacts content dynamically
        function updateContactsContent() {
            fetch('get_contacts_content.php')
                .then(response => response.json())
                .then(data => {
                    document.querySelector('.img4').src = data.contactImage; // Update contact image
                })
            .catch(error => console.error('Error fetching contacts content:', error));
        }

    
        // Call the functions to update content
    updateHomeContent();
    updateAboutContent();
    updateProjects();
    updateContactsContent();
</script>
</body>
</html>
