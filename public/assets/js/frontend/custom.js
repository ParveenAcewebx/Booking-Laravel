document.addEventListener("DOMContentLoaded", function () {
    let currentSteps = 1;
    const steps = document.querySelectorAll('.step');
    const prevButton = document.querySelector('.previous');
    const nextButtons = document.querySelectorAll('.next');
    const submitButton = document.querySelector('.submit');

    if (steps.length <= 1) {
        // Handle case where there is only 1 step
    }

    if (!steps.length || !prevButton || !nextButtons.length || !submitButton) {
        console.error('Required elements not found!');
        return; // Exit if elements aren't found
    }

    function handleNextButtonClick() {
        if (currentSteps < steps.length) {
            steps[currentSteps - 1].style.display = 'none';
            steps[currentSteps].style.display = 'block';
            currentSteps++;
            prevButton.style.display = 'inline-block';
            const currentNextButton = steps[currentSteps - 1].querySelector('.next');
            if (currentSteps === steps.length) {
                // Last step, hide Next and show Submit
                document.querySelector('.next').style.display = 'none';
                submitButton.style.display = 'inline-block';
            }
        }
    }

    function handlePreviousButtonClick() {
        document.querySelector('.next').style.display = 'inline-block';
        submitButton.style.display = 'none'; // Hide Submit when navigating back
        if (currentSteps === 2) {
            prevButton.style.display = 'none';
        }
        if (currentSteps > 1) {
            steps[currentSteps - 1].style.display = 'none';
            steps[currentSteps - 2].style.display = 'block';
            currentSteps--;
            const currentNextButton = steps[currentSteps - 1].querySelector('.next');
            currentNextButton.classList.remove('hidden');
        }
    }

    // Add event listeners for next and previous buttons
    nextButtons.forEach(button => {
        button.addEventListener('click', handleNextButtonClick);
    });

    prevButton.addEventListener('click', handlePreviousButtonClick);
});