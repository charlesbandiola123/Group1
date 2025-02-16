const openModalButtons = document.querySelectorAll('[data-modal-target]');
const closeModalButtons = document.querySelectorAll('[data-close-button]');
const openModalButtons2 = document.querySelectorAll('[data-modal-target2]');
const closeModalButtons2 = document.querySelectorAll('[data-close-button2]');
const overlay = document.getElementById('overlay');
const overlay2 = document.getElementById('overlay2');
const AHAC = document.getElementById('AHAC');
const signInBtn = document.getElementById('signIn');
const logIn = document.getElementById('logIn');

logIn.addEventListener('mouseover', () => {
    logIn.style.backgroundColor = "blue"
})

logIn.addEventListener('mouseout', () => {
    logIn.style.backgroundColor = "aqua"
})

signInBtn.addEventListener('mouseover', () => {
    signInBtn.style.backgroundColor = "blue"
})

signInBtn.addEventListener('mouseout', () => {
    signInBtn.style.backgroundColor = "aqua"
})

signInBtn.addEventListener('click', () => {
    signInBtn.style.backgroundColor = "aqua"
})

signInBtn.addEventListener('mouseup', () => {
    signInBtn.style.backgroundColor = "blue"
})

AHAC.addEventListener('mouseover', () => {
    AHAC.style.color = "aqua"
})

AHAC.addEventListener('mouseout', () => {
    AHAC.style.color = "blue"
})

AHAC.addEventListener('click', () => {
    openModal2(modal2)
    closeModal(modal)
})




openModalButtons.forEach(button => {
    button.addEventListener('click', () => {
        const modal = document.querySelector(button.dataset.modalTarget);
        openModal(modal);
    })
})

closeModalButtons.forEach(button => {
    button.addEventListener('click', () => {
        const modal = button.closest('.modal');
        closeModal(modal);
    })
})

function openModal(modal){
    if(modal == null) return;
    modal.classList.add('active');
    overlay.classList.add('active');
}

function closeModal(modal){
    if(modal == null) return;
    modal.classList.remove('active');
    overlay.classList.remove('active');
}




openModalButtons2.forEach(button => {
    button.addEventListener('click', () => {
        const modal2 = document.querySelector(button.dataset.modalTarget2);
        openModal2(modal2);
    })
})

closeModalButtons2.forEach(button => {
    button.addEventListener('click', () => {
        const modal2 = button.closest('.modal2');
        closeModal2(modal2);
    })
})

function openModal2(modal){
    if(modal == null) return;
    modal.classList.add('active');
    overlay2.classList.add('active');
}

function closeModal2(modal){
    if(modal == null) return;
    modal.classList.remove('active');
    overlay2.classList.remove('active');
}