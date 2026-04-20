import ws from 'k6/ws';

export const options = {
    vus: 200,
    duration: '10s',
};

export default function () {
    ws.connect('ws://localhost:8081/ws', {}, function (socket) {
        socket.send('bid:100');
        socket.on('message', (m) => {});
    });
}