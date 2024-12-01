document.addEventListener("DOMContentLoaded", () => {
    const headerMenu = document.querySelector('.menu');
    const openMenu = document.querySelector('.open');
    const closeMenu = document.querySelector('.close');
    const navMenu = document.querySelector('.nav');
    const header = document.querySelector('header');
    const navLink = document.querySelectorAll('.nav__link');

    headerMenu.addEventListener('click', () => {
        openMenu.classList.toggle('open-menu');
        closeMenu.classList.toggle('open-menu');
        navMenu.classList.toggle('open-menu')
        header.classList.toggle('open-menu')
    });

    navLink.forEach(element => {
        element.addEventListener('click', ()=> {
            openMenu.classList.toggle('open-menu');
            closeMenu.classList.toggle('open-menu');
            navMenu.classList.toggle('open-menu')
            header.classList.toggle('open-menu')
        })
    });




    const sortContainer = document.querySelector(".controls__sort");
    const directionContainer = document.querySelector(".controls__derection");
    const sortSelected = sortContainer.querySelector(".sort__selected");
    const directionSelected = directionContainer.querySelector(".sort__selected");
    const sortList = sortContainer.querySelector(".sort__list");
    const directionList = directionContainer.querySelector(".sort__list");

    const courseTitle = document.getElementById("course-title");
    const courseDescription = document.getElementById("course-description");
    const modulesList = document.getElementById("course-modules-list");

    const toggleOpenControls = (container, event) => {
        event.stopPropagation();
        container.classList.toggle('open-controls');
    };

    const removeAllOpenControls = () => {
        sortContainer.classList.remove('open-controls');
        directionContainer.classList.remove('open-controls');
    };

    sortContainer.addEventListener('click', (event) => {
        toggleOpenControls(sortContainer, event);
        directionContainer.classList.remove('open-controls');
    });

    directionContainer.addEventListener('click', (event) => {
        toggleOpenControls(directionContainer, event);
        sortContainer.classList.remove('open-controls');
    });

    document.addEventListener('click', removeAllOpenControls);

    const renderCategories = (categories) => {
        categories.forEach(category => {
            const li = document.createElement("li");
            li.className = "list__select";
            li.dataset.value = category;
            li.textContent = category;
            sortList.appendChild(li);
        });
    };

    const renderAllCoursesOption = () => {
        const allCoursesLi = document.createElement("li");
        allCoursesLi.className = "list__select";
        allCoursesLi.dataset.value = "Все курсы";
        allCoursesLi.textContent = "Все курсы";
        sortList.insertBefore(allCoursesLi, sortList.firstChild);
    };

    const updateDirections = (selectedCategory, data, directions) => {
        directionList.innerHTML = "";
        const filteredCourses = selectedCategory === "Все курсы" ? data : directions[selectedCategory] || [];
        filteredCourses.forEach(course => {
            const li = document.createElement("li");
            li.className = "list__select";
            li.dataset.courses = course.category;
            li.dataset.value = course.name;
            li.textContent = course.name;
            directionList.appendChild(li);
        });
    };

    const displayCourseInfo = (courseName, data) => {
        const course = data.find(c => c.name === courseName);
        if (course) {
            courseTitle.textContent = course.title;
            courseDescription.textContent = course.description;
            modulesList.innerHTML = "";
            (course.modules || []).forEach(module => {
                const listItem = document.createElement("li");
                listItem.classList.add("courses__item");

                const questionDiv = document.createElement("div");
                questionDiv.classList.add("courses__question");
                questionDiv.innerHTML = `<p>${module.question}</p><p class="question-plus"></p>`;
                listItem.appendChild(questionDiv);

                const answerDiv = document.createElement("ol");
                answerDiv.classList.add("courses__answer");
                (module.answers || []).forEach(answer => {
                    const paragraph = document.createElement("li");
                    paragraph.textContent = answer;
                    answerDiv.appendChild(paragraph);
                });
                listItem.appendChild(answerDiv);

                modulesList.appendChild(listItem);
            });
        }
    };

    fetch("courses.json")
        .then(response => response.json())
        .then(data => {
            const categories = new Set();
            const directions = {};

            data.forEach(course => {
                categories.add(course.category);
                if (!directions[course.category]) {
                    directions[course.category] = [];
                }
                directions[course.category].push(course);
            });

            renderCategories(categories);
            renderAllCoursesOption();

            sortSelected.textContent = "Начинающий";
            directionSelected.textContent = "Введение в iGaming";

            const handleSortListClick = (event) => {
                if (event.target.classList.contains("list__select")) {
                    const selectedCategory = event.target.dataset.value;
                    sortSelected.textContent = selectedCategory;

                    sortList.querySelectorAll('.list__select').forEach(item => item.classList.remove('selected'));
                    event.target.classList.add('selected');
                    toggleOpenControls(directionContainer, event);
                    toggleOpenControls(sortContainer, event);
                    updateDirections(selectedCategory, data, directions);
                }
            };

            const handleDirectionListClick = (event) => {
                if (event.target.classList.contains("list__select")) {
                    const selectedName = event.target.dataset.value;
                    directionSelected.innerHTML  = `<p>${selectedName}</p>`;

                    directionList.querySelectorAll('.list__select').forEach(item => item.classList.remove('selected'));
                    event.target.classList.add('selected');

                    displayCourseInfo(selectedName, data);
                }
            };

            sortList.addEventListener("click", handleSortListClick);
            directionList.addEventListener("click", handleDirectionListClick);

            updateDirections("Начинающий", data, directions);
            displayCourseInfo("Введение в iGaming", data);
        })
        .catch(error => console.error("Ошибка загрузки JSON:", error));
});

// Open Courses
const modulesList = document.getElementById("course-modules-list");
modulesList.addEventListener('click', (event) => {
    const item = event.target.closest('.courses__item');
    if (item) {
        const coursesItems = modulesList.querySelectorAll('.courses__item');
        coursesItems.forEach(i => {
            if (i !== item) {
                i.classList.remove('open-question');
            }
        });
        item.classList.toggle('open-question');
    }
});

const faqList = document.getElementById("faq__list");

const firstItem = faqList.querySelector('.courses__item');
if (firstItem) {
    firstItem.classList.add('open-question');
}

faqList.addEventListener('click', (event) => {
    const item = event.target.closest('.courses__item');
    if (item) {
        const coursesItems = faqList.querySelectorAll('.courses__item');
        coursesItems.forEach(i => {
            if (i !== item) {
                i.classList.remove('open-question');
            }
        });
        item.classList.toggle('open-question');
    }
});
