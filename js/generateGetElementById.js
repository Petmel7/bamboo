function generateGetElementById() {
    const searchInput = document.getElementById('searchInput').value;
    const friendsContainer = document.getElementById('friendsDataContainer');

    return { searchInput, friendsContainer };
}