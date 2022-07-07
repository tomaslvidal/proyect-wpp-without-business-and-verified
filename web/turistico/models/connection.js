import mysql from 'mysql';
import * as config from './config.json';

var connection = mysql.createConnection({
  host     : config.host,
  user     : config.user,
  password : config.password,
  database : config.database,
  port     : config.port
});

connection.connect(() => {
  console.log(`MySQL ON, port: ${connection.threadId}`);
});

export default connection;
