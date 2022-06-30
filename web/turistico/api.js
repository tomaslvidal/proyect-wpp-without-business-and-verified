const express = require("express");
const bodyParser = require("body-parser");
const fs = require("fs");
const axios = require("axios");
const shelljs = require("shelljs");

const config = require("./config.json");
const { Client, LocalAuth, Buttons, List } = require("whatsapp-web.js");

process.title = "whatsapp-node-api";
global.client = new Client({
  authStrategy: new LocalAuth({
    //(Math.random() + 1).toString(36).substring(7)
    clientId: '7n3kii'
  }),
  puppeteer: { headless: true, args: ['--no-sandbox', '--disable-setuid-sandbox', '--disable-extensions'] },
});

global.authed = false;

const app = express();

const port = process.env.PORT || config.port;
//Set Request Size Limit 50 MB
app.use(bodyParser.json({ limit: "50mb" }));

app.use(express.json());
app.use(bodyParser.urlencoded({ extended: true }));

client.on("qr", (qr) => {
  console.log("qr");
  fs.writeFileSync("./components/last.qr", qr);
});

client.on("authenticated", () => {
  console.log("AUTH!");
  authed = true;

  try {
    fs.unlinkSync("./components/last.qr");
  } catch (err) {}
});

client.on("auth_failure", () => {
  console.log("AUTH Failed !");
  process.exit();
});

client.on("ready", () => {
  console.log("Client is ready!");
  setInterval(function(){
    //javascript date and hour
    var date = new Date();
    var hour = date.getHours();
    var minute = date.getMinutes();
    var second = date.getSeconds();
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();
    var time = hour + ":" + minute + ":" + second;
    var dateTime = day + "/" + month + "/" + year + " " + time;

    // console.clear();
    // console.log('Still Alive! '+dateTime);
  },30000)
});

client.on("message", async (msg) => {
  if (config.webhook.enabled) {
    if (msg.hasMedia) {
      const attachmentData = await msg.downloadMedia();
      msg.attachmentData = attachmentData;
    }
    axios.post(config.webhook.path, { msg });
  }

  // let sections = [{title:'sectionTitle',rows:[{title:'ListItem1', description: 'desc'},{title:'ListItem2'}]}];
  // let list = new List('List body','btnText',sections,'Title','footer');
  // client.sendMessage(msg.from, list);

  // client.sendMessage(msg.from, 'Msj recibido: '+msg.body);

  axios.post('https://nodesv1.eviajes.online/wsp-services/catchMsg.php', msg)
  .then(function (response) {
    console.log('response',response.data);

    if(response.data.sendback){
      client.sendMessage(msg.from, response.data.msg);
    }
  })
  .catch(function (error) {
    console.log('error',error);
  });
});

client.on("disconnected", () => {
  console.log("disconnected");
});
client.initialize();

const chatRoute = require("./components/chatting");
const groupRoute = require("./components/group");
const authRoute = require("./components/auth");
const contactRoute = require("./components/contact");
const { maxHeaderSize } = require("http");

app.use(function (req, res, next) {
  console.log(req.method + " : " + req.path);
  next();
});
app.use("/chat", chatRoute);
app.use("/group", groupRoute);
app.use("/auth", authRoute);
app.use("/contact", contactRoute);

app.listen(port, () => {
  console.log("Server Running Live on Port : " + port);
});
