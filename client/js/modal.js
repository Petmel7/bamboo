
const isDarkModeEnabled = localStorage.getItem('darkMode') === 'true';
const modalThemeStyle = isDarkModeEnabled ? 'modal-content--dark' : 'modal-content--white';
const textColorClass = isDarkModeEnabled ? 'white-text' : '';

function openModal() {
    document.getElementById('myModal').style.display = 'block';
    const modal = document.querySelector('.modal');

    modal.innerHTML = `
        <div id="modalContent" class="modal-content ${modalThemeStyle}">
            <form class="custom-form" id="photoForm" enctype="multipart/form-data">
                <label for="avatar" class="">
                    <span class="custom-change custom-delete--button ${textColorClass}">Change</span>
                </label>
                <input class="button-input" onchange="changePhoto()" type="file" id="avatar" name="avatar" accept="image/*" style="display: none;">
            </form>

            <button class="custom-delete--button ${textColorClass}" type="button" onclick="comfirmSubmit()">Delete</button>
            <button class="custom-delete--button ${textColorClass}" type="button" onclick="closeModal()">Cancel</button>
        </div>
    `;
}

function closeModal() {
    document.getElementById('myModal').style.display = 'none';
}

window.onclick = function (event) {
    let modal = document.getElementById('myModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

function openModalDelete(messageId, isSender) {
    document.getElementById('myModal').style.display = 'block';
    const modalContent = document.getElementById('modalContent');

    modalContent.innerHTML = `
        <button class="custom-delete--button ${textColorClass}" onclick="deleteMessage(${messageId}, event)">Delete</button>
        <button id="updateId" class="custom-delete--button ${textColorClass}" onclick="openUpdateFormAndCloseModal(${messageId})">Update</button>
        <button class="custom-delete--button ${textColorClass}" onclick="closeModal()">Cancel</button>
    `;

    const updateId = document.getElementById('updateId');
    isSender ? updateId.style.display = 'block' : updateId.style.display = 'none';
}

function openModalDeleteAllChat(messageUserId) {
    document.getElementById('myModal').style.display = 'block';
    const modalContent = document.getElementById('modalContentAllChat');

    modalContent.innerHTML = `
        <h3 class="delete-chat ${textColorClass}">Delete this chat?</h3>
        <button class="custom-delete--button ${textColorClass}" data-userid="${messageUserId}" onclick="deleteUserAllChat(${currentUserId}, ${messageUserId}, event)">Delete</button>
        <button class="custom-delete--button ${textColorClass}" onclick="closeModal()">Cancel</button>
    `;
}