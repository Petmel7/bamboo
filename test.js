// Ваш тестовий файл

const { loadMessages } = require('./js/loadMessages.js'); // Підключаємо скрипт, який потрібно протестувати

test('Loading messages correctly', async () => {
    // Підготовка тестових умов
    document.body.innerHTML = `
        <div id="messagesContainer"></div>
    `;

    // Підготовка значень loggedInUserId та recipientId
    const loggedInUserId = 123;
    const recipientId = 456;

    // Виклик функції, яку потрібно протестувати з використанням визначених значень
    loadMessages(loggedInUserId, recipientId);

    // Очікуваний результат після завантаження повідомлень
    const expectedMessages = [ /* ваші очікувані дані про повідомлення */];

    // Перевірка результату
    const messagesContainer = document.getElementById('messagesContainer');
    expect(messagesContainer.innerHTML).toContain(expectedMessages);
});

