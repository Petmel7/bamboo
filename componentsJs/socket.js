const socket = new WebSocket(`ws://localhost:2346/?sender_id=${loggedInUserId}&recipient_id=${recipientId}`);
export { socket };