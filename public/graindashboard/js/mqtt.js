


const mqtt = require('mqtt');

const options = {
    host: '192.168.2.187',
    port: 1883,
    protocol: '',
    username:"",
    password:"",
  };
  
  const client = mqtt.connect(options);

  client.on("connect", () => {	
    console.log("connected"+ client.connected);
  });

  client.subscribe("news/events");
  
  client.on('message', async function (topic, message) {
       var message = message.toString().split('/'); 
        console.log(message);
       devices_events(message);
    })
