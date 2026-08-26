const menuToggle1 = document.getElementById('menu-toggle1'); // hamburger
const menuToggle2 = document.getElementById('menu-toggle2'); // close icon
const nav = document.querySelector('nav');
const header = document.querySelector('header');

menuToggle1.addEventListener('click', () => {
    nav.classList.add('active');
    menuToggle1.style.display = 'none';
    menuToggle2.style.display = 'block';
});

menuToggle2.addEventListener('click', () => {
    nav.classList.remove('active');
    menuToggle1.style.display = 'block';
    menuToggle2.style.display = 'none';
});


// Scroll listener for header hide/show
let lastScrollY = window.scrollY;

window.addEventListener('scroll', () => {
    if (window.scrollY > lastScrollY && window.scrollY > 10) {
        // scrolling down past 10px → hide header
        header.classList.add('hidden');
    } else {
        // scrolling up → show header
        header.classList.remove('hidden');
    }
    lastScrollY = window.scrollY;
});


// ================================================
// generate course options based on selected department
// =========================================
document.getElementById('department_id').addEventListener('change', function () {
    let deptId = this.value;
    let courseSelect = document.getElementById('course_id');

    //  Clear existing options
    courseSelect.innerHTML = '<option value="">Select Course</option>';

    if (deptId) {
        spinner.style.display = 'inline';
        
        let loadingOpt = document.createElement('option');
        loadingOpt.textContent = "Fetching courses...";
        loadingOpt.disabled = true;
        loadingOpt.selected = true;
        courseSelect.appendChild(loadingOpt);


        fetch('fetch_courses.php?department_id=' + deptId)
            .then(response => response.json())
            .then(data => {
                courseSelect.innerHTML = '<option value="">Select Course</option>';
                if (data.length > 0) {
                    data.forEach(course => {
                        let opt = document.createElement('option');
                        opt.value = course.id;
                        opt.textContent = course.name;
                        courseSelect.appendChild(opt);
                    });

                }
                else {
                    let opt = document.createElement('option');
                    opt.value = "";
                    opt.textContent = "No course available!";
                    opt.disabled = true;
                    courseSelect.appendChild(opt);
                }
                spinner.style.display = 'none';

            })
            .catch(err => console.error('Error fetching courses:', err));
        spinner.style.display = 'none';
    }
});


