const app = require('express')();
const http = require('http').createServer(app);
const io = require('socket.io')(http, {
  cors: {
    origin: 'http://127.0.0.1:8000',
    methods: ['GET', 'POST'],
    allowedHeaders: ['Content-Type'],
    credentials: true,
  },
});

// Rest of your Socket.io server code
io.on('connection', (socket) => {
  console.log('A user connected');
  
  socket.on('newMessage', (msg) => {
    io.emit('newMessage', JSON.parse(msg));
  });
  
  socket.on('disconnect', () => {
    console.log('A user disconnected');
  });
});
http.listen(3000, () => {
  console.log('Socket.io server listening on port 3000');
});
