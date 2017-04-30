/**
 * There is limited javascript in this project, so its been consolidated to one file
 */


/**
 * For use with navigation menu, all pages
 * 
 * Shows or hides the navigation menu
 */
function toggleMenu()
{
    var menu = document.getElementById("navMenu");

    menu.classList.toggle("hideMenu");
    menu.classList.toggle("showMenu");

    document.getElementById("mainBody").classList.toggle("compact");
}

/**
 * For use with navigation menu, all pages
 * 
 * Shows or hides menu sub-items
 * 
 * @param item 
 */
function toggleMenuSubItems(item)
{
    var elements = document.getElementsByClassName(item+"SubItem");
    for (var i = 0; i < elements.length; i++) {
        elements[i].classList.toggle("hiddenSubItem");
    }
}


/**
 * For use in profile/questions
 * 
 * Shows or hides the answers for the question with the provided id
 * 
 * @param questionId 
 */
function toggleAnswers(questionId)
{
    var answers = document.getElementById("answers_" + questionId);

    answers.classList.toggle("hideRow");
}


/**
 * For use in question/index
 * 
 * Adds an answer text box to the question form
 */
function addAnswer()
{
    var container = document.getElementById('answersContainer');

    var newField = document.createElement('div');

    var newInput = document.createElement('input');
    newInput.setAttribute('type', 'text');
    newInput.setAttribute('name', 'answers[]');
    
    newField.appendChild(newInput);

    container.appendChild(newField);
}
