
function formatTime(sentAt) {
    const sentAtDate = new Date(sentAt);
    const hours = sentAtDate.getHours().toString().padStart(2, '0');;
    const minutes = sentAtDate.getMinutes().toString().padStart(2, '0');;
    const formattedTime = `${hours}:${minutes}`;
    return formattedTime;
}

// function formatDate(date) {
//     const today = new Date();
//     const yesterday = new Date(today);
//     yesterday.setDate(yesterday.getDate() - 1);

//     if (date.toDateString() === today.toDateString()) {
//         return 'Сьогодні';
//     } else if (date.toDateString() === yesterday.toDateString()) {
//         return 'Вчора';
//     } else {
//         // Форматування іншої дати
//         const options = { year: 'numeric', month: 'long', day: 'numeric' };
//         return date.toLocaleDateString('uk-UA', options);
//     }
// }
